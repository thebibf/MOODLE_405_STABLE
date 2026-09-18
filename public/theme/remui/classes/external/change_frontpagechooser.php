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
 * Change frontpage chooser external API.
 *
 * @package   theme_remui
 * @copyright (c) 2022 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author Gourav Govande
 */

 namespace theme_remui\external;

use external_function_parameters;
use external_value;
use context_system;
use context_user;

/**
 * Change frontpage chooser trait.
 *
 * Provides external API functions for changing frontpage chooser settings.
 */
trait change_frontpagechooser {
    /**
     * Describes the parameters for add_new_page
     * @return external_function_parameters
     */
    public static function change_frontpagechooser_parameters() {
        return new external_function_parameters(
            []
        );
    }

    /**
     * Add new custome page
     * @param string page action which shoudl
     * @param  array page configuration data
     */
    public static function change_frontpagechooser() {
        global $PAGE;
        // Validate sesskey to prevent CSRF attacks.
        confirm_sesskey();

        // Validate context and admin permission.
        /** @var \context $context */
        $context = context_system::instance();
        self::validate_context($context);
        require_capability('moodle/site:config', $context);

        $PAGE->set_context($context);

        $ishomepageselected = get_config('theme_remui', 'frontpagechooser') == 1;
        if ($ishomepageselected) {
            if (is_plugin_available('local_edwiserpagebuilder')) {
                set_config('frontpagechooser', 3, 'theme_remui');
            } else {
                set_config('frontpagechooser', 0, 'theme_remui');
            }
        }

        return ['success' => true];
    }

    /**
     * Describes the  change_frontpagechooser_returns  value
     * @return external_value
     */
    public static function change_frontpagechooser_returns() {
        return new external_function_parameters(
            [
                'success' => new external_value(PARAM_BOOL, 'Updation success - true/false'),
            ]
        );
    }
}
