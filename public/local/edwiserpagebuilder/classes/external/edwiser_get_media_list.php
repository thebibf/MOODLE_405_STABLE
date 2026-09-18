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
 * External functions for retrieving media file list.
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

/**
 * Service definition for create new form
 * @copyright (c) 2022 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
trait edwiser_get_media_list {
    /**
     * Returns the functional parameter for create and update form methods.
     * @param  boolean                      $id          true if want to add id in parameters
     * @param  boolean                      $forceupdate If true then add forceupdate to settings array
     * @return external_function_parameters              Functional parameters
     */
    public static function edwiser_get_media_list_parameters() {
        return new external_function_parameters(
            [
                'limitfrom'      => new external_value(PARAM_INT, 'Media file list limit.', VALUE_DEFAULT, '0'),
                'offset'     => new external_value(PARAM_INT, 'Media file list offset.', VALUE_DEFAULT, '20'),
            ]
        );
    }

    /**
     * Return the response structure of create and update form services.
     * @return external_single_structure return structure
     */
    public static function edwiser_get_media_list_returns() {
        return new \external_single_structure(
            [
                'limitto' => new external_value(PARAM_INT, 'Media file id.'),
                'media'   => new \external_multiple_structure(
                    new \external_single_structure(
                        [
                            'file_path'    => new external_value(PARAM_TEXT, 'Path of the file.'),
                            'is_author'    => new external_value(PARAM_BOOL, 'Is current user is author.'),
                            'file_name'    => new external_value(PARAM_TEXT, 'File name.'),
                            'file_id'      => new external_value(PARAM_INT, 'File id.'),
                            'time_created' => new external_value(PARAM_INT, 'File creation time.'),
                            'size'         => new external_value(PARAM_TEXT, 'Size of the file.'),
                            'dimension'    => new external_value(PARAM_TEXT, 'Media file dimension if image.'),
                            'id'           => new external_value(PARAM_INT, 'Media file record id.'),
                        ]
                    )
                ),
            ]
        );
    }

    /**
     * List down the media from the plugins context.
     * @param  array $limitfrom meida file limit.
     * @param  string $offset offset limit.
     * @return array  [media_file_list]
     */
    public static function edwiser_get_media_list($limitfrom, $offset) {
        global $USER;
        $limitto      = $limitfrom + $offset;
        $fileslist   = [];
        $filestorage = get_file_storage();
        $context      = context_system::instance();
        self::validate_context($context);
        require_capability('local/edwiserpagebuilder:epb_can_view_page', $context);
        // Array for the stored files, see /moodle/lib/filestorage/stored_file.php for details.
        $files = $filestorage->get_area_files(
            $context->id,
            self::$pluginname,
            self::$pluginfilearea,
            false,
            'itemid',
            true,
            0,
            $limitfrom,
            $limitto
        );

        foreach ($files as $file) {
            $filename = $file->get_filename();
            if ('.' === $filename) {
                continue;
            }

            $objurl = \moodle_url::make_pluginfile_url(
                $context->id,
                self::$pluginname,
                self::$pluginfilearea,
                $file->get_itemid(),
                $file->get_filepath(),
                $filename
            );
            $filedata = [
                'file_path'    => $objurl->out(),
                'is_author'    => $USER->id === $file->get_userid(),
                'file_name'    => $filename,
                'file_id'      => $file->get_itemid(),
                'time_created' => $file->get_timemodified(),
                'size'         => self::size_filter($file->get_filesize()),
                'dimension'    => '',
                'id'           => $file->get_id(),
            ];
            if ($file->is_valid_image()) {
                $imageinfo              = $file->get_imageinfo();
                $filedata['dimension'] = $imageinfo['width'] . ' x ' . $imageinfo['height'] . ' pixels';
            }
            $fileslist[] = $filedata;
        }
        return [
            'limitto' => $limitfrom + \count($fileslist),
            'media'   => $fileslist,
        ];
    }

    /**
     * Convert bytes to human readable file size.
     *
     * @param int $bytes File size in bytes.
     * @return string Formatted file size string.
     */
    private static function size_filter($bytes) {
        $label = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        for ($i = 0; $bytes >= 1024 && $i < (count($label) - 1); $bytes /= 1024, $i++);
        return(round($bytes, 2) . " " . $label[$i]);
    }
}
