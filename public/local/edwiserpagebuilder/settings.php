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
 * Plugin settings for Edwiser Page Builder.
 *
 * @package   local_edwiserpagebuilder
 * @copyright (c) 2023 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author Gourav G
 */

defined('MOODLE_INTERNAL') || die();

// Define the component name.
$componentname = 'local_edwiserpagebuilder';
$capabilities = [
    'local/edwiserpagebuilder:epb_can_manage_page',
];

if ($hassiteconfig || has_any_capability($capabilities, \context_system::instance())) {
    if (!$hassiteconfig && has_any_capability($capabilities, \context_system::instance())) {
        if (!$ADMIN->locate('localplugins')) {
            $ADMIN->add('modules', new admin_category('localplugins', new lang_string('localplugins')));
        }
    }
    $ADMIN->add('localplugins', new admin_category($componentname, new lang_string('pluginname', $componentname)));

    $ADMIN->add(
        $componentname,
        new \admin_externalpage(
            'epbmanagepages',
            get_string('managepages', $componentname),
            new moodle_url('/local/edwiserpagebuilder/managepages.php'),
            ['local/edwiserpagebuilder:epb_can_manage_page']
        )
    );

    $ADMIN->add(
        $componentname,
        new \admin_externalpage(
            'epbcreatepage',
            get_string('addnewcustompage', $componentname),
            new \moodle_url('/local/edwiserpagebuilder/pageedit.php'),
            ['local/edwiserpagebuilder:epb_can_manage_page']
        )
    );

    // Add license page to admin menu.
    $ADMIN->add(
        $componentname,
        new \admin_externalpage(
            'local_edwiserpagebuilder_licensestatus',
            get_string('licensestatus', $componentname),
            new \moodle_url('/local/edwiserpagebuilder/classes/license.php'),
            ['moodle/site:config']
        )
    );
}
