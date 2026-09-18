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
 * Theme settings
 * @package   theme_remui
 * @copyright (c) 2022 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $PAGE, $OUTPUT, $CFG;

// License activation and deactivation handling.
if (optional_param('section', '', PARAM_TEXT) == 'themesettingremui') {
    // Handle license status change on form submit.
    $licensecontroller = new theme_remui\controller\LicenseController();
    $licensedata = $licensecontroller->serve_license_data();
    if ($licensedata) {
        set_config('edd_remui_setup_license_data', json_encode($licensedata), 'theme_remui');
    }

    // Form is submitted with changed settings. Do not want to execute when modifying a block.
    $data = data_submitted();
    if (isset($data->action) && $data->action == 'save-settings') {
        if (isset($data->s_theme_remui_announcementtext)) {
            $cfganouncetext = get_config('theme_remui', 'announcementtext');
            $formanouncetext = $data->s_theme_remui_announcementtext;

            $cfgdismisannounce = get_config('theme_remui', 'enabledismissannouncement');
            $formdismisannounce = $data->s_theme_remui_enabledismissannouncement;

            $cfgannouncementtype = get_config('theme_remui', 'announcementtype');
            $formannouncementtype = $data->s_theme_remui_announcementtype;

            if (
                $cfganouncetext !== $formanouncetext
                || $cfgdismisannounce !== $formdismisannounce
                || $cfgannouncementtype !== $formannouncementtype
            ) {
                \theme_remui\utility::remove_announcement_preferences();
            }
        }
    }
}

if (optional_param('action', '', PARAM_TEXT) == 'save-settings') {
    set_config('activetab', optional_param('activetab', 'theme_remui_general', PARAM_TEXT), 'theme_remui');
}

$versioning = "";
if ($PAGE->theme->name == 'remui') {
    $versioning = new lang_string(
        'versionforheading',
        'theme_remui',
        get_theme_release_info()
    );
}
$remuisettings = [];
require_once($CFG->dirroot . '/theme/remui/lib.php');
if ($ADMIN->fulltree) {
    $settings = new theme_remui_admin_settingspage_tabs(
        'themesettingremui',
        new lang_string('configtitle', 'theme_remui') . $versioning
    );

    $page = new admin_settingpage('theme_remui_general', new lang_string('generalsettings', 'theme_remui'));

    $setupwizard = new \theme_remui\setupwizard();
    $setupstatus = $setupwizard->get_setup_status();

    if ($setupstatus != 'finished' && (get_config('theme_remui', 'setupinstallcheck'))) {
        // Setupwizard setting.
        $page->add(new admin_setting_heading(
            'theme_remui_setupwizard',
            new lang_string('setupwizardsettingpagehead', 'theme_remui'),
            format_text(new lang_string('setupwizardsettingpagedesc', 'theme_remui'), FORMAT_MARKDOWN)
        ));

        $name = 'theme_remui/setupwizard';
        $title = new lang_string('setupwizard', 'theme_remui');
        $description = new lang_string('setupwizarddesc', 'theme_remui', ['wwwroot' => $CFG->wwwroot]);
        $default = false;
        $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
    }

    // Top navigation menu.
    $page->add(new admin_setting_heading(
        'theme_remui_topnavigationmenu',
        new lang_string('topnavigationmenuheading', 'theme_remui'),
        format_text(new lang_string('topnavigationmenuheadingdesc', 'theme_remui'), FORMAT_MARKDOWN)
    ));

    $name = 'theme_remui/enablesignup';
    $title = new lang_string('enablesignup', 'theme_remui');
    $description = get_string('enablesignupdesc', 'theme_remui');
    if ($CFG->registerauth != 'email') {
        $authurl = (new moodle_url('/admin/settings.php', ['section' => 'manageauths']))->out(false);
        $description = format_text(
            $description . "\n" . get_string('enablesignupdescnotice', 'theme_remui', $authurl),
            FORMAT_HTML
        );
    }
    $default = false;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // General Page site announcement Settings.
    $page->add(new admin_setting_heading(
        'theme_remui_general',
        new lang_string('siteannouncementheading', 'theme_remui'),
        format_text(new lang_string('siteannouncementheadingdesc', 'theme_remui'), FORMAT_MARKDOWN)
    ));

    $name = 'theme_remui/enableannouncement';
    $title = new lang_string('enableannouncement', 'theme_remui');
    $description = new lang_string('enableannouncementdesc', 'theme_remui');
    $default = false;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $remuisettings['enableannouncement'] = [[
        'value'  => true,
        'show' => ['announcementtext', 'enabledismissannouncement', 'announcementtype'],
    ], [
        'value'  => false,
        'hide' => ['announcementtext', 'enabledismissannouncement', 'announcementtype'],
    ]];
    // Announcment text.
    $name = 'theme_remui/announcementtext';
    $title = new lang_string('announcementtext', 'theme_remui');
    $description = new lang_string('announcementtextdesc', 'theme_remui');
    $default = '';
    $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);


    $name = 'theme_remui/enabledismissannouncement';
    $title = new lang_string('enabledismissannouncement', 'theme_remui');
    $description = new lang_string('enabledismissannouncementdesc', 'theme_remui');
    $default = false;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Testimonials data for about us section.
    $name = 'theme_remui/announcementtype';
    $title = new lang_string('announcementtype', 'theme_remui');
    $description = new lang_string('announcementtypedesc', 'theme_remui');
    $setting = new admin_setting_configselect(
        $name,
        $title,
        $description,
        'success',
        [
            'info'    => new lang_string('typeinfo', 'theme_remui'),
            'success' => new lang_string('typesuccess', 'theme_remui'),
            'warning' => new lang_string('typewarning', 'theme_remui'),
            'danger'  => new lang_string('typedanger', 'theme_remui'),
        ]
    );
    $page->add($setting);

    // General Page site announcement Settings.
    $page->add(new admin_setting_heading(
        'theme_remui_seo',
        new lang_string('seosettingsheading', 'theme_remui'),
        format_text(new lang_string('seosettingsheadingdesc', 'theme_remui'), FORMAT_MARKDOWN)
    ));
    // Custom favicon temp.
    $name = 'theme_remui/faviconurl';
    $title = new lang_string('favicon', 'theme_remui');
    $description = new lang_string('favicondesc', 'theme_remui');
    $setting = new admin_setting_configstoredfile(
        $name,
        $title,
        $description,
        'faviconurl',
        0,
        ['subdirs' => 0, 'accepted_types' => 'web_image']
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_remui/logoorsitename';
    $title = new lang_string('logoorsitename', 'theme_remui');
    $description = new lang_string('logoorsitenamedesc', 'theme_remui');
    $default = 'iconsitename';
    $setting = new admin_setting_configselect(
        $name,
        $title,
        $description,
        $default,
        [
            'logo' => new lang_string('onlylogo', 'theme_remui'),
            'logomini' => new lang_string('logomini', 'theme_remui'),
            'icononly' => new lang_string('icononly', 'theme_remui'),
            'iconsitename' => new lang_string('iconsitename', 'theme_remui'),
        ]
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $remuisettings['logoorsitename'] = [
        [
            'value'  => 'logo',
            'show' => ['logo', 'darkmodelogo'],
            'hide' => ['logomini', 'siteicon', 'darkmodelogomini'],
        ], [
            'value'  => 'logomini',
            'show' => ['logomini', 'darkmodelogomini'],
            'hide' => ['logo', 'siteicon', 'darkmodelogo'],
        ], [
            'value'  => 'icononly',
            'show' => ['siteicon'],
            'hide' => ['logo', 'logomini', 'darkmodelogo', 'darkmodelogomini'],
        ], [
            'value'  => 'iconsitename',
            'show' => ['siteicon'],
            'hide' => ['logo', 'logomini', 'darkmodelogo', 'darkmodelogomini'],
        ],
    ];

    // Logo file setting.
    $name = 'theme_remui/logo';
    $title = new lang_string('logo', 'theme_remui');
    $description = new lang_string('logodesc', 'theme_remui');
    $setting = new admin_setting_configstoredfile(
        $name,
        $title,
        $description,
        'logo',
        0,
        ['subdirs' => 0, 'accepted_types' => 'web_image']
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Logo file setting in dark mode.
    $name = 'theme_remui/darkmodelogo';
    $title = new lang_string('darkmodelogo', 'theme_remui');
    $description = new lang_string('darkmodelogodesc', 'theme_remui');
    $setting = new admin_setting_configstoredfile(
        $name,
        $title,
        $description,
        'darkmodelogo',
        0,
        ['subdirs' => 0, 'accepted_types' => 'web_image']
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // LogoMini file setting.
    $name = 'theme_remui/logomini';
    $title = new lang_string('logomini', 'theme_remui');
    $description = new lang_string('logominidesc', 'theme_remui');
    $setting = new admin_setting_configstoredfile(
        $name,
        $title,
        $description,
        'logomini',
        0,
        ['subdirs' => 0, 'accepted_types' => 'web_image']
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);


    // LogoMini file setting for dark mode.
    $name = 'theme_remui/darkmodelogomini';
    $title = new lang_string('darkmodelogomini', 'theme_remui');
    $description = new lang_string('darkmodelogominidesc', 'theme_remui');
    $setting = new admin_setting_configstoredfile(
        $name,
        $title,
        $description,
        'darkmodelogomini',
        0,
        ['subdirs' => 0, 'accepted_types' => 'web_image']
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Site icon setting.
    $name = 'theme_remui/siteicon';
    $title = new lang_string('siteicon', 'theme_remui');
    $description = new lang_string('siteicondesc', 'theme_remui');
    $default = 'graduation-cap';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $page->add($setting);

    $name = 'theme_remui/sitenamecolor';
    $title = new lang_string('sitenamecolor', 'theme_remui');
    $description = new lang_string('sitenamecolordesc', 'theme_remui');
    $default = '#0051f9';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Google analytics block.
    $name = 'theme_remui/googleanalytics';
    $title = new lang_string('googleanalytics', 'theme_remui');
    $description = new lang_string('googleanalyticsdesc', 'theme_remui');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $page->add(new admin_setting_heading(
        'theme_remui_customization',
        new lang_string('sitecustomizationhead', 'theme_remui'),
        format_text(new lang_string('sitecustomizationheaddesc', 'theme_remui'), FORMAT_MARKDOWN)
    ));

    // Font Selector.
    $name = 'theme_remui/fontselect';
    $title = new lang_string('fontselect', 'theme_remui');
    $description = new lang_string('fontselectdesc', 'theme_remui');
    $default = 1;
    $choices = [
        1 => new lang_string('fonttypestandard', 'theme_remui'),
        2 => new lang_string('fonttypegoogle', 'theme_remui'),
    ];
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $remuisettings['fontselect'] = [[
        'value'  => '1',
        'hide' => ['fontname'],
    ], [
        'value'  => '2',
        'show' => ['fontname'],
    ]];


    // Heading font name.
    $name = 'theme_remui/fontname';
    $title = new lang_string('fontname', 'theme_remui');
    $description = new lang_string('fontnamedesc', 'theme_remui');
    $default = 'Inter';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Page layout width setting.
    $name = 'theme_remui/pagewidth';
    $title = new lang_string('pagewidth', 'theme_remui');
    $description = new lang_string('pagewidthdesc', 'theme_remui');
    $setting = new admin_setting_configselect(
        $name,
        $title,
        $description,
        'success',
        [
            'default'   => new lang_string('defaultpermoodle', 'theme_remui'),
            'fullwidth' => new lang_string('fullwidthlayout', 'theme_remui'),
        ]
    );

    $page->add($setting);

    // Custom CSS file.
    $name = 'theme_remui/customcss';
    $title = new lang_string('customcss', 'theme_remui');
    $description = new lang_string('customcssdesc', 'theme_remui');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('remui_clear_cache');
    $page->add($setting);

    // Enable/Disable site loader.
    $name = 'theme_remui/enablesiteloader';
    $title = new lang_string('enablesiteloader', 'theme_remui');
    $description = new lang_string('enablesiteloaderdesc', 'theme_remui');
    $default = true;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Site loader image.
    $name = 'theme_remui/loaderimage';
    $title = new lang_string('loaderimagehead', 'theme_remui');
    $description = new lang_string('loaderimagedesc', 'theme_remui');
    $setting = new admin_setting_configstoredfile(
        $name,
        $title,
        $description,
        'loaderimage',
        0,
        ['subdirs' => 0, 'accepted_types' => 'web_image']
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    $remuisettings['enablesiteloader'] = [[
        'value'  => true,
        'show' => ['loaderimage'],
    ], [
        'value'  => false,
        'hide' => ['loaderimage'],
    ]];

    // Dark mode setting heading.
    $page->add(new admin_setting_heading(
        'theme_remui_darkmodesettings',
        new lang_string('darkmodesettingshead', 'theme_remui'),
        format_text(new lang_string('darkmodesettingsheaddesc', 'theme_remui'), FORMAT_MARKDOWN)
    ));

    $name = 'theme_remui/enabledarkmode';
    $title = new lang_string('enabledarkmode', 'theme_remui');
    $description = new lang_string('enabledarkmodedesc', 'theme_remui');
    $default = 'allowonallpages';
    $setting = new admin_setting_configselect(
        $name,
        $title,
        $description,
        $default,
        [
            'disable' => new lang_string('dmoption_disable', 'theme_remui'),
            'allowonallpages' => new lang_string('dmoption_allowonallpages', 'theme_remui'),
            'excludepages' => new lang_string('dmoption_excludepages', 'theme_remui'),
            'includepages' => new lang_string('dmoption_includepages', 'theme_remui'),
        ]
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_remui/darkmodeincludepages';
    $title = new lang_string('darkmodeincludepages', 'theme_remui');
    $description = new lang_string('darkmodeincludepagesdesc', 'theme_remui');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_remui/darkmodeexcludepages';
    $title = new lang_string('darkmodeexcludepages', 'theme_remui');
    $description = new lang_string('darkmodeexcludepagesdesc', 'theme_remui');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $remuisettings['enabledarkmode'] = [
        [
            'value'  => 'disable',
            'show' => [],
            'hide' => ['darkmodeincludepages', 'darkmodeexcludepages'],
        ], [
            'value'  => 'allowonallpages',
            'show' => [],
            'hide' => ['darkmodeincludepages', 'darkmodeexcludepages'],
        ], [
            'value'  => 'excludepages',
            'show' => ['darkmodeexcludepages'],
            'hide' => ['darkmodeincludepages'],
        ], [
            'value'  => 'includepages',
            'show' => ['darkmodeincludepages'],
            'hide' => ['darkmodeexcludepages'],
        ],
    ];

    // Accessibility widgets settings.
    $page->add(new admin_setting_heading(
        'theme_remui_accessbilityfeatures',
        new lang_string('accessbilityfeatureshead', 'theme_remui'),
        format_text(new lang_string('accessbilityfeaturesheaddesc', 'theme_remui'), FORMAT_MARKDOWN)
    ));

    // Dictionary.
    $name = 'theme_remui/enableaccessibilitytools';
    $title = new lang_string('enableaccessibilitytools', 'theme_remui');
    $description = new lang_string('enableaccessibilitytoolsdesc', 'theme_remui');
    $default = true;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    // Advance features settings.
    $page->add(new admin_setting_heading(
        'theme_remui_advancefetures',
        new lang_string('advancefeatureshead', 'theme_remui'),
        format_text(new lang_string('advancefeaturesheaddesc', 'theme_remui'), FORMAT_MARKDOWN)
    ));

    // Dictionary.
    $name = 'theme_remui/enabledictionary';
    $title = new lang_string('enabledictionary', 'theme_remui');
    $description = new lang_string('enabledictionarydesc', 'theme_remui');
    $default = true;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Usage tracking GDPR setting.
    $name = 'theme_remui/enableusagetracking';
    $title = get_string('enableusagetracking', 'theme_remui');
    $description = get_string('enableusagetrackingdesc', 'theme_remui');
    $default = true;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Edwiser Feedback GDPR setting.
    $name = 'theme_remui/enableedwfeedback';
    $title = new lang_string('enableedwfeedback', 'theme_remui');
    $description = new lang_string('enableedwfeedbackdesc', 'theme_remui');
    $default = true;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_remui/enablequickmenu';
    $title = new lang_string('enablequickmenu', 'theme_remui');
    $description = new lang_string('enablequickmenudesc', 'theme_remui');
    $default = true;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);


    // Must add the page after definiting all the settings!
    $settings->add($page);

    // Dashboard settings.
    $page = new admin_settingpage('theme_remui_dashboard', new lang_string('dashboardsetting', 'theme_remui'));

    $page->add(new admin_setting_heading(
        'theme_remui_dashboard',
        new lang_string('dashboardpage', 'theme_remui'),
        format_text(new lang_string('dashboardsettingdesc', 'theme_remui'), FORMAT_MARKDOWN)
    ));

    // Setting for enabling course stats on dashbaard page.
    $name = 'theme_remui/enabledashboardcoursestats';
    $title = new lang_string('enabledashboardcoursestats', 'theme_remui');
    $description = new lang_string('enabledashboardcoursestatsdesc', 'theme_remui');
    $default = true;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Remui block settings.
    $pluginman = core_plugin_manager::instance();
    if (array_key_exists("remuiblck", $pluginman->get_installed_plugins('block'))) {
        if (class_exists('block_remuiblck_settings')) {
            \block_remuiblck_settings::add_settings($settings, $page);
        }
    }
    $settings->add($page);
    // Homepage settings.
    $page = new admin_settingpage('theme_remui_frontpage', new lang_string('homepagesettings', 'theme_remui'));

    $pluginman = core_plugin_manager::instance();
    $activehomepage = get_config('theme_remui', 'frontpagechooser');

    $edwpagebuilderavailable = is_plugin_available('local_edwiserpagebuilder');
    if ($edwpagebuilderavailable) {
        $options = [
            0 => new lang_string('frontpagedesignold', 'theme_remui'),
            3 => new lang_string('homepageedwpagebuilderoption', 'theme_remui'),
        ];
        $page->add(new admin_setting_heading(
            'theme_remui_frontpagedesign',
            new lang_string('frontpagedesign', 'theme_remui'),
            format_text(new lang_string('frontpagedesigndesc', 'theme_remui'), FORMAT_MARKDOWN)
        ));
        $name = 'theme_remui/frontpagechooser';
        $title = new lang_string('frontpagechooser', 'theme_remui');
        $description = new lang_string('frontpagechooserdesc', 'theme_remui');
        $setting = new admin_setting_configselect(
            $name,
            $title,
            $description,
            0,
            $options
        );
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
    } else {
        set_config('frontpagechooser', 0, 'theme_remui');
    }
    if ($activehomepage == 0) {
        if (class_exists('admin_setting_heading')) {
            $page->add(new admin_setting_heading(
                'theme_remui_upsection',
                new lang_string('frontpageimagecontent', 'theme_remui'),
                format_text(new lang_string('frontpageimagecontentdesc', 'theme_remui'), FORMAT_MARKDOWN)
            ));
        }
        $name = 'theme_remui/frontpageimagecontent';
        $title = new lang_string('frontpageimagecontentstyle', 'theme_remui');
        $description = new lang_string('frontpageimagecontentstyledesc', 'theme_remui');
        $setting = new admin_setting_configselect(
            $name,
            $title,
            $description,
            1,
            [
                0 => new lang_string('staticcontent', 'theme_remui'),
                1 => new lang_string('slidercontent', 'theme_remui'),
            ]
        );
        $page->add($setting);

        $name = 'theme_remui/contenttype';
        $title = new lang_string('contenttype', 'theme_remui');
        $description = new lang_string('contentdesc', 'theme_remui');
        $setting = new admin_setting_configselect(
            $name,
            $title,
            $description,
            0,
            [
                0 => new lang_string('videourl', 'theme_remui'),
                1 => new lang_string('image', 'theme_remui'),
            ]
        );
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $name = 'theme_remui/video';
        $title = new lang_string('video', 'theme_remui');
        $description = new lang_string('videodesc', 'theme_remui');
        $default = 'https://www.youtube.com/embed/wop3FMhoLGs';
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $name = 'theme_remui/addtext';
        $title = new lang_string('addtext', 'theme_remui');
        $description = new lang_string('addtextdesc', 'theme_remui');
        $default = new lang_string('defaultaddtext', 'theme_remui');
        $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $name = 'theme_remui/staticimage';
        $title = new lang_string('uploadimage', 'theme_remui');
        $description = new lang_string('uploadimagedesc', 'theme_remui');
        $setting = new admin_setting_configstoredfile($name, $title, $description, 'staticimage', 0, [
            'subdirs' => 0, 'accepted_types' => 'web_image',
        ]);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $name = 'theme_remui/slideinterval';
        $title = new lang_string('slideinterval', 'theme_remui');
        $description = new lang_string('slideintervaldesc', 'theme_remui');
        $default = 5000;
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $name = 'theme_remui/sliderautoplay';
        $title = new lang_string('sliderautoplay', 'theme_remui');
        $description = new lang_string('sliderautoplaydesc', 'theme_remui');
        $setting = new admin_setting_configselect(
            $name,
            $title,
            $description,
            1,
            [
                1 => new lang_string('true', 'theme_remui'),
                2 => new lang_string('false', 'theme_remui'),
            ]
        );
        $page->add($setting);

        $name = 'theme_remui/slidercount';
        $title = new lang_string('slidercount', 'theme_remui');
        $description = new lang_string('slidercountdesc', 'theme_remui');
        $setting = new admin_setting_configselect(
            $name,
            $title,
            $description,
            1,
            [
                1 => new lang_string('one', 'theme_remui'),
                2 => new lang_string('two', 'theme_remui'),
                3 => new lang_string('three', 'theme_remui'),
                4 => new lang_string('four', 'theme_remui'),
                5 => new lang_string('five', 'theme_remui'),
            ]
        );
        $page->add($setting);
        $remuisettings['slidercount'] = [];
        for ($slidecounts = 1; $slidecounts <= 5; $slidecounts = $slidecounts + 1) {
            $slidervisibility = [
                'value' => $slidecounts,
                'show' => [],
                'hide' => [],
            ];
            for ($i = 1; $i <= $slidecounts; $i++) {
                $slidervisibility['show'][] = 'slideimage' . $i;
                $slidervisibility['show'][] = 'slidertext' . $i;
                $slidervisibility['show'][] = 'sliderbuttontext' . $i;
                $slidervisibility['show'][] = 'sliderurl' . $i;
            }
            for ($i = $slidecounts + 1; $i <= 5; $i++) {
                $slidervisibility['hide'][] = 'slideimage' . $i;
                $slidervisibility['hide'][] = 'slidertext' . $i;
                $slidervisibility['hide'][] = 'sliderbuttontext' . $i;
                $slidervisibility['hide'][] = 'sliderurl' . $i;
            }
            $remuisettings['slidercount'][] = $slidervisibility;
            $name = 'theme_remui/slideimage' . $slidecounts;
            $title = new lang_string('slideimage', 'theme_remui');

            $description = new lang_string('slideimagedesc', 'theme_remui');
            $setting = new admin_setting_configstoredfile($name, $title, $description, 'slideimage' . $slidecounts, 0, [
                'subdirs' => 0, 'accepted_types' => 'web_image',
            ]);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $page->add($setting);

            $name = 'theme_remui/slidertext' . $slidecounts;
            $title = new lang_string('slidertext', 'theme_remui');
            $description = new lang_string('slidertextdesc', 'theme_remui');
            $default = new lang_string('defaultslidertext', 'theme_remui');
            $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $page->add($setting);

            $name = 'theme_remui/sliderbuttontext' . $slidecounts;
            $title = new lang_string('sliderbuttontext', 'theme_remui');
            $description = new lang_string('sliderbuttontextdesc', 'theme_remui');
            $default = '';
            $setting = new admin_setting_configtext($name, $title, $description, $default);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $page->add($setting);

            $name = 'theme_remui/sliderurl' . $slidecounts;
            $title = new lang_string('sliderurl', 'theme_remui');
            $description = new lang_string('sliderurldesc', 'theme_remui');
            $default = '';
            $setting = new admin_setting_configtext($name, $title, $description, $default);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $page->add($setting);
        }
        $remuisettings['contenttype'] = [[
            'value' => 0,
            'show' => ['video'],
            'hide' => ['addtext', 'staticimage'],
        ], [
            'value' => 1,
            'show' => ['addtext', 'staticimage'],
            'hide' => ['video'],
        ]];
        $remuisettings['frontpageimagecontent'] = [[
            'value' => 0,
            'show' => ['contenttype'],
            'hide' => [
                'slideinterval',
                'sliderautoplay',
                'slidercount',
            ],
        ], [
            'value' => 1,
            'show' => [
                'slideinterval',
                'sliderautoplay',
                'slidercount',
            ],
            'hide' => ['contenttype'],
        ]];
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
        // Marketing blocks.
        $page->add(new admin_setting_heading(
            'theme_remui_blocksection',
            new lang_string('frontpageblocks', 'theme_remui'),
            format_text(new lang_string('frontpageblocksdesc', 'theme_remui'), FORMAT_MARKDOWN)
        ));

        // Show the About Us on Home Page Setting.
        $name = 'theme_remui/frontpageblockdisplay';
        $title = new lang_string('frontpageblockdisplay', 'theme_remui');
        $description = new lang_string('frontpageblockdisplaydesc', 'theme_remui');
        $setting = new admin_setting_configselect(
            $name,
            $title,
            $description,
            1,
            [
                1 => new lang_string('donotshowaboutus', 'theme_remui'),
                2 => new lang_string('showaboutusinrow', 'theme_remui'),
                3 => new lang_string('showaboutusingridblock', 'theme_remui'),
            ]
        );


        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Marketing spot section heading.
        $name = 'theme_remui/frontpageblockheading';
        $title = new lang_string('frontpageaboutus', 'theme_remui');
        $description = new lang_string('frontpageaboutustitledesc', 'theme_remui');
        $default = 'About Us';
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
        // Description for above.
        $name = 'theme_remui/frontpageblockdesc';
        $title = new lang_string('frontpageaboutusbody', 'theme_remui');
        $description = new lang_string('frontpageaboutusbodydesc', 'theme_remui');
        $default = 'Holisticly harness just in time technologies via corporate scenarios.';
        $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $name = 'theme_remui/enablesectionbutton';
        $title = new lang_string('enablesectionbutton', 'theme_remui');
        $description = new lang_string('enablesectionbuttondesc', 'theme_remui');
        $default = false;
        $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $remuisettings['frontpageblockdisplay'] = [];
        $blockicons = ['flag', 'globe', 'cog', 'users'];
        $targets = [
            'frontpageblockheading',
            'frontpageblockdesc',
            'enablesectionbutton',
        ];
        for ($blockcount = 1; $blockcount <= 4; $blockcount++) {
            $targets = array_merge($targets, [
                'frontpageblocksection' . $blockcount,
                'sectionbuttontext' . $blockcount,
                'frontpageblockdescriptionsection' . $blockcount,
                'frontpageblockiconsection' . $blockcount,
                'frontpageblockimage' . $blockcount,
            ]);

            /*block section*/
            $name = 'theme_remui/frontpageblocksection' . $blockcount;
            $title = new lang_string('frontpageblocksection' . $blockcount, 'theme_remui');
            $description = new lang_string('frontpageblocksectiondesc', 'theme_remui');
            $default = 'LOREM IPSUM';
            $setting = new admin_setting_configtext($name, $title, $description, $default);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $page->add($setting);

            $name = 'theme_remui/frontpageblockdescriptionsection' . $blockcount;
            $title = new lang_string('frontpageblockdescriptionsection' . $blockcount, 'theme_remui');
            $description = new lang_string('frontpageblockdescriptionsectiondesc', 'theme_remui');
            $default = new lang_string('defaultdescriptionsection', 'theme_remui');
            $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $page->add($setting);

            $name = 'theme_remui/frontpageblockiconsection' . $blockcount;
            $title = new lang_string('frontpageblockiconsection' . $blockcount, 'theme_remui');
            $description = new lang_string('frontpageblockiconsectiondesc', 'theme_remui');
            $default = $blockicons[$blockcount - 1];
            $setting = new admin_setting_configtext($name, $title, $description, $default);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $page->add($setting);

            $name = 'theme_remui/sectionbuttontext' . $blockcount;
            $title = new lang_string('sectionbuttontext' . $blockcount, 'theme_remui');
            $description = new lang_string('sectionbuttontextdesc', 'theme_remui');
            $default = 'Read More';
            $setting = new admin_setting_configtext($name, $title, $description, $default);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $page->add($setting);

            $name = 'theme_remui/sectionbuttonlink' . $blockcount;
            $title = new lang_string('sectionbuttonlink' . $blockcount, 'theme_remui');
            $description = new lang_string('sectionbuttonlinkdesc', 'theme_remui');
            $default = '#';
            $setting = new admin_setting_configtext($name, $title, $description, $default);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $page->add($setting);

            $name = 'theme_remui/frontpageblockimage' . $blockcount;
            $title = new lang_string('frontpageblockimage', 'theme_remui');
            $description = new lang_string('frontpageblockimagedesc', 'theme_remui');
            $setting = new admin_setting_configstoredfile(
                $name,
                $title,
                $description,
                'frontpageblockimage' . $blockcount,
                0,
                ['subdirs' => 0, 'accepted_types' => 'web_image']
            );
            $setting->set_updatedcallback('theme_reset_all_caches');
            $page->add($setting);
        }
        $visibility = [
            'value' => 1,
            'hide' => $targets,
        ];
        $remuisettings['frontpageblockdisplay'][] = $visibility;
        $visibility['value'] = 2;
        $visibility['show'] = $visibility['hide'];
        unset($visibility['hide']);
        $remuisettings['frontpageblockdisplay'][] = $visibility;
        $visibility['value'] = 3;
        $remuisettings['frontpageblockdisplay'][] = $visibility;

        $remuisettings['enablesectionbutton'] = [[
            'value' => true,
            'show' => [
                'sectionbuttonlink1',
                'sectionbuttonlink2',
                'sectionbuttonlink3',
                'sectionbuttonlink4',
            ],
        ], [
            'value' => false,
            'hide' => [
                'sectionbuttonlink1',
                'sectionbuttonlink2',
                'sectionbuttonlink3',
                'sectionbuttonlink4',
            ],
        ]];

        // Frontpage Aboutus settings.
        $page->add(new admin_setting_heading(
            'theme_remui_frontpage_aboutus',
            new lang_string('frontpagetestimonial', 'theme_remui'),
            format_text(new lang_string('frontpagetestimonialdesc', 'theme_remui'), FORMAT_MARKDOWN)
        ));


        $name = 'theme_remui/enablefrontpageaboutus';
        $title = new lang_string('enablefrontpageaboutus', 'theme_remui');
        $description = new lang_string('enablefrontpageaboutusdesc', 'theme_remui');
        $default = false;
        $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Heading text for about us.
        $name = 'theme_remui/frontpageaboutusheading';
        $title = new lang_string('frontpageaboutusheading', 'theme_remui');
        $description = new lang_string('frontpageaboutusheadingdesc', 'theme_remui');
        $default = "Testimonial Title";
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Text for about us.
        $name = 'theme_remui/frontpageaboutustext';
        $title = new lang_string('frontpageaboutustext', 'theme_remui');
        $description = new lang_string('frontpageaboutustextdesc', 'theme_remui');
        $default = new lang_string('frontpageaboutusdefault', 'theme_remui');
        ;
        $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        // Testimonials data for about us section.
        $name = 'theme_remui/testimonialcount';
        $title = new lang_string('testimonialcount', 'theme_remui');
        $description = new lang_string('testimonialcountdesc', 'theme_remui');
        $setting = new admin_setting_configselect(
            $name,
            $title,
            $description,
            1,
            [
                1 => new lang_string('one', 'theme_remui'),
                2 => new lang_string('two', 'theme_remui'),
                3 => new lang_string('three', 'theme_remui'),
            ]
        );
        $page->add($setting);

        $remuisettings['testimonialcount'] = [];
        for ($testimonialcount = 1; $testimonialcount <= 3; $testimonialcount++) {
            $testivisibility = [
                'value' => $testimonialcount,
                'show' => [],
                'hide' => [],
            ];
            for ($i = 1; $i <= $testimonialcount; $i++) {
                $testivisibility['show'][] = 'testimonialimage' . $i;
                $testivisibility['show'][] = 'testimonialname' . $i;
                $testivisibility['show'][] = 'testimonialdesignation' . $i;
                $testivisibility['show'][] = 'testimonialtext' . $i;
            }
            for (; $i <= 3; $i++) {
                $testivisibility['hide'][] = 'testimonialimage' . $i;
                $testivisibility['hide'][] = 'testimonialname' . $i;
                $testivisibility['hide'][] = 'testimonialdesignation' . $i;
                $testivisibility['hide'][] = 'testimonialtext' . $i;
            }
            $remuisettings['testimonialcount'][] = $testivisibility;
            // Image.
            $name = 'theme_remui/testimonialimage' . $testimonialcount;
            $title = new lang_string('testimonialimage', 'theme_remui');
            $description = new lang_string('testimonialimagedesc', 'theme_remui');
            $setting = new admin_setting_configstoredfile(
                $name,
                $title,
                $description,
                'testimonialimage' . $testimonialcount,
                0,
                ['subdirs' => 0, 'accepted_types' => 'web_image']
            );
            $setting->set_updatedcallback('theme_reset_all_caches');
            $page->add($setting);

            // Name.
            $name = 'theme_remui/testimonialname' . $testimonialcount;
            $title = new lang_string('testimonialname', 'theme_remui');
            $description = new lang_string('testimonialnamedesc', 'theme_remui');
            $default = '';
            $setting = new admin_setting_configtext($name, $title, $description, $default);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $page->add($setting);

            // Post.
            $name = 'theme_remui/testimonialdesignation' . $testimonialcount;
            $title = new lang_string('testimonialdesignation', 'theme_remui');
            $description = new lang_string('testimonialdesignationdesc', 'theme_remui');
            $default = '';
            $setting = new admin_setting_configtext($name, $title, $description, $default);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $page->add($setting);

            // Description.
            $name = 'theme_remui/testimonialtext' . $testimonialcount;
            $title = new lang_string('testimonialtext', 'theme_remui');
            $description = new lang_string('testimonialtextdesc', 'theme_remui');
            $default = '';
            $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $page->add($setting);
        }
        $remuisettings['enablefrontpageaboutus'] = [[
            'value' => true,
            'show' => [
                'frontpageaboutusheading',
                'frontpageaboutustext',
                'testimonialcount',
            ],
        ], [
            'value' => false,
            'hide' => [
                'frontpageaboutusheading',
                'frontpageaboutustext',
                'testimonialcount',
            ],
        ]];
    }
    if ($activehomepage != 1) {
        $page->add(new admin_setting_heading(
            'theme_remui_frontpage_transparentheader',
            new lang_string('transparentheaderheader', 'theme_remui'),
            format_text(new lang_string('transparentheaderheaderdesc', 'theme_remui'), FORMAT_MARKDOWN)
        ));
        $defaultvalue = false;
        $name = 'theme_remui/homepagetransparentheader';
        $title = new lang_string('homepagetransparentheadertitle', 'theme_remui');
        $description = new lang_string('homepagetransparentheaderdesc', 'theme_remui');
        $default = $defaultvalue;
        $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);

        $name = 'theme_remui/frontpageheadercolor';
        $title = new lang_string('frontpageheadercolortitle', 'theme_remui');
        $description = new lang_string('frontpageheadercolordesc', 'theme_remui');
        $default = '#616e84';
        $previewconfig = null;
        $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);
    }


    $remuisettings['homepagetransparentheader'] = [[
        'value'  => true,
        'show' => ['frontpageheadercolor'],
    ], [
        'value'  => false,
        'hide' => ['frontpageheadercolor'],
    ]];

    $page->add(new admin_setting_heading(
        'theme_remui_frontpage_hidehomepageelement',
        new lang_string('hidehomepageelement', 'theme_remui'),
        format_text(new lang_string('hidehomepageelementdesc', 'theme_remui'), FORMAT_MARKDOWN)
    ));

    $name = 'theme_remui/hideheadercontent';
    $title = new lang_string('hideheadercontenttitle', 'theme_remui');
    $description = new lang_string('hideheadercontentdesc', 'theme_remui');
    $default = false;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_remui/hideactivitysection';
    $title = new lang_string('hideactivitysectiontitle', 'theme_remui');
    $description = new lang_string('hideactivitysectiondesc', 'theme_remui');
    $default = false;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    $settings->add($page);

    // Course Page Settings.
    $page = new admin_settingpage('theme_remui_course', new lang_string('coursesettings', 'theme_remui'));

    $page->add(new admin_setting_heading(
        'theme_remui_course_info_control',
        new lang_string('courseinfocontrolhead', 'theme_remui'),
        format_text(new lang_string('courseinfocontroldesc', 'theme_remui'), FORMAT_MARKDOWN)
    ));

    $name = 'theme_remui/coursedatevisibility';
    $title = new lang_string('coursedatevisibilityhead', 'theme_remui');
    $description = new lang_string('coursedatevisibilitydesc', 'theme_remui');
    $setting = new admin_setting_configselect(
        $name,
        $title,
        $description,
        "showupdatedate",
        [
            "hidedate" => new lang_string('hidedate', 'theme_remui'),
            "showstartdate" => new lang_string('showstartdate', 'theme_remui'),
            "showupdatedate" => new lang_string('showupdatedate', 'theme_remui'),
            "showstartwhenend" => new lang_string('showstartwhenend', 'theme_remui'),
        ]
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_remui/enrolleduserscountvisibility';
    $title = new lang_string('enrolleduserscountvisibilityhead', 'theme_remui');
    $description = new lang_string('enrolleduserscountvisibilitydesc', 'theme_remui');
    $default = true;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_remui/showenrolledtextinput';
    $title = new lang_string('showenrolledtextinputhead', 'theme_remui');
    $description = new lang_string('showenrolledtextinputdesc', 'theme_remui');
    $default = new lang_string('showenrolledtextinputdefaulttext', 'theme_remui');
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $remuisettings['enrolleduserscountvisibility'] = [[
        'value' => 0,
        'hide' => ['showenrolledtextinput'],
    ], [
        'value' => 1,
        'show' => ['showenrolledtextinput'],
    ]];

    $name = 'theme_remui/lessonsvisiblityoncoursecard';
    $title = new lang_string('lessonsvisiblityoncoursecardhead', 'theme_remui');
    $description = new lang_string('lessonsvisiblityoncoursecarddesc', 'theme_remui');
    $default = true;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);


    $name = 'theme_remui/showlessontextinput';
    $title = new lang_string('showlessontextinputhead', 'theme_remui');
    $description = new lang_string('showlessontextinputdesc', 'theme_remui');
    $default = new lang_string('showlessontextinputdefaulttext', 'theme_remui');
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $remuisettings['lessonsvisiblityoncoursecard'] = [[
        'value' => 0,
        'hide' => ['showlessontextinput'],
    ], [
        'value' => 1,
        'show' => ['showlessontextinput'],
    ]];
    // Enrolment Page settings.
    $page->add(new admin_setting_heading(
        'theme_remui_coursepage',
        new lang_string('coursepagesettings', 'theme_remui'),
        format_text(new lang_string('coursepagesettingsdesc', 'theme_remui'), FORMAT_MARKDOWN)
    ));



    // Setting for enabling course stats visibility in course page.
    $name = 'theme_remui/enablecoursestats';
    $title = new lang_string('enablecoursestats', 'theme_remui');
    $description = new lang_string('enablecoursestatsdesc', 'theme_remui');
    $default = true;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_remui/focusmode';
    $title = new lang_string('focusmode', 'theme_remui');
    $description = new lang_string('focusmodedesc', 'theme_remui');
    $default = 1; // Default to 'Focus mode on'.
    $options = [
        1 => new lang_string('focusmodeon', 'theme_remui'),
        0 => new lang_string('focusmodeoff', 'theme_remui'),
        2 => new lang_string('forcefocusmode', 'theme_remui'),
    ];
    $setting = new admin_setting_configselect($name, $title, $description, $default, $options);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);



    // Setting for next and previous button in activity.
    $name = 'theme_remui/activitynextpreviousbutton';
    $title = new lang_string('activitynextpreviousbutton', 'theme_remui');
    $description = new lang_string('activitynextpreviousbuttondesc', 'theme_remui');
    $setting = new admin_setting_configselect(
        $name,
        $title,
        $description,
        1,
        [
            0 => new lang_string('disablenextprevious', 'theme_remui'),
            1 => new lang_string('enablenextprevious', 'theme_remui'),
            2 => new lang_string('enablenextpreviouswithname', 'theme_remui'),
        ]
    );
    $page->add($setting);

    // Course per page to shown.
    $name = 'theme_remui/courseheaderdesign';
    $title = new lang_string('courseheaderdesign', 'theme_remui');
    $description = new lang_string('courseheaderdesigndesc', 'theme_remui');
    $setting = new admin_setting_configselect(
        $name,
        $title,
        $description,
        1,
        [
            0 => new lang_string('default', 'theme_remui'),
            1 => new lang_string('headerdesign', 'theme_remui', 1),
        ]
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $remuisettings['courseheaderdesign'] = [[
        'value' => 0,
        'hide' => ['headeroverlayopacity'],
    ], [
        'value' => 1,
        'show' => ['headeroverlayopacity'],
    ]];
    $name = 'theme_remui/headeroverlayopacity';
    $title = new lang_string('headeroverlayopacityhead', 'theme_remui');
    $description = new lang_string('headeroverlayopacitydesc', 'theme_remui');
    $default = '100';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $page->add(new admin_setting_heading(
        'theme_remui_course_card_settings',
        new lang_string('coursecardsettingshead', 'theme_remui'),
        format_text(new lang_string('coursecardsettingsdesc', 'theme_remui'), FORMAT_MARKDOWN)
    ));

        // Course per page to shown.
        $name = 'theme_remui/courseperpage';
        $title = new lang_string('courseperpage', 'theme_remui');
        $description = new lang_string('courseperpagedesc', 'theme_remui');
        $setting = new admin_setting_configselect(
            $name,
            $title,
            $description,
            12,
            [
                12 => new lang_string('twelve', 'theme_remui'),
                9 => new lang_string('nine', 'theme_remui'),
                6 => new lang_string('six', 'theme_remui'),
            ]
        );
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);


        // Course animation to be shown on Archieve page.
        $name = 'theme_remui/courseanimation';
        $title = new lang_string('courseanimation', 'theme_remui');
        $description = new lang_string('courseanimationdesc', 'theme_remui');
        $setting = new admin_setting_configselect(
            $name,
            $title,
            $description,
            'none',
            [
                'none' => new lang_string('none', 'theme_remui'),
                'fade' => new lang_string('fade', 'theme_remui'),
                'slide-top' => new lang_string('slide-top', 'theme_remui'),
                'slide-bottom'  => new lang_string('slide-bottom', 'theme_remui'),
                'slide-right'   => new lang_string('slide-right', 'theme_remui'),
                'scale-up'      => new lang_string('scale-up', 'theme_remui'),
                'scale-down'    => new lang_string('scale-down', 'theme_remui'),
            ]
        );
        $setting->set_updatedcallback('theme_reset_all_caches');
        $page->add($setting);


    // Enrolment Page settings.
    $page->add(new admin_setting_heading(
        'theme_remui_enrolpage',
        new lang_string('enrolpagesettings', 'theme_remui'),
        format_text(new lang_string('enrolpagesettingsdesc', 'theme_remui'), FORMAT_MARKDOWN)
    ));

    $remuisettings['enrolment_page_layout'] = [[
        'value' => 0,
        'hide' => ['showcoursepricing'],
    ], [
        'value' => 1,
        'show' => ['showcoursepricing'],
    ]];

    $remuisettings['showcoursepricing'] = [[
        'value' => 0,
        'hide' => ['enrolment_payment'],
    ], [
        'value' => 1,
        'show' => ['enrolment_payment'],
    ]];

    // Full Page background Settings.
    $setting = new admin_setting_configselect(
        'theme_remui/enrolment_page_layout',
        new lang_string('enrolment_layout', 'theme_remui'),
        new lang_string('enrolment_layout_desc', 'theme_remui'),
        0,
        [
            '0' => new lang_string('defaultlayout', 'theme_remui'),
            '1' => new lang_string('enable_layout1', 'theme_remui'),
        ]
    );
    $page->add($setting);

    // Full Page background Settings.
    $name = 'theme_remui/showcoursepricing';
    $title = new lang_string('showcoursepricing', 'theme_remui');
    $description = new lang_string('showcoursepricingdesc', 'theme_remui');
    $default = true;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Course Enrolment Settings.
    $setting = new admin_setting_configselect(
        'theme_remui/enrolment_payment',
        new lang_string('enrolment_payment', 'theme_remui'),
        new lang_string('enrolment_payment_desc', 'theme_remui'),
        0,
        [
            '0' => new lang_string('allrequirepayment', 'theme_remui'),
            '1' => new lang_string('somearefree', 'theme_remui'),
        ]
    );
    $page->add($setting);

    // Show Related Courses.
    $name = 'theme_remui/showrelatedcourse';
    $title = new lang_string('showrelatedcourse', 'theme_remui');
    $description = new lang_string('showrelatedcoursedesc', 'theme_remui');
    $default = true;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);


    // Show Latest Courses.
    $name = 'theme_remui/showlatestcourse';
    $title = new lang_string('showlatestcourse', 'theme_remui');
    $description = new lang_string('showlatestcoursedesc', 'theme_remui');
    $default = true;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // No of latest Courses.
    $name = 'theme_remui/latestcoursecount';
    $title = new lang_string('latestcoursecount', 'theme_remui');
    $description = new lang_string('latestcoursecountdesc', 'theme_remui');
    $setting = new admin_setting_configselect(
        $name,
        $title,
        $description,
        12,
        [
            '4' => new lang_string('four', 'theme_remui'),
            '8' => new lang_string('eight', 'theme_remui'),
            '12' => new lang_string('twelve', 'theme_remui'),
            '16' => new lang_string('sixteen', 'theme_remui'),
            '20' => new lang_string('twenty', 'theme_remui'),
        ]
    );
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    $settings->add($page);

    $remuisettings['showlatestcourse'] = [
            [
            'value' => false,
            'hide' => ['latestcoursecount'],
            ],
            [
            'value' => true,
            'show' => ['latestcoursecount'],
            ],
        ];

    // Footer Settings (commented out for future use).

    // Login Page Settings.
    $page = new admin_settingpage('theme_remui_login', new lang_string('loginsettings', 'theme_remui'));
    $page->add(new admin_setting_heading(
        'theme_remui_login',
        new lang_string('loginsettings', 'theme_remui'),
        format_text(new lang_string('loginpagesettings', 'theme_remui'), FORMAT_MARKDOWN)
    ));

    $name = 'theme_remui/navlogin_popup';
    $title = new lang_string('navlogin_popup', 'theme_remui');
    $description = new lang_string('navlogin_popupdesc', 'theme_remui');
    $default = true;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_remui/loginpanellogo';
    $title = new lang_string('loginpanellogo', 'theme_remui');
    $description = new lang_string('loginpanellogodesc', 'theme_remui');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'loginpanellogo', 0, [
        'subdirs' => 0, 'accepted_types' => 'web_image',
    ]);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_remui/loginsettingpic';
    $title = new lang_string('loginsettingpic', 'theme_remui');
    $description = new lang_string('loginsettingpicdesc', 'theme_remui');
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'loginsettingpic', 0, [
        'subdirs' => 0, 'accepted_types' => 'web_image',
    ]);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Brand Logo Position Setting.
    $name = 'theme_remui/brandlogopos';
    $title = new lang_string('brandlogopos', 'theme_remui');
    $description = new lang_string('brandlogoposdesc', 'theme_remui');
    $default = true;
    $setting = new admin_setting_configselect(
        $name,
        $title,
        $description,
        1,
        [
            0 => new lang_string('hiddenlogo', 'theme_remui'),
            1 => new lang_string('sidebarregionlogo', 'theme_remui'),
            2 => new lang_string('maincontentregionlogo', 'theme_remui'),
        ]
    );

    $page->add($setting);

    // Login Page Layout.
    $name = 'theme_remui/loginpagelayout';
    $title = new lang_string('loginpagelayout', 'theme_remui');
    $description = new lang_string('loginpagelayoutdesc', 'theme_remui');
    $setting = new admin_setting_configselect(
        $name,
        $title,
        $description,
        'loginright',
        [
            'logincenter' => new lang_string('logincenter', 'theme_remui'),
            'loginleft' => new lang_string('loginleft', 'theme_remui'),
            'loginright' => new lang_string('loginright', 'theme_remui'),
        ]
    );
    $page->add($setting);

    // Text with Brand Logo.
    $name = 'theme_remui/brandlogotext';
    $title = new lang_string('brandlogotext', 'theme_remui');
    $description = new lang_string('brandlogotextdesc', 'theme_remui');
    $default = ""; // Default string will be Unhide.
    $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_remui/signuptextcolor';
    $title = new lang_string('signuptextcolor', 'theme_remui');
    $description = new lang_string('signuptextcolordesc', 'theme_remui');
    $default = '#4C5A73';
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    $settings->add($page);

    $remuisettings['loginpagelayout'] = [[
        'value'  => 'logincenter',
        'show' => [],
        'hide' => ['brandlogotext', 'signuptextcolor'],
    ], [
        'value'  => 'loginleft',
        'show' => ['brandlogopos', 'brandlogotext', 'signuptextcolor'],
        'hide' => [],
    ], [
        'value'  => 'loginright',
        'show' => ['brandlogopos', 'brandlogotext', 'signuptextcolor'],
        'hide' => [],
    ]];
}
global $PAGE;
if (optional_param('section', '', PARAM_TEXT) == 'themesettingremui') {
    $PAGE->requires->data_for_js('remuisettings', $remuisettings);
    $PAGE->requires->js(new moodle_url('/theme/remui/settings.js'));
    $PAGE->requires->js_call_amd('theme_remui/settings', 'init');
    $PAGE->requires->js_call_amd('theme_remui/validatejson', 'init');
    $PAGE->requires->js_call_amd('theme_remui/redirectsettingshandler', 'init');
}

$showmodal = false;
if (get_config("theme_remui", "setupinstallcheck") == "showmodal") {
    $showmodal = true;
}
$PAGE->requires->js_call_amd('theme_remui/setupwizard', 'registerModalEvents', [$showmodal]);
