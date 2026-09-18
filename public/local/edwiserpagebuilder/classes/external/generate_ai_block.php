<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Web service: Generate AI block content from a user prompt.
 *
 * @package   local_edwiserpagebuilder
 * @copyright (c) 2026 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_edwiserpagebuilder\external;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->libdir . '/externallib.php');
require_once($CFG->dirroot . '/local/edwiserpagebuilder/lib.php');
use external_function_parameters;
use external_single_structure;
use external_value;

/**
 * Trait for generate_ai_block web service.
 *
 * @package local_edwiserpagebuilder
 */
trait generate_ai_block {
    /**
     * Parameter definition for generate_ai_block.
     *
     * @return external_function_parameters
     */
    public static function generate_ai_block_parameters(): external_function_parameters {
        return new external_function_parameters([
            'contextid' => new external_value(PARAM_INT, 'The context ID'),
            'prompttext' => new external_value(PARAM_RAW, 'The user prompt text'),
            'rawprompt' => new external_value(PARAM_RAW, 'The raw user prompt before scoping instructions'),
        ]);
    }

    /**
     * Generate block content using AI.
     *
     * Enforces free tier limit (BR-008): checks remaining requests before
     * calling AI, increments count after success, returns requests_remaining.
     *
     * @param int $contextid The context ID.
     * @param string $prompttext The user prompt.
     * @param string $rawprompt The raw user prompt before scoping.
     * @return array The generation result.
     */
    public static function generate_ai_block(int $contextid, string $prompttext, string $rawprompt): array {
        global $USER;

        $params = self::validate_parameters(self::generate_ai_block_parameters(), [
            'contextid' => $contextid,
            'prompttext' => $prompttext,
            'rawprompt' => $rawprompt,
        ]);

        $context = \context::instance_by_id($params['contextid']);
        self::validate_context($context);

        require_capability('local/edwiserpagebuilder:epb_can_view_page', $context);

        // BR-008: Verify user is admin/manager.
        if (!check_user_admin_cap()) {
            return [
                'success' => false,
                'html' => '',
                'css' => '',
                'js' => '',
                'combined' => '',
                'error' => \get_string('ai_generation_failed', 'local_edwiserpagebuilder'),
                'requests_remaining' => 0,
            ];
        }

        // BR-008: Check free tier request limit (skip for pro users).
        $requestsremaining = get_ai_requests_remaining($USER->id);
        if ($requestsremaining === 0) {
            return [
                'success' => false,
                'html' => '',
                'css' => '',
                'js' => '',
                'combined' => '',
                'error' => \get_string('ai_requests_exhausted', 'local_edwiserpagebuilder'),
                'requests_remaining' => 0,
            ];
        }

        // Server-side prompt validation to prevent bypass of client-side checks.
        // Validate against the raw user prompt (before scoping instructions were appended).
        $validator = new \local_edwiserpagebuilder\ai\prompt_validator();
        $validation = $validator->validate($params['rawprompt']);
        if (!$validation['valid']) {
            return [
                'success' => false,
                'html' => '',
                'css' => '',
                'js' => '',
                'combined' => '',
                'error' => implode(', ', $validation['issues']),
                'requests_remaining' => $requestsremaining,
            ];
        }

        if (!\local_edwiserpagebuilder\ai\ai_block_generator::is_ai_available()) {
            return [
                'success' => false,
                'html' => '',
                'css' => '',
                'js' => '',
                'combined' => '',
                'error' => \get_string('ai_not_available', 'local_edwiserpagebuilder'),
                'requests_remaining' => $requestsremaining,
            ];
        }

        $result = \local_edwiserpagebuilder\ai\ai_block_generator::generate_content(
            $params['contextid'],
            $params['prompttext']
        );

        // BR-008: Count is incremented via confirm_ai_generation (called by the client
        // after receiving the response), not here, so that cancelling does not consume a credit.

        return [
            'success' => $result['success'],
            'html' => $result['html'],
            'css' => $result['css'],
            'js' => $result['js'],
            'combined' => $result['combined'],
            'error' => $result['error'] ?? '',
            'requests_remaining' => $requestsremaining,
        ];
    }

    /**
     * Return definition for generate_ai_block.
     *
     * @return external_single_structure
     */
    public static function generate_ai_block_returns(): external_single_structure {
        return new external_single_structure([
            'success' => new external_value(PARAM_BOOL, 'Whether generation succeeded'),
            'html' => new external_value(PARAM_RAW, 'Generated HTML content'),
            'css' => new external_value(PARAM_RAW, 'Generated CSS content'),
            'js' => new external_value(PARAM_RAW, 'Generated JS content'),
            'combined' => new external_value(PARAM_RAW, 'Combined content with inline style/script'),
            'error' => new external_value(PARAM_RAW, 'Error message if failed', VALUE_DEFAULT, ''),
            'requests_remaining' => new external_value(
                PARAM_INT,
                'Free tier requests remaining (-1 for unlimited/pro)',
                VALUE_DEFAULT,
                -1
            ),
        ]);
    }
}
