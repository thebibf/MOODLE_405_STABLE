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
 * External function trait for setting block configuration.
 *
 * @package   block_edwiseradvancedblock
 * @copyright (c) 2022 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author    Gourav Govande
 */

namespace block_edwiseradvancedblock\external;
use external_function_parameters;
use external_single_structure;
use external_value;
use stdClass;

/**
 * Trait set_block_config for handling block configuration external functions.
 */
trait set_block_config {
    /**
     * Returns description of method parameters.
     *
     * @return external_function_parameters
     */
    public static function set_block_config_parameters() {
        // Set_block_config_parameters() always return an external_function_parameters().
        // The external_function_parameters constructor expects an array of external_description.
        return new external_function_parameters(
            [
                'instanceid' => new external_value(PARAM_INT, 'Instance Id of Block', VALUE_REQUIRED),
                'blockhtml' => new external_value(PARAM_RAW, 'Html content of given block instance'),
                'blockcss' => new external_value(PARAM_RAW, 'CSS content of given block instance'),
                'blockjs' => new external_value(PARAM_RAW, 'JS content of given block instance'),
            ]
        );
    }
    /**
     * The function itself
     * @return string welcome message
     */
    public static function set_block_config($instanceid, $blockhtml, $blockcss, $blockjs) {
        global $DB, $CFG;
        require_once($CFG->dirroot . "/blocks/edwiseradvancedblock/lib.php");

        // Validate parameters.
        $params = self::validate_parameters(self::set_block_config_parameters(), [
            'instanceid' => $instanceid,
            'blockhtml' => $blockhtml,
            'blockcss' => $blockcss,
            'blockjs' => $blockjs,
        ]);
        $instanceid = $params['instanceid'];
        $blockhtml = $params['blockhtml'];
        $blockcss = $params['blockcss'];
        $blockjs = $params['blockjs'];

        // Check capability.
        $context = \context_block::instance($instanceid);
        self::validate_context($context);
        require_capability('block/edwiseradvancedblock:cancustomizelive', $context);

        $blockrecord = $DB->get_record('block_instances', ['id' => $instanceid]);

        $returnobj = new stdClass();
        $returnobj->status = true;
        $returnobj->msg = get_string('changessaved', 'block_edwiseradvancedblock');

        if (!$blockrecord) {
            $returnobj->status = true;
            $returnobj->msg = get_string('instancenotfound', 'block_edwiseradvancedblock');
            return $returnobj;
        }

        $dataobj = new stdClass();
        $dataobj->html = [
            "text" => revert_cdn_url($blockhtml),
            "format" => 1,
        ];

        $dataobj->css = [
            "text" => revert_cdn_url($blockcss),
            "format" => 1,
        ];

        $dataobj->js = [
            "text" => $blockjs,
            "format" => 1,
        ];

        try {
            $instance = block_instance($blockrecord->blockname, $blockrecord);

            $instance->instance_config_save($dataobj, false);
        } catch (Exception $e) {
            $returnobj->status = false;
            $returnobj->msg = $e->message;
        }

        return $returnobj;
    }
    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function set_block_config_returns() {
        return new external_single_structure(
            [
                'status' => new external_value(PARAM_BOOL, 'Error Boolean True/False'),
                'msg' => new external_value(PARAM_TEXT, 'Success/Failure Message'),
            ]
        );
    }
}
