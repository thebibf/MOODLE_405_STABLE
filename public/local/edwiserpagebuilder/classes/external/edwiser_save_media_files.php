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
 * External functions for saving media files.
 *
 * @package   local_edwiserpagebuilder
 * @copyright (c) 2022 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author Sudam Chakor
 */

namespace local_edwiserpagebuilder\external;

use external_single_structure;
use external_function_parameters;
use external_value;
use context_system;
use context_user;

/**
 * Service definition for create new form
 * @copyright (c) 2022 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
trait edwiser_save_media_files {
    /**
     * Returns the functional parameter for saving media files.
     * @return external_function_parameters Functional parameters
     */
    public static function edwiser_save_media_files_parameters() {
        return new external_function_parameters(
            [
                'itemid' => new external_value(PARAM_INT, 'Media file list limit.'),
            ]
        );
    }

    /**
     * Return the response structure of create and update form services.
     * @return external_single_structure return structure
     */
    public static function edwiser_save_media_files_returns() {
        return new external_single_structure(
            [
                'status' => new external_value(PARAM_BOOL, 'Boolean success or fails.'),
                'msg'    => new external_value(PARAM_TEXT, 'Error or success message.'),
            ]
        );
    }

    /**
     * List down the media from the plugins context.
     * @param  array $itemid file uploade manager item id.
     * @return array returns the status and message.
     */
    public static function edwiser_save_media_files($itemid) {
        global $USER;
        $responce = [
            'status' => true,
            'msg'    => get_string('filesavingsuccessful', 'local_edwiserpagebuilder'),
        ];
        $context = \context_system::instance();
        self::validate_context($context);
        require_capability('local/edwiserpagebuilder:epb_can_manage_page', $context);
        $fs = get_file_storage();
        $unuseditemid = rand(1, 999999999);
        while ($files = $fs->get_area_files($context->id, self::$pluginname, self::$pluginfilearea, $itemid)) {
            $unuseditemid = rand(1, 999999999);
        }

        $messagetext = \file_save_draft_area_files(
            $itemid,
            $context->id,
            self::$pluginname,
            self::$pluginfilearea,
            $unuseditemid,
            null,
            $responce['msg']
        );
        $contextuser = context_user::instance($USER->id);

        $fs->delete_area_files($contextuser->id, 'user', 'draft', $itemid);

        if ($responce['msg'] !== $messagetext) {
            $responce['msg'] = get_string('filesavingsuccessful', 'local_edwiserpagebuilder');
        }
        return $responce;
    }
}
