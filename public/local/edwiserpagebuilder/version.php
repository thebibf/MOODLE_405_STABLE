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
 * Version information for Edwiser Page Builder.
 *
 * @package   local_edwiserpagebuilder
 * @copyright (c) 2023 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author    Gourav Govande
 */

defined('MOODLE_INTERNAL') || die();

$plugin->version   = 2026042200;      // The current module version (Date: YYYYMMDDXX).
$plugin->requires  = 2022041900;      // Requires this Moodle version.
$plugin->component = 'local_edwiserpagebuilder'; // Full name of the plugin (used for diagnostics).
// Cron interval is not required.
$plugin->maturity  = MATURITY_STABLE;
$plugin->release   = '4.2.26';
