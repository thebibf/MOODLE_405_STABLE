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
 * Course progress handler class.
 *
 * @package   local_edwiserpagebuilder
 * @copyright (c) 2022 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @author    2022 WisdmLabs
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace local_edwiserpagebuilder\remuiblck;

use stdClass;
use context_system;

/**
 * This class will handle every operations related to course progress.
 */
class courseprogress {
    /**
     * Get the block context data.
     *
     * @return stdClass Block context.
     */
    public function get_block_context() {
        $context = $this->get_courseprogress_context();

        return $context;
    }

    /**
     * Get course progress context data.
     *
     * @return stdClass Course progress context.
     */
    public function get_courseprogress_context() {
        global $OUTPUT, $PAGE;
        $context = new stdClass();
        $context->alwaysload = get_user_preferences('always-load-progress', false) == true;
        $context->alwaysloadwarning = get_user_preferences('always-load-warning', false) == true ? 1 : 0;
        $context->canview = $this->can_view();
        $context->warningicon = $OUTPUT->image_url("warninig_icon", "local_edwiserpagebuilder");
        $context->editing = $PAGE->user_is_editing();
        return $context;
    }

    /**
     * Check if user can view course progress.
     *
     * @return bool True if user can view.
     */
    public function can_view() {

        if (is_siteadmin()) {
            return true;
        }

        $userobj = \local_edwiserpagebuilder\remuiblck\userhandler::get_instance();

        $options["roles"] = $userobj->get_user_roles_system_wide();
        if (
            in_array("manager", $options['roles']) ||
            in_array("teacher", $options['roles']) ||
            in_array("editingteacher", $options['roles'])
        ) {
            return true;
        }

        return false;
    }

    /**
     * Get course progress list data.
     *
     * @param stdClass $config Configuration object.
     * @return array Course progress list.
     */
    public function get_course_progress_list($config) {
        $loadprogress = $config->loadprogress || get_user_preferences('always-load-progress', false) == true;

        $coursehandler = \local_edwiserpagebuilder\remuiblck\coursehandler::get_instance();
        [$courses, $count] = $coursehandler->teacher_courses_data(
            $config->search,
            $config->start,
            $config->length,
            $config->order,
            $loadprogress
        );
        return [
            "courses" => empty($courses) ? [] : $courses,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
        ];
    }

    /**
     * Get course progress data for a specific course.
     *
     * @param stdClass $config Configuration object.
     * @return stdClass Course progress data.
     */
    public function get_course_progress($config) {
        global $USER;
        $data = new stdClass();
        $course = get_course($config->courseid);
        $data->coursefullname = format_text(trim($course->fullname));
        $data->coursesummary = format_text(trim(strip_tags($course->summary)));
        $data->students = [];

        $coursecontext = context_course::instance($config->courseid);
        $groupid = groups_get_user_groups($config->courseid, $USER->id);
        $students = get_role_users(5, $coursecontext);
        $roleuser = get_user_roles($coursecontext, $USER->id);
        $roleid = 0;
        foreach ($roleuser as $key => $rusers) {
            $roleid = $rusers->roleid;
        }
        if ($roleid == 4) {
            if (count($groupid[0]) > 0) {
                $members = groups_get_groups_members($groupid[0]);
                foreach ($members as $key => $member) {
                    if (!in_array($key, array_keys($students))) {
                        unset($members[$key]);
                    }
                }
                $students = $members;
            }
        }

        $studentcnt   = 0;
        $coursehandler = \local_edwiserpagebuilder\remuiblck\coursehandler::get_instance();
        foreach ($students as $studentid => $student) {
            $studentdata = new stdClass();
            $studentdata->index = ++$studentcnt;
            $studentdata->name = fullname($student);
            $studentdata->id = $studentid;
            $studentdata->lastaccess = $coursehandler->get_last_course_access_time($config->courseid, $studentid)->time;
            $progress = (int)progress::get_course_progress_percentage($course, $student->id);
            if (empty($progress)) {
                $progress = 0;
            }
            $studentdata->progress = $progress;
            $studentdata->progressclass = $progress > 70
                ? 'progress-bar-success'
                : ($progress > 30 ? 'progress-bar-warning' : 'progress-bar-danger');
            $data->students[] = $studentdata;
            unset($students[$studentid]);
        }
        return $data;
    }

    /**
     * Generate block content from template.
     *
     * @param array $context Template context data.
     * @return string Rendered HTML.
     */
    public function generate_block_content($context = []) {

        global $OUTPUT, $CFG;

        return $OUTPUT->render_from_template("local_edwiserpagebuilder/remuiblck/courseprogress", $context);
    }
}
