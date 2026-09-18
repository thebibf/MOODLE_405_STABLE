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
 * Save user profile settings service
 *
 * @package   theme_remui
 * @copyright (c) 2023 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace theme_remui\external;

use external_function_parameters;
use external_value;
use context_system;
use stdClass;
use context;

/**
 * Set_block_position trait
 * @copyright (c) 2022 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Set block position trait.
 *
 * Provides external API functions for setting block positions.
 */
trait set_block_pos {
    /**
     * Describes the parameters for set_block_pos.
     *
     * @return external_function_parameters
     */
    public static function set_block_pos_parameters() {
        return new external_function_parameters(
            [
                // Block IDs can be 'na' (not applicable) or integer IDs.
                'currentblockid' => new external_value(PARAM_ALPHANUMEXT, 'Current block ID or "na"'),
                'movingblockid' => new external_value(PARAM_ALPHANUMEXT, 'Moving block ID or "na"'),
                'region' => new external_value(PARAM_ALPHANUMEXT, 'Block region name'),
                'blockfoundstatus' => new external_value(PARAM_ALPHANUMEXT, 'Block found status in the region'),
                'operationtype' => new external_value(PARAM_ALPHANUMEXT, 'Operation type (up, down, or move)'),
                'movedirection' => new external_value(PARAM_ALPHANUMEXT, 'Move direction (moveblockup or moveblockdown)'),
                'contextid' => new external_value(PARAM_INT, 'Page context ID'),
             ]
        );
    }

    /**
     * Set block position.
     *
     * @param string $currentblockid Current block ID
     * @param string $movingblockid Moving block ID
     * @param string $region Block region
     * @param string $blockfoundstatus Block found status
     * @param string $operationtype Operation type
     * @param string $movedirection Move direction
     * @param string $contextid Context ID
     * @return bool Move status
     */
    public static function set_block_pos(
        $currentblockid,
        $movingblockid,
        $region,
        $blockfoundstatus,
        $operationtype,
        $movedirection,
        $contextid
    ) {
        global $DB, $CFG;

        // Validate sesskey to prevent CSRF attacks.
        confirm_sesskey();

        // Validate context and capability for block editing.
        $context = \context::instance_by_id($contextid);
        self::validate_context($context);
        require_capability('moodle/block:edit', $context);

        require_once($CFG->dirroot . '/theme/remui/lib.php');
        $movestatus = false;
        $currentblockinstance = new \stdClass();
        $movingblockinstance  = new \stdClass();
        $blockinstancetable = 'block_instances';

        $currentblockinstance = $DB->get_record($blockinstancetable, ["id" => $currentblockid]);
        if ($operationtype == 'up' || $operationtype == 'down') {
            if ($currentblockid != 'na' && $movingblockid != 'na') {
                $movingblockinstance  = $DB->get_record($blockinstancetable, ["id" => $movingblockid]);

                $updateparams = [
                    "id" => $currentblockid,
                    'defaultweight' => $movingblockinstance->defaultweight,
                ];
                $DB->update_record($blockinstancetable, $updateparams);
                $recordexist = $DB->record_exists('block_positions', ['blockinstanceid' => $movingblockinstance->id]);
                if ($recordexist) {
                    edw_reposition_block(
                        $movingblockinstance,
                        $region,
                        $currentblockinstance->defaultweight,
                        $contextid,
                        $movingblockinstance->pagetypepattern,
                        $movingblockinstance->subpagepattern
                    );
                }

                $recordexist = $DB->record_exists('block_positions', ['blockinstanceid' => $currentblockinstance->id]);
                $updateparams = [
                    "id" => $movingblockid,
                    'defaultweight' => $currentblockinstance->defaultweight,
                ];
                $DB->update_record($blockinstancetable, $updateparams);
                if ($recordexist) {
                    edw_reposition_block(
                        $currentblockinstance,
                        $region,
                        $movingblockinstance->defaultweight,
                        $contextid,
                        $currentblockinstance->pagetypepattern,
                        $currentblockinstance->subpagepattern
                    );
                }
                $movestatus = true;
            }
        }
        if ($operationtype == 'move' &&  $movedirection == 'moveblockup') {
            if ($movingblockid == 'na') {
                edw_reposition_block(
                    $currentblockinstance,
                    $region,
                    0,
                    $contextid,
                    $currentblockinstance->pagetypepattern,
                    $currentblockinstance->subpagepattern
                );
                $movestatus = true;
            } else {
                $movingblockinstance  = $DB->get_record($blockinstancetable, ["id" => $movingblockid]);
                edw_reposition_block(
                    $currentblockinstance,
                    $region,
                    $movingblockinstance->defaultweight + 1,
                    $contextid,
                    $currentblockinstance->pagetypepattern,
                    $currentblockinstance->subpagepattern
                );
                $movestatus = true;
            }
        }

        if ($operationtype == 'move' &&  $movedirection == 'moveblockdown') {
            if ($movingblockid == 'na') {
                edw_reposition_block(
                    $currentblockinstance,
                    $region,
                    0,
                    $contextid,
                    $currentblockinstance->pagetypepattern,
                    $currentblockinstance->subpagepattern
                );
                $movestatus = true;
            } else {
                $movingblockinstance  = $DB->get_record($blockinstancetable, ["id" => $movingblockid]);
                edw_reposition_block(
                    $currentblockinstance,
                    $region,
                    $movingblockinstance->defaultweight - 1,
                    $contextid,
                    $currentblockinstance->pagetypepattern,
                    $currentblockinstance->subpagepattern
                );
                $movestatus = true;
            }
        }
        return $movestatus;
    }

    /**
     * Describes the return value for set_block_pos.
     *
     * @return external_value
     */
    public static function set_block_pos_returns() {
        return  new external_value(PARAM_RAW, 'status of block weight changed or not');
    }
}
