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
 * External functions for remuiblck actions.
 *
 * @package   local_edwiserpagebuilder
 * @copyright (c) 2022 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author Gourav Govande
 */
namespace local_edwiserpagebuilder\external;

use external_function_parameters;
use external_value;
use context_system;
use context_user;

/**
 * Trait for remuiblck action external functions.
 */
trait remuiblck_action {
    /**
     * Describes the parameters for add_new_page
     * @return external_function_parameters
     */
    public static function remuiblck_action_parameters() {
        return new external_function_parameters(
            [
                'action' => new external_value(PARAM_TEXT, 'Action Type'),
                'config' => new external_value(PARAM_RAW, 'block data'),
            ]
        );
    }

    /**
     * Add new custome page
     * @param string page action which shoudl
     * @param  array page configuration data
     */
    public static function remuiblck_action($action, $config) {
        global $PAGE;
        $context = context_system::instance();
        $PAGE->set_context($context);
        self::validate_context($context);
        // Allow users who can manage or edit blocks.
        if (
            !has_capability('moodle/site:manageblocks', $context) &&
            !has_capability('moodle/block:edit', $context) &&
            !has_capability('moodle/my:manageblocks', $context)
        ) {
            require_capability('moodle/block:edit', $context);
        }
        $remuiblckhandler = new \local_edwiserpagebuilder\remuiblck\remuiblck_handler();

        $return = $remuiblckhandler->perform_action($action, $config);

        return json_encode($return);
    }

    /**
     * Describes the  remuiblck_action_returns  value
     * @return external_value
     */
    public static function remuiblck_action_returns() {
        return new external_value(PARAM_RAW, 'Block');
    }
}
