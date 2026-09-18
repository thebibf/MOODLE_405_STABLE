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
 * Web service: Optimize AI prompt using AI.
 *
 * @package   local_edwiserpagebuilder
 * @copyright (c) 2026 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_edwiserpagebuilder\external;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->libdir . '/externallib.php');

use external_function_parameters;
use external_single_structure;
use external_value;

/**
 * Trait for optimize_ai_prompt web service.
 *
 * @package local_edwiserpagebuilder
 */
trait optimize_ai_prompt {
    /**
     * Parameter definition for optimize_ai_prompt.
     *
     * @return external_function_parameters
     */
    public static function optimize_ai_prompt_parameters(): external_function_parameters {
        return new external_function_parameters([
            'contextid' => new external_value(PARAM_INT, 'The context ID'),
            'prompttext' => new external_value(PARAM_RAW, 'The prompt text to optimize'),
        ]);
    }

    /**
     * Optimize a user prompt using AI.
     *
     * @param int $contextid The context ID.
     * @param string $prompttext The prompt text.
     * @return array The optimization result.
     */
    public static function optimize_ai_prompt(int $contextid, string $prompttext): array {
        $params = self::validate_parameters(self::optimize_ai_prompt_parameters(), [
            'contextid' => $contextid,
            'prompttext' => $prompttext,
        ]);

        $context = \context::instance_by_id($params['contextid']);
        self::validate_context($context);

        require_capability('local/edwiserpagebuilder:epb_can_view_page', $context);

        // Validate prompt before sending to AI for optimization.
        $validator = new \local_edwiserpagebuilder\ai\prompt_validator();
        $validation = $validator->validate($params['prompttext']);
        if (!$validation['valid']) {
            return [
                'success' => false,
                'optimized_prompt' => '',
                'error' => implode(', ', $validation['issues']),
            ];
        }

        if (!\local_edwiserpagebuilder\ai\ai_block_generator::is_ai_available()) {
            return [
                'success' => false,
                'optimized_prompt' => '',
                'error' => \get_string('ai_not_available', 'local_edwiserpagebuilder'),
            ];
        }

        $result = \local_edwiserpagebuilder\ai\ai_block_generator::optimize_prompt(
            $params['contextid'],
            $params['prompttext']
        );

        return [
            'success' => $result['success'],
            'optimized_prompt' => $result['optimized_prompt'],
            'error' => $result['error'] ?? '',
        ];
    }

    /**
     * Return definition for optimize_ai_prompt.
     *
     * @return external_single_structure
     */
    public static function optimize_ai_prompt_returns(): external_single_structure {
        return new external_single_structure([
            'success' => new external_value(PARAM_BOOL, 'Whether optimization succeeded'),
            'optimized_prompt' => new external_value(PARAM_RAW, 'The optimized prompt text'),
            'error' => new external_value(PARAM_RAW, 'Error message if failed', VALUE_DEFAULT, ''),
        ]);
    }
}
