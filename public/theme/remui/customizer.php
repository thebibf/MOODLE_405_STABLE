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
 * Customizer page.
 *
 * @package   theme_remui
 * @copyright (c) 2022 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once("../../config.php");

require_login();

if (!is_siteadmin()) {
    throw new moodle_exception(get_string('noaccessright', 'theme_remui'));
}

$url = optional_param('url', $CFG->wwwroot, PARAM_RAW);
$PAGE->set_pagelayout('popup');
$PAGE->set_context(context_system::instance());
// Get additional URL parameters using optional_param for known parameters.
// Only preserve explicitly needed parameters to avoid security issues.
// If additional parameters are needed, add them here with optional_param().
$urlparams = [];
// Example: $urlparams['paramname'] = optional_param('paramname', null, PARAM_TEXT);
// Build URL with additional parameters if any.
if (!empty($urlparams)) {
    $urlobj = new moodle_url($url, $urlparams);
    $url = $urlobj->out(false);
}
$customizerurl = new moodle_url('/theme/remui/customizer.php', ['url' => $url]);
$PAGE->set_url($customizerurl);
$PAGE->set_title(get_string('customizer', 'theme_remui'));

$PAGE->requires->js_call_amd('theme_remui/customizer', 'init');

$PAGE->requires->css('/theme/remui/style/customizer.css');
$PAGE->requires->css('/theme/remui/style/devices.css');
$PAGE->requires->css('/theme/remui/style/color-picker.css');

$strings = get_string_manager()->load_component_strings('theme_remui', 'en');
$PAGE->requires->strings_for_js(array_keys($strings), 'theme_remui');
$PAGE->requires->strings_for_js([
    'success',
    'yes',
    'reset',
], 'moodle');

$customizer = theme_remui\customizer\customizer::instance();

$templatecontext = new stdClass();

$templatecontext->panels = $customizer->accordion();
$templatecontext->url = $url;

if (get_config('theme_remui', 'enablesiteloader')) {
    $templatecontext->loader = \theme_remui\utility::get_site_loader();
}
$templatecontext->sitename = format_string($SITE->shortname, true, ['context' => context_system::instance()]);

$templatecontext->darkmodecustomizerwarnnotvisible  = get_user_preferences('darkmodecustomizerwarnnotvisible');

$templatecontext->dmstatus = (new theme_remui_darkmodehandler())->get_status() !== "disable";

// Hide dashboard personalizer info banner when customizer page is accessed.
// This is safe because: (1) page requires admin access (checked above),
// (2) it's a notification dismissal, not a security-sensitive operation.
// No sesskey needed as this is automatic page initialization, not a user-triggered action.
set_config("dashboardpersonalizerinfo", "hide", 'theme_remui');



$PAGE->requires->js_call_amd('theme_remui/feedbackcollection', 'init', [false]);


echo $OUTPUT->header();
echo $OUTPUT->render_from_template('theme_remui/customizer/main', $templatecontext);
echo $OUTPUT->footer();
