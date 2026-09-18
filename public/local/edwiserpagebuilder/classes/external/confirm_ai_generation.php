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
 * Trait for confirm_ai_generation web service.
 *
 * Increments the AI generation request count after the client confirms
 * it received the generated content. This prevents cancelled generations
 * from consuming free-tier credits.
 *
 * @package   local_edwiserpagebuilder
 * @copyright (c) 2026 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_edwiserpagebuilder\external;

defined('MOODLE_INTERNAL') || die();

use external_function_parameters;
use external_single_structure;
use external_value;

require_once(__DIR__ . '/../../lib.php');

/**
 * Trait for confirm_ai_generation service.
 *
 * @package local_edwiserpagebuilder
 */
trait confirm_ai_generation {
    /**
     * Parameter definition for confirm_ai_generation.
     *
     * @return external_function_parameters
     */
    public static function confirm_ai_generation_parameters(): external_function_parameters {
        return new external_function_parameters([
            'contextid' => new external_value(PARAM_INT, 'The context ID'),
        ]);
    }

    /**
     * Confirm a successful AI generation and increment the request count.
     *
     * Called by the client after it receives and displays the generated content.
     * This ensures cancelled generations do not consume free-tier credits.
     *
     * @param int $contextid The context ID.
     * @return array Result with success status and remaining requests.
     */
    public static function confirm_ai_generation(int $contextid): array {
        global $USER;

        $params = self::validate_parameters(self::confirm_ai_generation_parameters(), [
            'contextid' => $contextid,
        ]);

        $context = \context::instance_by_id($params['contextid']);
        self::validate_context($context);

        require_capability('local/edwiserpagebuilder:epb_can_view_page', $context);

        // BR-008: Verify user is admin/manager.
        if (!check_user_admin_cap()) {
            return [
                'success' => false,
                'requests_remaining' => 0,
            ];
        }

        $requestsremaining = get_ai_requests_remaining($USER->id);

        // Pro users: no need to increment.
        if ($requestsremaining === -1) {
            return [
                'success' => true,
                'requests_remaining' => -1,
            ];
        }

        // Free tier: increment the used count.
        if ($requestsremaining > 0) {
            $requestsremaining = increment_ai_request_count($USER->id);
        }

        return [
            'success' => true,
            'requests_remaining' => $requestsremaining,
        ];
    }

    /**
     * Return definition for confirm_ai_generation.
     *
     * @return external_single_structure
     */
    public static function confirm_ai_generation_returns(): external_single_structure {
        return new external_single_structure([
            'success' => new external_value(PARAM_BOOL, 'Whether the confirmation succeeded'),
            'requests_remaining' => new external_value(PARAM_INT, 'Remaining AI generation requests (-1 = unlimited)'),
        ]);
    }
}
