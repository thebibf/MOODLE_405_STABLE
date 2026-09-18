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
 * Trait for get_ai_requests_remaining web service.
 *
 * Returns the number of AI generation requests remaining for the current user.
 * Used to display the remaining count when the AI modal first opens.
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
 * Trait for get_ai_requests_remaining service.
 *
 * @package local_edwiserpagebuilder
 */
trait get_ai_requests_remaining {
    /**
     * Parameter definition for get_ai_requests_remaining.
     *
     * @return external_function_parameters
     */
    public static function get_ai_requests_remaining_parameters(): external_function_parameters {
        return new external_function_parameters([]);
    }

    /**
     * Get the number of AI generation requests remaining for the current user.
     *
     * @return array Result with remaining requests count.
     */
    public static function get_ai_requests_remaining(): array {
        global $USER;

        // BR-008: Verify user is admin/manager.
        if (!check_user_admin_cap()) {
            return [
                'requests_remaining' => 0,
            ];
        }

        $remaining = get_ai_requests_remaining($USER->id);

        return [
            'requests_remaining' => $remaining,
        ];
    }

    /**
     * Return definition for get_ai_requests_remaining.
     *
     * @return external_single_structure
     */
    public static function get_ai_requests_remaining_returns(): external_single_structure {
        return new external_single_structure([
            'requests_remaining' => new external_value(PARAM_INT, 'Remaining AI generation requests (-1 = unlimited)'),
        ]);
    }
}
