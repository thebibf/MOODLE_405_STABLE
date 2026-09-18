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
 * Web service - Send feedback report.
 *
 * @package   theme_remui
 * @copyright (c) 2023 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace theme_remui\external;

use external_function_parameters;
use external_value;

/**
 * Get message contact list count trait.
 *
 * Provides external API functions for getting message contact list count.
 */
trait get_msg_contact_list_count {
    /**
     * Describes the parameters for get_msg_contact_list_count.
     *
     * @return external_function_parameters
     */
    public static function get_msg_contact_list_count_parameters() {
        return new external_function_parameters(
            [
                'userid' => new external_value(PARAM_INT, 'User ID of logged in user'),
             ]
        );
    }

    /**
     * Get message contact list count.
     *
     * @param string $loggedinuserid Logged in user ID
     * @return string JSON encoded data
     */
    public static function get_msg_contact_list_count($loggedinuserid) {
        global $USER;
        $contactscount = \core_message\api::count_contacts($USER->id);
        $receivedrequest = \core_message\api::get_received_contact_requests_count($USER->id);
        $data = [];
        $data["showmsgcount"] = '<span class ="badge badge-primary edw-msg-panel-badge">' . $contactscount . '</span>';
        $data["showrequestcount"] = '<span class ="badge badge-primary edw-msg-panel-badge">' . $receivedrequest . '</span>';
        return json_encode($data);
    }

    /**
     * Describes the return value for get_msg_contact_list_count.
     *
     * @return external_value
     */
    public static function get_msg_contact_list_count_returns() {
        return  new external_value(PARAM_RAW, 'count of contacts in messaging panel');
    }
}
