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
 * Database upgrade steps for Edwiser Page Builder.
 *
 * @package   local_edwiserpagebuilder
 * @copyright (c) 2022 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author    Gourav Govande
 */
defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/local/edwiserpagebuilder/lib.php');
/**
 * Custom code to be run on upgrading the plugin.
 * @param int $oldversion Plugin's old version
 * @return bool True if upgrade successful
 */
function xmldb_local_edwiserpagebuilder_upgrade($oldversion) {
    global $DB;

    // Creating a table for layouts.
    $dbman = $DB->get_manager();

    // Determine which table names exist (old edw_* or new local_edwiserpagebuilder_*).
    $layoutstablename = $dbman->table_exists(new xmldb_table('edw_page_block_layouts'))
        ? 'edw_page_block_layouts'
        : 'local_edwiserpagebuilder_blklayouts';
    $blockstablename = $dbman->table_exists(new xmldb_table('edw_page_blocks'))
        ? 'edw_page_blocks'
        : 'local_edwiserpagebuilder_blocks';

    $table = new xmldb_table($layoutstablename);

    if (!$dbman->table_exists($table)) {
        // Table doesn't exist under either name — create it with the old name
        // (it will be renamed later by rename_edwiserpagebuilder_tables).
        $table = new xmldb_table('edw_page_block_layouts');
        $table->addField(new xmldb_field('id', XMLDB_TYPE_INTEGER, 10, null, true, true));
        $table->addField(new xmldb_field('title', XMLDB_TYPE_CHAR, 255, null, true));
        $table->addField(new xmldb_field('belongsto', XMLDB_TYPE_CHAR, 255, null, true));
        $table->addField(new xmldb_field('label', XMLDB_TYPE_CHAR, 255, true, true));
        $table->addField(new xmldb_field('thumbnail', XMLDB_TYPE_TEXT, 255, null, true));
        $table->addField(new xmldb_field('content', XMLDB_TYPE_TEXT, null, null, true));
        $table->addField(new xmldb_field('version', XMLDB_TYPE_INTEGER, 10, null, false, false));
        $table->addField(new xmldb_field('updateavailable', XMLDB_TYPE_INTEGER, 1, null, false, false, 0));
        $table->addField(new xmldb_field('visible', XMLDB_TYPE_INTEGER, 1, null, false, false, 0));

        $table->addKey(new xmldb_key('primary', XMLDB_KEY_PRIMARY, ['id']));
        $dbman->create_table($table);
    }

    // Updating the blocks table — add fields if missing.
    $table = new xmldb_table($blockstablename);

    // Adding type field.
    $field = new xmldb_field('type', XMLDB_TYPE_CHAR, 100, null, false, false, "block");
    if (!$dbman->field_exists($table, $field)) {
        $dbman->add_field($table, $field);
    }

    // Adding categories field.
    $field = new xmldb_field('categories', XMLDB_TYPE_CHAR, 100, null, false, false);
    if (!$dbman->field_exists($table, $field)) {
        $dbman->add_field($table, $field);
    }

    // Adding locked field.
    $field = new xmldb_field('locked', XMLDB_TYPE_INTEGER, 1, null, false, false, 0);
    if (!$dbman->field_exists($table, $field)) {
        $dbman->add_field($table, $field);
        // Set all existing blocks to unlocked.
        $DB->set_field($blockstablename, 'locked', 0);
    }

    // Adding tier field.
    $field = new xmldb_field('tier', XMLDB_TYPE_CHAR, 100, null, false, false);
    if (!$dbman->field_exists($table, $field)) {
        $dbman->add_field($table, $field);
    }

    create_page_table($dbman);
    create_page_draft_table($dbman);
    create_taskslist_table($dbman);
    move_data_from_remuiblck_to_edwiserpagebuilder();

    // Rename old edw_* tables to local_edwiserpagebuilder_* prefix for Moodle coding standards.
    // This MUST run before any code that uses PHP classes referencing the new table names.
    if ($oldversion < 2026031800) {
        rename_edwiserpagebuilder_tables($dbman);
    }

    // Update the block content on upgradation.
    local_edwiserpagebuilder_update_block_content();
    set_config('edwadvancedblockfatchstatus', true, 'local_edwiserpagebuilder');

    return true;
}

/**
 * Rename legacy edw_* tables to local_edwiserpagebuilder_* prefix.
 *
 * This must run after the old upgrade steps that reference old table names, because
 * those steps may still need to add fields to the old-named tables.
 *
 * @param xmldb_manager $dbman Database manager instance.
 */
function rename_edwiserpagebuilder_tables($dbman) {
    $renames = [
        'edw_page_blocks'        => 'local_edwiserpagebuilder_blocks',
        'edw_page_block_layouts' => 'local_edwiserpagebuilder_blklayouts',
        'edw_pages'              => 'local_edwiserpagebuilder_pages',
        'edw_pages_draft'        => 'local_edwiserpagebuilder_pagesdraft',
        'edw_taskslist'          => 'local_edwiserpagebuilder_taskslist',
    ];

    foreach ($renames as $oldname => $newname) {
        $table = new xmldb_table($oldname);
        if ($dbman->table_exists($table)) {
            $newtable = new xmldb_table($newname);
            if (!$dbman->table_exists($newtable)) {
                $dbman->rename_table($table, $newname);
            }
        }
    }
}

/**
 * Create the pages table.
 *
 * @param xmldb_manager $dbman Database manager instance.
 */
function create_page_table($dbman) {
    // Check both old and new names — if either exists, skip creation.
    $oldtable = new xmldb_table('edw_pages');
    $newtable = new xmldb_table('local_edwiserpagebuilder_pages');
    if ($dbman->table_exists($oldtable) || $dbman->table_exists($newtable)) {
        return;
    }

    $table = new xmldb_table('edw_pages');

    $table->addField(new xmldb_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null));
    $table->addField(new xmldb_field('pagename', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null, 'id'));
    $table->addField(new xmldb_field('pagedesc', XMLDB_TYPE_TEXT, null, null, null, null, null, 'pagename'));
    $table->addField(new xmldb_field('capabilities', XMLDB_TYPE_TEXT, null, null, null, null, null, 'pagedesc'));
    $table->addField(new xmldb_field('pagecontent', XMLDB_TYPE_TEXT, null, null, null, null, null, 'capabilities'));
    $table->addField(new xmldb_field('deleted', XMLDB_TYPE_INTEGER, '1', null, null, null, '0', 'pagecontent'));
    $table->addField(new xmldb_field('pagelayout', XMLDB_TYPE_TEXT, null, null, null, null, null, 'deleted'));
    $table->addField(new xmldb_field('startdate', XMLDB_TYPE_INTEGER, '10', null, null, null, '0', 'pagelayout'));
    $table->addField(new xmldb_field('enddate', XMLDB_TYPE_INTEGER, '10', null, null, null, '0', 'startdate'));
    $table->addField(new xmldb_field('pagemodified', XMLDB_TYPE_INTEGER, '10', null, null, null, '0', 'enddate'));
    $table->addField(new xmldb_field('allowloginonly', XMLDB_TYPE_INTEGER, '1', null, null, null, '0', 'pagemodified'));
    $table->addField(new xmldb_field('visible', XMLDB_TYPE_INTEGER, '1', null, null, null, '0', 'allowloginonly'));
    $table->addField(new xmldb_field('seotag', XMLDB_TYPE_TEXT, null, null, null, null, null, 'visible'));
    $table->addField(new xmldb_field('seodesc', XMLDB_TYPE_TEXT, null, null, null, null, null, 'seotag'));
    $table->addField(new xmldb_field('allowindex', XMLDB_TYPE_INTEGER, '1', null, null, null, '0', 'seodesc'));
    $table->addField(new xmldb_field('refid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null, 'allowindex'));

    $table->addKey(new xmldb_key('primary', XMLDB_KEY_PRIMARY, ['id']));
    $table->addKey(new xmldb_key('fk_refid', XMLDB_KEY_FOREIGN, ['refid'], 'edw_pages_draft', ['id']));

    if (!$dbman->table_exists($table)) {
        $dbman->create_table($table);
    }
}

/**
 * Create the pages draft table.
 *
 * @param xmldb_manager $dbman Database manager instance.
 */
function create_page_draft_table($dbman) {
    // Check both old and new names — if either exists, skip creation.
    $oldtable = new xmldb_table('edw_pages_draft');
    $newtable = new xmldb_table('local_edwiserpagebuilder_pagesdraft');
    if ($dbman->table_exists($oldtable) || $dbman->table_exists($newtable)) {
        return;
    }

    $table = new xmldb_table('edw_pages_draft');

    $table->addField(new xmldb_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null));
    $table->addField(new xmldb_field('pagename', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null, 'id'));
    $table->addField(new xmldb_field('pagedesc', XMLDB_TYPE_TEXT, null, null, null, null, null, 'pagename'));
    $table->addField(new xmldb_field('capabilities', XMLDB_TYPE_TEXT, null, null, null, null, null, 'pagedesc'));
    $table->addField(new xmldb_field('pagecontent', XMLDB_TYPE_TEXT, null, null, null, null, null, 'capabilities'));
    $table->addField(new xmldb_field('deleted', XMLDB_TYPE_INTEGER, '1', null, null, null, '0', 'pagecontent'));
    $table->addField(new xmldb_field('pagelayout', XMLDB_TYPE_TEXT, null, null, null, null, null, 'deleted'));
    $table->addField(new xmldb_field('startdate', XMLDB_TYPE_INTEGER, '10', null, null, null, '0', 'pagelayout'));
    $table->addField(new xmldb_field('enddate', XMLDB_TYPE_INTEGER, '10', null, null, null, '0', 'startdate'));
    $table->addField(new xmldb_field('pagemodified', XMLDB_TYPE_INTEGER, '10', null, null, null, '0', 'enddate'));
    $table->addField(new xmldb_field('allowloginonly', XMLDB_TYPE_INTEGER, '1', null, null, null, '0', 'pagemodified'));
    $table->addField(new xmldb_field('visible', XMLDB_TYPE_INTEGER, '1', null, null, null, '0', 'allowloginonly'));
    $table->addField(new xmldb_field('seotag', XMLDB_TYPE_TEXT, null, null, null, null, null, 'visible'));
    $table->addField(new xmldb_field('seodesc', XMLDB_TYPE_TEXT, null, null, null, null, null, 'seotag'));
    $table->addField(new xmldb_field('allowindex', XMLDB_TYPE_INTEGER, '1', null, null, null, '0', 'seodesc'));
    $table->addField(new xmldb_field('refid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null, 'allowindex'));

    $table->addKey(new xmldb_key('primary', XMLDB_KEY_PRIMARY, ['id']));
    $table->addKey(new xmldb_key('fk_refid', XMLDB_KEY_FOREIGN, ['refid'], 'edw_pages', ['id']));

    if (!$dbman->table_exists($table)) {
        $dbman->create_table($table);
    }
}

/**
 * Create the taskslist table.
 *
 * @param xmldb_manager $dbman Database manager instance.
 */
function create_taskslist_table($dbman) {
    // Check both old and new names — if either exists, skip creation.
    $oldtable = new xmldb_table('edw_taskslist');
    $newtable = new xmldb_table('local_edwiserpagebuilder_taskslist');
    if ($dbman->table_exists($oldtable) || $dbman->table_exists($newtable)) {
        return;
    }

    $table = new xmldb_table('edw_taskslist');

    $table->addField(new xmldb_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null));
    $table->addField(new xmldb_field('subject', XMLDB_TYPE_CHAR, '500', null, XMLDB_NOTNULL, null, null));
    $table->addField(new xmldb_field('summary', XMLDB_TYPE_CHAR, '1000', null, null, null, null));
    $table->addField(new xmldb_field('createdby', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null));
    $table->addField(new xmldb_field('assignedto', XMLDB_TYPE_CHAR, '1000', null, null, null, null));
    $table->addField(new xmldb_field('completed', XMLDB_TYPE_INTEGER, '10', null, null, null, '0'));
    $table->addField(new xmldb_field('deleted', XMLDB_TYPE_INTEGER, '10', null, null, null, '0'));
    $table->addField(new xmldb_field('notify', XMLDB_TYPE_INTEGER, '10', null, null, null, '0'));
    $table->addField(new xmldb_field('visible', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null));
    $table->addField(new xmldb_field('timedue', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null));
    $table->addField(new xmldb_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null));
    $table->addField(new xmldb_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null));

    $table->addKey(new xmldb_key('id', XMLDB_KEY_PRIMARY, ['id']));

    if (!$dbman->table_exists($table)) {
        $dbman->create_table($table);
    }
}

/**
 * Move task data from remuiblck to edwiserpagebuilder.
 */
function move_data_from_remuiblck_to_edwiserpagebuilder() {
    global $DB;
    $dbman = $DB->get_manager();

    if ($dbman->table_exists('block_remuiblck_taskslist') && !get_config("local_edwiserpagebuilder", "remuiblck_data_moved")) {
        $olddata = $DB->get_records("block_remuiblck_taskslist");
        // Insert into whichever table currently exists (old or new name).
        $targettable = $dbman->table_exists(new xmldb_table('edw_taskslist'))
            ? 'edw_taskslist'
            : 'local_edwiserpagebuilder_taskslist';
        $DB->insert_records($targettable, $olddata);
    }
    set_config("remuiblck_data_moved", true, "local_edwiserpagebuilder");
}
