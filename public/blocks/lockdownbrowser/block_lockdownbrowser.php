<?php
// Respondus LockDown Browser Extension for Moodle
// Copyright (c) 2011-2026 Respondus, Inc.  All Rights Reserved.
// Date: January 22, 2026.

class block_lockdownbrowser extends block_base {

    public function init() {

        $this->content_type = BLOCK_TYPE_TEXT;

        // ensure title is unique even if string table is unavailable
        $this->title = get_string("lockdownbrowser", "block_lockdownbrowser");
    }

    public function get_content() {

        global $CFG, $COURSE;
        global $DB; // Trac #9465

        if ($this->content != null) {

            return $this->content;
        }

        $this->content       = new stdClass;
        $this->content->text = '';

        if (bccomp($CFG->version, 2013111800, 2) >= 0) {
            // Moodle 2.6.0+.
            $context = context_course::instance($COURSE->id);
        } else {
            // Prior to Moodle 2.6.0.
            $context = get_context_instance(CONTEXT_COURSE, $COURSE->id);
        }

        $this->content->footer = '';

        if (has_capability('moodle/course:manageactivities', $context)
          || has_capability('moodle/course:viewhiddenactivities', $context) // Trac #3595
           ) {
            // Trac #9465
            //$this->content->footer = '<a href="' . $CFG->wwwroot . '/blocks/lockdownbrowser/dashboard.php?course=' .
            //  $COURSE->id . '">' . get_string('dashboard', 'block_lockdownbrowser') . ' ...</a>';
            $tableName = "lti_types";
            $table = new xmldb_table($tableName);
            $dbman = $DB->get_manager();
            $tableExists = $dbman->table_exists($table);
            $recordExists = false;
            if ($tableExists === true) {
                $records = $DB->get_records($tableName);
                if (count($records) > 0) {
                    foreach ($records as $rec) {
                        if (strcmp($rec->baseurl, "https://smc-service-cloud.respondus2.com/MONServer/moodle/lti-login.do") === 0
                          && $rec->state === 1
                          ) {
                            $recordExists = true;
                            break;
                        }
                    }
                }
            }
            if ($recordExists === false) {
                // LTI dashboard not present, display link to local dashboard
                $this->content->footer = '<a href="' . $CFG->wwwroot . '/blocks/lockdownbrowser/dashboard.php?course=' .
                  $COURSE->id . '">' . get_string('dashboard', 'block_lockdownbrowser') . ' ...</a>';
             }
        }

        return $this->content;
    }

    public function instance_allow_multiple() {

        return false;
    }

    public function applicable_formats() {

        return array(
            'site-index'         => false,
            'course-view'        => true,
            'course-view-social' => false,
            'mod'                => false,
            'mod-quiz'           => false
        );
    }

    public function has_config() {

        return true;
    }
}

