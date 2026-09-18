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
 * Theme external services list
 *
 * @package   theme_remui
 * @copyright (c) 2023 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();
$functions = [
    'theme_remui_handle_bug_feedback_report' => [
        'classname'     => 'theme_remui\external\api',
        'methodname'    => 'handle_bug_feedback_report',
        'description'   => 'Handle the one click bug/feedback report. Gets data from feedback.js ' .
            'and sends the data to WordPress API endpoint.',
        'type'          => 'read',
        'ajax'          => true,
        'loginrequired' => true,
        'requiredcapability' => 'moodle/site:config',
    ],

    'theme_remui_get_msg_contact_list_count' => [
        'classname'     => 'theme_remui\external\api',
        'methodname'    => 'get_msg_contact_list_count',
        'description'   => 'Get the contacts count for msg panel',
        'type'          => 'read',
        'ajax'          => true,
        'loginrequired' => true,
        'requiredcapability' => 'moodle/site:config',
    ],
    'theme_remui_get_login_user_detail' => [
        'classname'     => 'theme_remui\external\api',
        'methodname'    => 'get_login_user_detail',
        'description'   => 'Get the details of logged in users',
        'type'          => 'read',
        'ajax'          => true,
        'loginrequired' => true,
        'requiredcapability' => 'moodle/site:config',
    ],
    'theme_remui_get_myoverviewcourses' => [
        'classname'     => 'theme_remui\external\api',
        'methodname'    => 'get_myoverviewcourses',
        'description'   => 'It will generate course data for block myoverview',
        'type'          => 'write',
        'ajax'          => true,
    ],
    'theme_remui_get_course_stats' => [
        'classname'     => 'theme_remui\external\api',
        'methodname'    => 'get_course_stats',
        'description'   => 'Get course statistics',
        'type'          => 'read',
        'ajax'          => true,
    ],
    'theme_remui_get_courses' => [
    'classname'     => 'theme_remui\external\api',
    'methodname'    => 'get_courses',
    'description'   => 'Get courses',
    'type'          => 'write',
    'ajax'          => true,
    'loginrequired' => false,
    ],
    'theme_remui_enrol_get_course_content' => [
        'classname'     => 'theme_remui\external\api',
        'methodname'    => 'enrol_get_course_content',
        'description'   => 'Enrolment page course content data',
        'type'          => 'read',
        'ajax'          => true,
        'loginrequired' => false,
    ],
    'theme_remui_enrol_get_course_instructors' => [
        'classname'     => 'theme_remui\external\api',
        'methodname'    => 'enrol_get_course_instructors',
        'description'   => 'Enrolment page course Instructors data',
        'type'          => 'read',
        'ajax'          => true,
        'loginrequired' => false,
    ],
    'theme_remui_get_tags' => [
        'classname'     => 'theme_remui\external\api',
        'methodname'    => 'get_tags',
        'description'   => 'Returns HTML of Tags element',
        'type'          => 'read',
        'ajax'          => true,
        'loginrequired' => false,
    ],
    'theme_remui_save_user_profile_settings' => [
        'classname'     => 'theme_remui\external\api',
        'methodname'    => 'save_user_profile_settings',
        'description'   => 'Save user profile data from profile page',
        'type'          => 'write',
        'ajax'          => true,
    ],
    'theme_remui_get_dashboard_stats' => [
        'classname'     => 'theme_remui\external\api',
        'methodname'    => 'get_dashboard_stats',
        'description'   => 'Get dashboard statistics',
        'type'          => 'read',
        'ajax'          => true,
        'loginrequired' => true,
    ],
    'theme_remui_customizer_save_settings' => [
        'classname'     => 'theme_remui\customizer\external\api',
        'methodname'    => 'save_settings',
        'description'   => 'Save customizer settings',
        'type'          => 'write',
        'ajax'          => true,
        'loginrequired' => true,
    ],
    'theme_remui_customizer_get_file_from_setting' => [
        'classname'     => 'theme_remui\customizer\external\api',
        'methodname'    => 'get_file_from_setting',
        'description'   => 'Get file from setting based on item id',
        'type'          => 'read',
        'ajax'          => true,
        'loginrequired' => true,
    ],
    'theme_remui_set_block_pos' => [
        'classname'     => 'theme_remui\external\api',
        'methodname'    => 'set_block_pos',
        'description'   => 'Set the weight of the block and change position',
        'type'          => 'read',
        'ajax'          => true,
        'loginrequired' => true,
    ],
    'theme_remui_enrol_page_action' => [
        'classname'     => 'theme_remui\external\api',
        'methodname'    => 'enroll_page_action',
        'description'   => 'perform enrollment page actions',
        'type'          => 'read',
        'ajax'          => true,
        'loginrequired' => true,
    ],
    'theme_remui_change_frontpagechooser' => [
        'classname'     => 'theme_remui\external\api',
        'methodname'    => 'change_frontpagechooser',
        'description'   => 'Change frontpagechooser for homepage setting',
        'type'          => 'write',
        'ajax'          => true,
        'loginrequired' => true,
    ],
    'theme_remui_do_setup_action' => [
        'classname'     => 'theme_remui\external\api',
        'methodname'    => 'do_setup_action',
        'description'   => 'perform theme setup actions',
        'type'          => 'read',
        'ajax'          => true,
        'loginrequired' => true,
    ],
    'theme_remui_do_feedbackcollection_action' => [
        'classname'     => 'theme_remui\external\api',
        'methodname'    => 'do_feedbackcollection_action',
        'description'   => 'perform theme setup actions',
        'type'          => 'read',
        'ajax'          => true,
        'loginrequired' => true,
    ],
    'theme_remui_get_course_category_menu' => [
        'classname'     => 'theme_remui\external\api',
        'methodname'    => 'get_course_category_menu',
        'description'   => 'Return course category menu HTML for navbar dropdown',
        'type'          => 'read',
        'ajax'          => true,
        'loginrequired' => false,
    ],
    'theme_remui_get_focus_mode_sections' => [
        'classname'     => 'theme_remui\external\api',
        'methodname'    => 'get_focus_mode_sections',
        'description'   => 'Return focus mode activity sections and prev/next links as JSON',
        'type'          => 'read',
        'ajax'          => true,
        'loginrequired' => false,
    ],
    'theme_remui_do_personalization_action' => [
        'classname'     => 'theme_remui\external\api',
        'methodname'    => 'do_personalization_action',
        'description'   => 'perform personalization actions',
        'type'          => 'read',
        'ajax'          => true,
        'loginrequired' => true,
    ],
];
