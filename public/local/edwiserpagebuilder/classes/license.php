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
 * License management page
 *
 * @package   local_edwiserpagebuilder
 * @copyright Copyright (c) 2024 WisdmLabs. (http://www.wisdmlabs.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once('license_controller.php');

global $PAGE, $CFG, $OUTPUT, $DB;
admin_externalpage_setup('local_edwiserpagebuilder_licensestatus');

$systemcontext = context_system::instance();
$PAGE->set_context($systemcontext);
$PAGE->set_pagelayout('admin');
$title = get_string('licensestatus', 'local_edwiserpagebuilder');
$PAGE->set_title($title);
$PAGE->set_heading(get_string('licensestatus', 'local_edwiserpagebuilder'));
$PAGE->set_url($CFG->wwwroot . "/local/edwiserpagebuilder/classes/license.php");

if (!is_siteadmin()) {
    return false;
}

ob_start();
$pluginslug = 'edwiser-page-builder-for-moodle';
$lcontroller = new edwiserpagebuilder_license_controller();
$lcontroller->add_data();
$licensekey = $DB->get_field_select(
    'config_plugins',
    'value',
    'name = :name',
    ['name' => 'edd_' . $pluginslug . '_license_key'],
    IGNORE_MISSING
);

// Get License Status.
$lastaction = optional_param('lastaction', false, PARAM_ALPHA);
$status = $DB->get_field_select(
    'config_plugins',
    'value',
    'name = :name',
    ['name' => 'edd_' . $pluginslug . '_license_status'],
    IGNORE_MISSING
);

// Get renew link.
$renewlink = $DB->get_field_select(
    'config_plugins',
    'value',
    'name = :name',
    ['name' => 'wdm_' . $pluginslug . '_product_site'],
    IGNORE_MISSING
);

$cfglicensekey = get_config('local_edwiserpagebuilder', 'licensekey');
$licensekeyactivate = get_config('local_edwiserpagebuilder', 'licensekeyactivate');
$licensekeydeactivate = get_config('local_edwiserpagebuilder', 'licensekeydeactivate');

// Show proper reponse to user on license activation/deactivation.
if ($cfglicensekey == 'empty') {
    // If empty, show error message.
    echo '<div class="alert alert-danger">
       <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
       <h4><i class="icon fa fa-ban"></i> Error</h4>' .
       get_string("enterlicensekey", "local_edwiserpagebuilder") . '
    </div>';
}
if (
    $lastaction !== false && $lastaction == 'valid'
    && optional_param('licensekeyactivate', false, PARAM_ALPHA) != false
) {
    // Valid license key.
    echo '<div class="alert alert-success">
       <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
       <h4><i class="icon fa fa-check"></i> Success</h4>' .
       get_string("licensekeyactivated", "local_edwiserpagebuilder") . '
    </div>';
} else if ($lastaction !== false && $lastaction == 'expired') {
    // Expired license key.
    echo '<div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h4><i class="icon fa fa-ban"></i> Alert!</h4>' .
    get_string("licensekeyhasexpired", "local_edwiserpagebuilder") . '
</div>';
} else if ($lastaction !== false && $lastaction == 'disabled') {
    // Disabled license key.
        echo '<div class="alert alert-danger">
           <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
           <h4><i class="icon fa fa-ban"></i> Alert!</h4>' .
           get_string("licensekeyisdisabled", "local_edwiserpagebuilder") . '
        </div>';
} else if ($lastaction == 'invalid') {
    // Invalid license key.
    echo '<div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h4><i class="icon fa fa-ban"></i> Alert!</h4>' .
    get_string("entervalidlicensekey", "local_edwiserpagebuilder") . '
</div>';
} else if ($lastaction == 'noactivationsleft') {
    // Maximum limit reached.
    echo '<div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h4><i class="icon fa fa-ban"></i> Alert!</h4>' .
    get_string("nolicenselimitleft", "local_edwiserpagebuilder") . '
</div>';
} else if ($lastaction == 'site_inactive') {
    // Site is inactive.
    echo '<div class="alert alert-danger">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <h4><i class="icon fa fa-ban"></i> Alert!</h4>' .
          get_string("siteinactive", "local_edwiserpagebuilder") . '
        </div>';
} else if ($lastaction == 'deactivated') {
    // Site is inactive.
        echo '<div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h4><i class="icon fa fa-ban"></i> Alert!</h4>' .
    get_string("licensekeydeactivated", "local_edwiserpagebuilder") . '
    </div>';
} else if (
    $lastaction == 'no_response'
    || (optional_param('licensekeydeactivate', false, PARAM_ALPHA) != false && $lastaction == 'valid')
) {
    // Site is inactive.
    echo '<div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-ban"></i> Alert!</h4>' .
        get_string("noresponsereceived", "local_edwiserpagebuilder") . '
        </div>';
}

// Remove config vars.
unset_config('licensekey', 'local_edwiserpagebuilder');
unset_config('licensekeyactivate', 'local_edwiserpagebuilder');
unset_config('licensekeydeactivate', 'local_edwiserpagebuilder');

$sesskeyhidden = sesskey();
$activationtitle = get_string('edwiserpagebuilderlicenseactivation', 'local_edwiserpagebuilder');
$licensekeystr = get_string('licensekey', 'local_edwiserpagebuilder');

// Check if RemUI Pro license is active.
require_once($CFG->dirroot . '/local/edwiserpagebuilder/lib.php');
$remuiproactive = false;
if (check_plugin_available('theme_remui')) {
    if (!defined('PLUGINNAME') && file_exists($CFG->dirroot . '/theme/remui/classes/controller/LicenseController.php')) {
        require_once($CFG->dirroot . '/theme/remui/classes/controller/LicenseController.php');
    }
    if (defined('PLUGINNAME') && PLUGINNAME === 'Edwiser RemUI') {
        $remuistatus = get_config('theme_remui', 'edd_remui_license_status');
        if ($remuistatus == 'valid') {
            $remuiproactive = true;
        }
    }
}
$disabledattr = $remuiproactive ? 'disabled' : '';

echo '<form method="POST">';
echo '<div class="license-box box box-warning">';
echo "<input type=\"hidden\" name=\"sesskey\" value=\"{$sesskeyhidden}\">";
echo '<div class="box-header with-border">';
echo "<h3 class=\"box-title\">{$activationtitle}</h3>";
echo '</div>';

if ($remuiproactive) {
    echo '<div class="alert alert-warning mt-2">';
    echo '<i class="fa fa-info-circle mr-1"></i>';
    echo get_string('remuiprolicense', 'local_edwiserpagebuilder');
    echo '</div>';
}

echo '<div class="panel-body">';
echo '<input type="hidden" name="activetab" value="local_edwiserform_license_status">';

if ($status == "valid") {
    echo '<div class="form-group has-success">';
    echo "<label class=\"control-label text-black col-sm-3\">{$licensekeystr}:</label>";
    echo '<div class="col-sm-9">';
    echo "<input id='edd_{$pluginslug}_license_key' class='form-control' name='edd_{$pluginslug}_license_key'
        type='text' class='regular-text' value='{$licensekey}' placeholder='Enter license key...' readonly/>";
    echo '</div></div>';
} else if ($status == "expired") {
    echo '<div class="form-group has-error">';
    echo "<label class=\"control-label text-black col-sm-3\">{$licensekeystr}:</label>";
    echo '<div class="col-sm-9">';
    echo "<input id='edd_{$pluginslug}_license_key' class='form-control' name='edd_{$pluginslug}_license_key'
        type='text' class='regular-text' value='{$licensekey}' placeholder='Enter license key...' readonly/>";
    echo '</div></div>';
} else {
    echo '<div class="form-group has-error">';
    echo "<label class=\"control-label text-black col-sm-3\">{$licensekeystr}:</label>";
    echo '<div class="col-sm-9">';
    echo "<input id='edd_{$pluginslug}_license_key' class='form-control' name='edd_{$pluginslug}_license_key'
        type='text' class='regular-text' value='{$licensekey}' placeholder='Enter license key...' {$disabledattr}/>";
    echo '</div></div>';
}

echo '<div class="form-group">';
echo '<label class="control-label col-sm-3">' .
    get_string('licensestatus', 'local_edwiserpagebuilder') .
    ':</label>';
echo '<div class="col-sm-9">';
$statustextactive = get_string('active', 'local_edwiserpagebuilder');
$statustextactivetext = "<p style='color:green;'>{$statustextactive}</p>";
$statustextinactive = get_string('notactive', 'local_edwiserpagebuilder');
$statustextinactivetext = "<p style='color:red;'>{$statustextinactive}</p>";
$statustextexpired = get_string('expired', 'local_edwiserpagebuilder');
$statustextexpiredtext = "<p style='color:red;'>{$statustextexpired}</p>";
$statustextlimitexceeded = get_string('no_activations_left', 'local_edwiserpagebuilder');
$statustextlimitexceededtext = "<p style='color:red;'>{$statustextlimitexceeded}</p>";
if ($remuiproactive || ($status !== false && $status == 'valid')) {
    echo $statustextactivetext;
} else if ($status == 'site_inactive') {
    echo $statustextinactivetext;
} else if ($status == 'expired') {
    echo $statustextexpiredtext;
} else if ($status == 'invalid') {
    echo $statustextinactivetext;
} else if ($status == 'no_activations_left') {
    echo $statustextlimitexceededtext;
} else {
    echo $statustextinactivetext;
}
echo '</div>';
echo '</div>';

echo '<div class="form-group">';
$activatelicensetext = get_string('activatelicense', 'local_edwiserpagebuilder');
$deactivatelicensetext = get_string('deactivatelicense', 'local_edwiserpagebuilder');
$renewlicensetext = get_string('renewlicense', 'local_edwiserpagebuilder');
echo '<div class="col-sm-9">';
// Hidden field to check if on license tab.
echo "<input type='hidden' id='onRapidGraderLicensePage' name='onRapidGraderLicensePage' value='1'/>";
if ($status !== false && $status == 'valid') {
    echo "<input type='submit' class='btn btn-primary text-white'
        style='color:white;' name='edd_{$pluginslug}_license_deactivate' value='{$deactivatelicensetext}'/>";
} else if ($status == 'expired') {
    echo "<input type='submit' class='btn btn-primary'
        style='color:white;' name='edd_{$pluginslug}_license_deactivate' value='{$deactivatelicensetext}'/>&nbsp&nbsp";
    echo '<input type="button" class="btn btn-primary"
        style="color:white;" name="edd_' . $pluginslug . '_license_renew"
        value="' . $renewlicensetext . '" onclick="window.open(\'' . $renewlink . '\');">';
} else {
    echo "<input type='submit' class='btn btn-primary'
        style='color:white;' name='edd_{$pluginslug}_license_activate'
        value='{$activatelicensetext}' {$disabledattr}/>";
}
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';
echo '</form>';

$out = ob_get_clean();
$onlicensepage = optional_param('onRapidGraderLicensePage', false, PARAM_BOOL);
if ($onlicensepage) {
    $url = new moodle_url('/local/edwiserpagebuilder/classes/license.php', [
        'lastaction' => $status,
        'licensekeyactivate' => $licensekeyactivate,
        'licensekeydeactivate' => $licensekeydeactivate,
    ]);
    redirect($url->out());
}
echo $OUTPUT->header();
echo $out;
echo $OUTPUT->footer();
