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
 * Privacy Subsystem implementation for local_edwiserpagebuilder.
 *
 * @package    local_edwiserpagebuilder
 * @copyright  (c) 2022 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_edwiserpagebuilder\privacy;

use core_privacy\local\metadata\collection;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\approved_userlist;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\userlist;
use core_privacy\local\request\writer;
use core_privacy\local\request\transform;

/**
 * Privacy provider for local_edwiserpagebuilder.
 *
 * @package    local_edwiserpagebuilder
 * @copyright  (c) 2022 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider implements
    \core_privacy\local\metadata\provider,
    \core_privacy\local\request\core_userlist_provider,
    \core_privacy\local\request\plugin\provider {
    /**
     * Returns metadata about this plugin's data storage.
     *
     * @param collection $collection The initialised collection to add items to.
     * @return collection A listing of user data stored through this system.
     */
    public static function get_metadata(collection $collection): collection {
        $collection->add_database_table(
            'local_edwiserpagebuilder_taskslist',
            [
                'subject' => 'privacy:metadata:local_edwiserpagebuilder_taskslist:subject',
                'summary' => 'privacy:metadata:local_edwiserpagebuilder_taskslist:summary',
                'createdby' => 'privacy:metadata:local_edwiserpagebuilder_taskslist:createdby',
                'assignedto' => 'privacy:metadata:local_edwiserpagebuilder_taskslist:assignedto',
                'completed' => 'privacy:metadata:local_edwiserpagebuilder_taskslist:completed',
                'timedue' => 'privacy:metadata:local_edwiserpagebuilder_taskslist:timedue',
                'timecreated' => 'privacy:metadata:local_edwiserpagebuilder_taskslist:timecreated',
                'timemodified' => 'privacy:metadata:local_edwiserpagebuilder_taskslist:timemodified',
            ],
            'privacy:metadata:local_edwiserpagebuilder_taskslist'
        );

        // External service: Edwiser license server for license activation and validation.
        $collection->add_external_location_link(
            'edwiser.org',
            [
                'licensekey' => 'privacy:metadata:external:edwiser:licensekey',
                'siteurl' => 'privacy:metadata:external:edwiser:siteurl',
                'useragent' => 'privacy:metadata:external:edwiser:useragent',
            ],
            'privacy:metadata:external:edwiser'
        );

        // External service: Edwiser analytics for plugin installation telemetry.
        $collection->add_external_location_link(
            'edwiser.org/analytics',
            [
                'email' => 'privacy:metadata:external:edwiseranalytics:email',
                'name' => 'privacy:metadata:external:edwiseranalytics:name',
                'ip' => 'privacy:metadata:external:edwiseranalytics:ip',
                'siteurl' => 'privacy:metadata:external:edwiseranalytics:siteurl',
            ],
            'privacy:metadata:external:edwiseranalytics'
        );

        // External service: Edwiser CDN for block content delivery.
        $collection->add_external_location_link(
            'staticcdn.edwiser.org',
            [
                'licensekey' => 'privacy:metadata:external:edwisercdn:licensekey',
                'siteurl' => 'privacy:metadata:external:edwisercdn:siteurl',
            ],
            'privacy:metadata:external:edwisercdn'
        );

        // External service: Google Fonts for loading web fonts.
        $collection->add_external_location_link(
            'fonts.googleapis.com',
            [
                'ipaddress' => 'privacy:metadata:external:googlefonts:ipaddress',
            ],
            'privacy:metadata:external:googlefonts'
        );

        return $collection;
    }

    /**
     * Get the list of contexts that contain user information for the specified user.
     *
     * @param int $userid The user to search.
     * @return contextlist The contextlist containing the list of contexts used in this plugin.
     */
    public static function get_contexts_for_userid(int $userid): contextlist {
        $contextlist = new contextlist();

        // Tasks where user is the creator.
        $sql = "SELECT ctx.id
                  FROM {local_edwiserpagebuilder_taskslist} t
                  JOIN {context} ctx ON ctx.instanceid = 0 AND ctx.contextlevel = :contextlevel
                 WHERE t.createdby = :userid AND t.deleted = 0";
        $params = [
            'contextlevel' => CONTEXT_SYSTEM,
            'userid' => $userid,
        ];
        $contextlist->add_from_sql($sql, $params);

        // Tasks where user is assigned (assignedto is a JSON array of user IDs).
        $sql = "SELECT ctx.id
                  FROM {local_edwiserpagebuilder_taskslist} t
                  JOIN {context} ctx ON ctx.instanceid = 0 AND ctx.contextlevel = :contextlevel
                 WHERE t.deleted = 0
                   AND (" . self::get_assignedto_sql('t.assignedto', ':assigneduserid') . ")";
        $params = [
            'contextlevel' => CONTEXT_SYSTEM,
            'assigneduserid' => $userid,
        ];
        $contextlist->add_from_sql($sql, $params);

        return $contextlist;
    }

    /**
     * Get the list of users who have data within a context.
     *
     * @param userlist $userlist The userlist containing the list of users who have data in this context/plugin combination.
     */
    public static function get_users_in_context(userlist $userlist) {
        $context = $userlist->get_context();

        if (!$context instanceof \context_system) {
            return;
        }

        // Users who created tasks.
        $sql = "SELECT DISTINCT t.createdby AS userid
                  FROM {local_edwiserpagebuilder_taskslist} t
                 WHERE t.deleted = 0";
        $userlist->add_from_sql('userid', $sql, []);

        // Users who are assigned to tasks - need to parse JSON.
        $tasks = self::get_all_active_tasks_with_assignees();
        foreach ($tasks as $task) {
            $assignedto = json_decode($task->assignedto, true);
            if (is_array($assignedto)) {
                foreach ($assignedto as $assigneduserid) {
                    $userlist->add_from_sql(
                        'userid',
                        "SELECT :uid AS userid FROM {local_edwiserpagebuilder_taskslist} WHERE id = :id",
                        ['uid' => (int)$assigneduserid, 'id' => $task->id]
                    );
                }
            }
        }
    }

    /**
     * Export all user data for the specified user, in the specified contexts.
     *
     * @param approved_contextlist $contextlist The approved contexts to export information for.
     */
    public static function export_user_data(approved_contextlist $contextlist) {
        global $DB;

        $userid = $contextlist->get_user()->id;

        foreach ($contextlist->get_contexts() as $context) {
            if (!$context instanceof \context_system) {
                continue;
            }

            // Export tasks created by the user.
            $tasks = $DB->get_records_select(
                'local_edwiserpagebuilder_taskslist',
                'createdby = :userid AND deleted = 0',
                ['userid' => $userid]
            );

            foreach ($tasks as $task) {
                $data = (object) [
                    'subject' => $task->subject,
                    'summary' => $task->summary,
                    'createdby' => transform::user($task->createdby),
                    'assignedto' => $task->assignedto,
                    'completed' => transform::yesno($task->completed),
                    'timedue' => $task->timedue ? transform::datetime($task->timedue) : '-',
                    'timecreated' => transform::datetime($task->timecreated),
                    'timemodified' => transform::datetime($task->timemodified),
                ];

                writer::with_context($context)->export_data(
                    [get_string('tasks', 'local_edwiserpagebuilder'), $task->id],
                    $data
                );
            }

            // Export tasks assigned to the user.
            $alltasks = $DB->get_records_select(
                'local_edwiserpagebuilder_taskslist',
                'deleted = 0'
            );

            foreach ($alltasks as $task) {
                if ((int)$task->createdby === $userid) {
                    continue; // Already exported above.
                }
                $assignedto = json_decode($task->assignedto, true);
                if (is_array($assignedto) && in_array($userid, array_map('intval', $assignedto))) {
                    $data = (object) [
                        'subject' => $task->subject,
                        'summary' => $task->summary,
                        'createdby' => transform::user($task->createdby),
                        'assignedto' => $task->assignedto,
                        'completed' => transform::yesno($task->completed),
                        'timedue' => $task->timedue ? transform::datetime($task->timedue) : '-',
                        'timecreated' => transform::datetime($task->timecreated),
                        'timemodified' => transform::datetime($task->timemodified),
                    ];

                    writer::with_context($context)->export_data(
                        [get_string('tasks', 'local_edwiserpagebuilder'), $task->id],
                        $data
                    );
                }
            }
        }
    }

    /**
     * Delete all data for all users in the specified context.
     *
     * @param \context $context The specific context to delete data for.
     */
    public static function delete_data_for_all_users_in_context(\context $context) {
        global $DB;

        if (!$context instanceof \context_system) {
            return;
        }

        $DB->delete_records('local_edwiserpagebuilder_taskslist');
    }

    /**
     * Delete all user data for the specified user, in the specified contexts.
     *
     * @param approved_contextlist $contextlist The approved contexts and user information to delete information for.
     */
    public static function delete_data_for_user(approved_contextlist $contextlist) {
        global $DB;

        $userid = $contextlist->get_user()->id;

        foreach ($contextlist->get_contexts() as $context) {
            if (!$context instanceof \context_system) {
                continue;
            }

            // Delete tasks created by the user.
            $DB->delete_records('local_edwiserpagebuilder_taskslist', ['createdby' => $userid]);

            // Remove user from assignedto in all tasks.
            self::remove_user_from_assigned_tasks($userid);
        }
    }

    /**
     * Delete multiple users within a single context.
     *
     * @param approved_userlist $userlist The approved context and user information to delete information for.
     */
    public static function delete_data_for_users(approved_userlist $userlist) {
        global $DB;

        $context = $userlist->get_context();
        if (!$context instanceof \context_system) {
            return;
        }

        $userids = $userlist->get_userids();
        if (empty($userids)) {
            return;
        }

        foreach ($userids as $userid) {
            $DB->delete_records('local_edwiserpagebuilder_taskslist', ['createdby' => $userid]);
            self::remove_user_from_assigned_tasks($userid);
        }
    }

    /**
     * Build SQL condition fragment to check if a user ID exists in the JSON assignedto field.
     *
     * @param string $field The database field name.
     * @param string $param The parameter placeholder.
     * @return string SQL fragment.
     */
    private static function get_assignedto_sql(string $field, string $param): string {
        global $DB;
        // The assignedto field stores a JSON array like [1,2,3] or ["1","2","3"].
        // We use LIKE patterns to match the user ID in the JSON string.
        $likequoted = $DB->sql_concat("'%\"'", $param, "'\"%'");
        $likestart = $DB->sql_concat("'%['", $param, "',%'");
        $likemid = $DB->sql_concat("'%,'", $param, "',%'");
        $likeend = $DB->sql_concat("'%,'", $param, "']%'");
        $likeonly = $DB->sql_concat("'%['", $param, "']%'");
        return "({$field} LIKE {$likequoted} OR {$field} LIKE {$likestart} " .
               "OR {$field} LIKE {$likemid} OR {$field} LIKE {$likeend} " .
               "OR {$field} LIKE {$likeonly})";
    }

    /**
     * Get all active tasks that have assignees.
     *
     * @return array Array of task records.
     */
    private static function get_all_active_tasks_with_assignees(): array {
        global $DB;

        return $DB->get_records_select(
            'local_edwiserpagebuilder_taskslist',
            "deleted = 0 AND assignedto IS NOT NULL AND assignedto != ''",
            [],
            '',
            'id, assignedto'
        );
    }

    /**
     * Remove a user from the assignedto field of all tasks.
     *
     * @param int $userid The user ID to remove.
     */
    private static function remove_user_from_assigned_tasks(int $userid) {
        global $DB;

        $tasks = self::get_all_active_tasks_with_assignees();

        foreach ($tasks as $task) {
            $assignedto = json_decode($task->assignedto, true);
            if (!is_array($assignedto)) {
                continue;
            }

            $filtered = array_values(array_filter($assignedto, function ($id) use ($userid) {
                return (int)$id !== $userid;
            }));

            if (count($filtered) !== count($assignedto)) {
                $DB->set_field('local_edwiserpagebuilder_taskslist', 'assignedto', json_encode($filtered), ['id' => $task->id]);
            }
        }
    }
}
