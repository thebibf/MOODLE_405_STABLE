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
 * Get course category menu Service
 *
 * @package   theme_remui
 * @copyright (c) 2023 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace theme_remui\external;

use external_function_parameters;
use theme_remui\utility;
use external_value;
use context_system;

/**
 * Get course category menu service trait
 * @copyright (c) 2022 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
trait get_course_category_menu {
    /**
     * Describes the parameters for get_course_category_menu
     * @return external_function_parameters
     */
    public static function get_course_category_menu_parameters() {
        return new external_function_parameters([]);
    }

    /**
     * Return course category menu HTML for navbar dropdown
     * @return array HTML wrapped in an array
     */
    public static function get_course_category_menu() {
        global $PAGE;
        $context = context_system::instance();
        $PAGE->set_context($context);

        $categories = utility::get_categories_list();
        $html = utility::generatecategorystructure($categories);

        return [
            'html' => $html,
            'status' => true,
        ];
    }

    /**
     * Describes the get_course_category_menu return value
     * @return external_function_parameters
     */
    public static function get_course_category_menu_returns() {
        return new external_function_parameters(
            [
                'html' => new external_value(PARAM_RAW, 'Category tree HTML'),
                'status' => new external_value(PARAM_BOOL, 'Success status'),
            ]
        );
    }
}
