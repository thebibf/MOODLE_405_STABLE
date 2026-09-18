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
 * Web service: Validate AI prompt quality and security.
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
use external_multiple_structure;
use external_value;

/**
 * Trait for validate_ai_prompt web service.
 *
 * @package local_edwiserpagebuilder
 */
trait validate_ai_prompt {
    /**
     * Parameter definition for validate_ai_prompt.
     *
     * @return external_function_parameters
     */
    public static function validate_ai_prompt_parameters(): external_function_parameters {
        return new external_function_parameters([
            'prompttext' => new external_value(PARAM_RAW, 'The prompt text to validate'),
        ]);
    }

    /**
     * Validate a prompt for quality and security.
     *
     * @param string $prompttext The prompt text.
     * @return array The validation result.
     */
    public static function validate_ai_prompt(string $prompttext): array {
        $params = self::validate_parameters(self::validate_ai_prompt_parameters(), [
            'prompttext' => $prompttext,
        ]);

        $context = \context_system::instance();
        self::validate_context($context);

        require_capability('local/edwiserpagebuilder:epb_can_view_page', $context);

        $validator = new \local_edwiserpagebuilder\ai\prompt_validator();
        $result = $validator->validate($params['prompttext']);

        return [
            'valid' => $result['valid'],
            'score' => $result['score'],
            'suggest_optimize' => $validator->should_suggest_optimization($result['score']),
            'issues' => $result['issues'],
            'suggestions' => $result['suggestions'],
        ];
    }

    /**
     * Return definition for validate_ai_prompt.
     *
     * @return external_single_structure
     */
    public static function validate_ai_prompt_returns(): external_single_structure {
        return new external_single_structure([
            'valid' => new external_value(PARAM_BOOL, 'Whether the prompt is valid'),
            'score' => new external_value(PARAM_INT, 'Quality score 0-100'),
            'suggest_optimize' => new external_value(PARAM_BOOL, 'Whether to suggest prompt optimization'),
            'issues' => new external_multiple_structure(
                new external_value(PARAM_TEXT, 'Validation issue message'),
                'List of validation issues',
                VALUE_OPTIONAL
            ),
            'suggestions' => new external_multiple_structure(
                new external_value(PARAM_TEXT, 'Improvement suggestion'),
                'List of improvement suggestions',
                VALUE_OPTIONAL
            ),
        ]);
    }
}
