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
 * Library functions for Edwiser Page Builder.
 *
 * @package   local_edwiserpagebuilder
 * @copyright (c) 2022 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author    Gourav Govande
 */
defined('MOODLE_INTERNAL') || die();

define('COURSE_MANAGE_PIE_COLORS', [
    'enrolleduserscolor' => "#E4EAEC",
    'studentcompletedcolor' => "#008C4D",
    'inprogresscolor' => "#0B69E3",
    'yettostartcolor' => "#F57D1B",
]);
global $CFG;

if ($CFG->branch < '404') {

    /**
     * Add meta tags before standard HTML head for published pages.
     *
     * @return string HTML meta tags.
     */
    function local_edwiserpagebuilder_before_standard_html_head() {
        global $PAGE;

        $output = '';

        if (strpos($PAGE->bodyclasses, "epb-publish-") !== false) {
            $pageid = optional_param('id', 0, PARAM_INT);

            if ($pageid) {
                $ph = new \local_edwiserpagebuilder\custom_page_handler("publish", $pageid);

                $output .= '<meta name="description" content="' . $ph->page->get_pagedesc() . '">';

                if (!$ph->page->get_allowindex()) {
                    $output .= '<meta name="robots" content="noindex"/>';
                }

                if ($keywords = $ph->page->get_seotag()) {
                    $output .= '<meta name="' . $keywords . '" content="' . $ph->page->get_seodesc() . '">';
                }
            }
        }
        return $output;
    }
}

/**
 * Extend navigation for Edwiser Page Builder.
 *
 * @param global_navigation $nav The global navigation object.
 */
function local_edwiserpagebuilder_extend_navigation(global_navigation $nav) {

    global $PAGE, $OUTPUT;

    // Check if current theme is remui OR if remui is a parent theme.
    $isremuitheme = ($PAGE->theme->name === 'remui');
    $hasremuiparent = in_array('remui', $PAGE->theme->parents);
    $isremuiorchild = ($isremuitheme || $hasremuiparent);

    if (
        !$isremuiorchild && (isset($PAGE->theme->addblockposition) &&
        $PAGE->user_is_editing() &&
        $PAGE->user_can_edit_blocks() &&
        $PAGE->pagelayout !== 'mycourses'
        )
    ) {
        $regionsid = [
            "content" => '#block-region-content',
            "side-pre" => '#block-region-side-pre',
            "side-top" => '#region-top-blocks',
            "side-bottom" => '#region-bottom-blocks',
            "full-width-top" => '#region-fullwidthtop-blocks',
            "full-bottom" => '#region-fullwidthbottom-blocks',
        ];
        $sortingarray = ['full-width-top', 'side-top', 'content', 'side-bottom', 'full-bottom', 'side-pre'];

        $addblockmodalcontext = [
            'editing' => $PAGE->user_is_editing(),
            'regiondata' => [],
        ];
        $regionsarray = $PAGE->blocks->get_regions();

        usort($regionsarray, function ($a, $b) use ($sortingarray) {
            $indexa = array_search($a, $sortingarray);
            $indexb = array_search($b, $sortingarray);
            return $indexa - $indexb;
        });

        foreach ($regionsarray as $region) {
            if (empty($OUTPUT->addblockbutton($region))) {
                continue;
            }
            $singleregiondata = [
                'region' => $region,
                'regionname' => isset($regionsid[$region])
                    ? get_string($region, 'local_edwiserpagebuilder')
                    : ($PAGE->theme->name === 'adaptable'
                        ? get_string('region-' . $region, 'theme_adaptable')
                        : $region),
                'regionid' => isset($regionsid[$region]) ? $regionsid[$region] : "#{$region}",
                'regionaddblockbutton' => get_addblock_btn_for_region($region),
                'pageurl' => $PAGE->url,
            ];
            $addblockmodalcontext['regiondata'][] = $singleregiondata;
        }

        $PAGE->requires->data_for_js('blocksectiondata', $addblockmodalcontext['regiondata']);
        $PAGE->requires->data_for_js('addblockmodalcontext', json_encode($addblockmodalcontext));

        // It handles the visibility of secondary navigation in edwiserpagebuilder add block modal.
        $PAGE->requires->data_for_js('currentpagesubtype', $PAGE->subpage);
        $PAGE->requires->data_for_js('edwremuithemeinfo', 'available');

        // Add the required JS modules.
        $PAGE->requires->js_call_amd('local_edwiserpagebuilder/addblockmodal', 'init', [
            $PAGE->pagetype,
            $PAGE->pagelayout,
            null,
            $PAGE->subpage,
            (is_siteadmin() && is_pagebuilder_req_plugin_available('block_edwiseradvancedblock')),
            is_pagebuilder_req_plugin_available('filter_edwiserpbf'),
            filter_get_active_state('edwiserpbf') != 1,
        ]);

        $PAGE->requires->js_call_amd('local_edwiserpagebuilder/addblockaddedlistners', 'init');
        $PAGE->requires->js_call_amd('local_edwiserpagebuilder/edwaddblockbutton', 'init');
    }

    // Add custom JavaScript for plugin display customization on admin pages.
    if (
        (strpos($PAGE->url->out(), '/admin/plugins.php') !== false ||
        strpos($PAGE->url->out(), '/admin/localplugins.php') !== false) && !is_epb_pro_allowed()
    ) {
        $PAGE->requires->js_call_amd('local_edwiserpagebuilder/plugin-customization', 'addPluginUpgradeButton');
    }
}

/**
 * Get the add block button HTML for a specific region.
 *
 * @param string $region The block region name.
 * @return string The rendered add block button HTML.
 */
function get_addblock_btn_for_region($region) {
    global $OUTPUT, $PAGE;

    $params = ['bui_addblock' => '', 'sesskey' => sesskey()];
    if (!empty($region)) {
        $params['bui_blockregion'] = $region;
    }
    $url = new moodle_url($PAGE->url, $params);
    $addblockbutton = $OUTPUT->render_from_template(
        'core/add_block_button',
        context: [
            'link' => $url->out(false),
            'escapedlink' => "?{$url->get_query_string(false)}",
            'pagehash' => $PAGE->get_edited_page_hash(),
            'blockregion' => $region,
            // The following parameters are not used since Moodle 4.2 but are
            // still passed for backward-compatibility.
            'pageType' => $PAGE->pagetype,
            'pageLayout' => $PAGE->pagelayout,
            'subPage' => $PAGE->subpage,
        ]
    );

    return $addblockbutton;
}


/**
 * Serves the files from the edwiserform file areas
 * @param stdClass $course the course object
 * @param stdClass $cm the course module object
 * @param stdClass $context the edwiserform's context
 * @param string $filearea the name of the file area
 * @param array $args extra arguments (itemid, path)
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 * @since Edwiser Form 1.0.0
 */
function local_edwiserpagebuilder_pluginfile(
    $course,
    $cm,
    $context,
    $filearea,
    array $args,
    $forcedownload = 0,
    array $options = []
) {
    if ($context->contextlevel != CONTEXT_SYSTEM) {
        send_file_not_found();
    }
    $itemid = (int)array_shift($args);
    $relativepath = implode('/', $args);
    $fullpath = "/{$context->id}/local_edwiserpagebuilder/$filearea/$itemid/$relativepath";

    $fs = get_file_storage();
    if (!($file = $fs->get_file_by_hash(sha1($fullpath)))) {
        return false;
    }
    // Download MUST be forced - security!
    send_stored_file($file, 0, 0, $forcedownload, $options);
}

/**
 * Get the block content editor URL.
 *
 * @return string The block content URL.
 */
function get_block_content_url() {
    global $CFG;
    $blkid = isset($_GET['bui_edit']) ? ( '?bui_edit=' . $_GET['bui_edit'] ) : '';
    $blkurl = $CFG->wwwroot . '/blocks/edwiseradvancedblock/editor.php' . $blkid;
    return $blkurl;
}

/**
 * Get the block content return URL.
 *
 * @return string The return URL.
 */
function get_block_content_return_url() {
    return isset($_GET['returl']) ? $_GET['returl'] : '';
}

/**
 * Get the block ID from the request.
 *
 * @return string The block ID.
 */
function get_block_id() {
    return (isset($_GET['bui_edit']) ? $_GET['bui_edit'] : '');
}

/**
 * Add the customizer button on each block.
 *
 * @param int $instanceid The block instance ID.
 * @return string The customizer button HTML.
 */
function local_edwiserpagebuilder_customizer_button($instanceid) {
    global $PAGE, $CFG, $DB, $OUTPUT;
    if (!$PAGE->user_is_editing()) {
        return "";
    }

    if (!check_user_admin_cap($userobject = null)) {
        return "";
    }

    // Check if current theme is remui OR if remui is a parent theme.
    $isremuitheme = ($PAGE->theme->name === 'remui');
    $hasremuiparent = in_array('remui', $PAGE->theme->parents);
    $isremuiorchild = ($isremuitheme || $hasremuiparent);

    $url = $CFG->wwwroot . "/local/edwiserpagebuilder/editor.php?bui_edit=" . $instanceid;
    $url .= "&returl=" . urlencode($PAGE->url);

    $customizerbutton = "<div class='d-flex justify-content-end live-customizer-btn'>";

    // New button for export.
    $buttonclass = is_epb_pro_allowed()
        ? 'block_exporter_btn'
        : 'export-block-btn-locked';

    $customizerbutton  .= "<a class='btn btn-secondary mr-2 {$buttonclass}' data-blockid='{$instanceid}' href='#' role='button'>";
    $customizerbutton .= "<i class='fa fa-upload'></i> <span>Export block</span>";

    if (!is_epb_pro_allowed()) {
        $imgurl = $CFG->wwwroot . "/local/edwiserpagebuilder/pix/lock-fill-icon.svg";
        $customizerbutton .= "<img src='{$imgurl}' class='lock-icon' alt='locked'>";
    }

    $customizerbutton .= "</a>";

    // AI prompt button (hidden by default; JS filterAiButtons() shows it only on html/custom blocks).
    if (
        \local_edwiserpagebuilder\ai\ai_block_generator::is_ai_available()
    ) {
        $providerconfigured = \local_edwiserpagebuilder\ai\ai_block_generator::is_ai_provider_configured() ? '1' : '0';
        $aisettingsurl = $CFG->wwwroot . '/admin/settings.php?section=aiprovider';
        $customizerbutton .= "<button class='btn mr-2 epb-ai-prompt-btn d-none' "
            . "data-action='open-ai-prompt' data-blockid='{$instanceid}' "
            . "data-provider-configured='{$providerconfigured}' "
            . "data-ai-settings-url='{$aisettingsurl}' type='button'>"
            . "<i class='fa fa-magic'></i> "
            . get_string('ai_prompt_button', 'local_edwiserpagebuilder')
            . "</button>";
    }

    // Live customizer button.
    $customizerbutton .= "<a class='btn btn-primary' href='" . $url . "' role='button'>";
    $customizerbutton .= "<i class='fa fa-pencil'></i> " . get_string("livecustomizer", "local_edwiserpagebuilder") . "</a>";
    $customizerbutton .= "</div>";

    // Load AI Block Creator JS module when AI is available.
    if (
        \local_edwiserpagebuilder\ai\ai_block_generator::is_ai_available()
    ) {
        $PAGE->requires->js_call_amd(
            'local_edwiserpagebuilder/ai_block_creator',
            'init',
            [\context_system::instance()->id]
        );
    }

    return $customizerbutton;
}

/**
 * Get the EPB license status.
 *
 * @return string|false The license status or false.
 */
function get_epb_license_status() {
    global $DB;
    // Check license status.
    $licensestatus = $DB->get_field_select(
        'config_plugins',
        'value',
        'name = :name',
        ['name' => 'edd_edwiser-page-builder-for-moodle_license_status'],
        IGNORE_MISSING
    );
    return $licensestatus;
}

/**
 * Check if EPB pro features are allowed.
 *
 * @return bool True if pro is allowed.
 */
function is_epb_pro_allowed() {
    global $CFG;
    $licensestatus = get_config('local_edwiserpagebuilder', 'edd_edwiser-page-builder-for-moodle_license_status');
    if ($licensestatus == 'valid') {
        return true;
    }

    if (check_plugin_available('theme_remui')) {
        if (!defined('PLUGINNAME') && file_exists($CFG->dirroot . '/theme/remui/classes/controller/LicenseController.php')) {
            require_once($CFG->dirroot . '/theme/remui/classes/controller/LicenseController.php');
        }
        if (defined('PLUGINNAME') && PLUGINNAME === 'Edwiser RemUI') {
            $remuistatus = get_config('theme_remui', 'edd_remui_license_status');
            if ($remuistatus == 'valid') {
                return true;
            }
        }
    }

    return false;
}

/**
 * Resolve the license payload for proxy requests.
 * Returns an array with keys: license, item_name, current_version.
 * Uses static caching per request.
 *
 * @return array
 */
function local_edwiserpagebuilder_get_proxy_license_payload() {
    global $CFG;

    static $payload = null;
    if ($payload !== null) {
        return $payload;
    }

    // Defaults for Page Builder (EPB), sourced from EPB license controller when available.
    $license = '';
    $itemname = '';
    $currentversion = '';

    $epbstatus = get_config('local_edwiserpagebuilder', 'edd_edwiser-page-builder-for-moodle_license_status') ?: '';
    if ($epbstatus === 'valid') {
        $license = get_config('local_edwiserpagebuilder', 'edd_edwiser-page-builder-for-moodle_license_key') ?: '';

        require_once($CFG->dirroot . '/local/edwiserpagebuilder/classes/license_controller.php');
        $epbctrl = new \edwiserpagebuilder_license_controller();
        if (!empty($epbctrl->wdmedwiserpagebuilderdata)) {
            $itemname = $epbctrl->wdmedwiserpagebuilderdata['plugin_name'] ?? $itemname;
            $currentversion = $epbctrl->wdmedwiserpagebuilderdata['plugin_version'] ?? $currentversion;
        }
    } else if (check_plugin_available('theme_remui')) {
        // Load RemUI constants once if necessary.
        if (!defined('PLUGINNAME')) {
            require_once($CFG->dirroot . '/theme/remui/classes/controller/LicenseController.php');
        }

        // Proceed only for the expected product name.
        if (defined('PLUGINNAME') && PLUGINNAME === 'Edwiser RemUI') {
            $remuistatus = get_config('theme_remui', 'edd_remui_license_status') ?: '';
            if ($remuistatus === 'valid') {
                $license = get_config('theme_remui', 'edd_remui_license_key') ?: '';
                $itemname = PLUGINNAME;
                $currentversion = PLUGINVERSION;
            }
        }
    }

    $payload = [
        'license' => $license,
        'item_name' => $itemname,
        'current_version' => $currentversion,
    ];
    return $payload;
}

/**
 * Check if EPB pro is allowed for a specific block.
 *
 * @param stdClass $block The block object.
 * @return int 1 if locked, 0 if not.
 */
function is_epb_pro_allowed_for_block($block) {
    // Determine if block should be locked based on requirements:
    // 1. If block is locked - it should be locked (return 1).
    // 2. If block is not locked AND block tier is pro AND pro not allowed - it should also be locked (return 1).
    // 3. Otherwise - it should not be locked (return 0).

    $isepblicensevalid = is_epb_pro_allowed();
    return (int)$block->locked ? 1 :
    ((!$isepblicensevalid && isset($block->tier) && $block->tier === 'pro') ? 1 : 0);
}

/**
 * Update the list of blocks.
 *
 * @param bool $limit The limit for update.
 * @param int $offset The offset for update.
 * @return mixed The result of the update.
 */
function local_edwiserpagebuilder_update_block_content($limit = false, $offset = 0) {
    $cm = new \local_edwiserpagebuilder\content_manager();
    return $cm->update_block_content($limit, $offset); // Update the block content on cron run.
}

/**
 * Update pro blocks content.
 *
 * @param bool $limit The limit for update.
 * @param int $offset The offset for update.
 * @return mixed The result of the update.
 */
function local_edwiserpagebuilder_update_pro_block_content($limit = false, $offset = 0) {
    $cm = new \local_edwiserpagebuilder\content_manager();
    $result = $cm->update_pro_block_content($limit, $offset); // Update pro block content bypassing version check.

    // Mark auto-update as done if update is complete.
    if ($result && is_array($result) && isset($result['complete']) && $result['complete']) {
        set_config('pro_blocks_auto_update_done', 1, 'local_edwiserpagebuilder');
    }

    return $result;
}
/**
 * Check user is admin or manager
 * @param  object  $userobject User object
 * @return boolean             True if admin or manager
 */
function check_user_admin_cap($userobject = null) {
    global $USER;
    if (!$userobject) {
        $userobject = $USER;
    }
    if (is_siteadmin()) {
        return true;
    }
    $context = \context_system::instance();
    $roles = get_user_roles($context, $userobject->id, false);
    foreach ($roles as $role) {
        if ($role->roleid == 1 && $role->shortname == 'manager') {
            return true;
        }
    }
    return false;
}

/**
 * Output fragment for the upload media file picker.
 *
 * @param array $args The fragment arguments.
 * @return string The rendered file picker HTML.
 */
function local_edwiserpagebuilder_output_fragment_upload_media_filepicker($args) {
    global $PAGE, $CFG;
    require_once($CFG->dirroot . '/lib/form/filemanager.php');
    $fmoptions = new stdClass();
    $fmoptions->maxbytes       = -1;
    $fmoptions->maxfiles       = -1;
    $fmoptions->itemid         = file_get_unused_draft_itemid();
    $fmoptions->subdirs        = false;
    $fmoptions->accepted_types = ['web_image', 'web_video'];
    $fmoptions->return_types   = FILE_INTERNAL | FILE_CONTROLLED_LINK;
    $fmoptions->context        = context_system::instance();
    ;
    $fmoptions->areamaxbytes   = FILE_AREA_MAX_BYTES_UNLIMITED;
    $fm = new \form_filemanager($fmoptions);
    $fileoutput = $PAGE->get_renderer('core', 'files');
    $renderedfilemanager = $fileoutput->render($fm);
    $output = '<div id="edwiser-tab-file-upload" class="tab-pane fade p-4 flex-grow-1 active show"'
        . ' role="tabpanel" aria-labelledby="edwiser-tab-file-select">';
    $output .= $renderedfilemanager;
    $output .= '<input type="hidden" name="file_upload_item_id" id="file_upload_item_id"'
        . ' value="' . $fmoptions->itemid . '" />';
    $output .= '</div>';
    return $output;
}

/**
 * Define CDN constants for block content URLs.
 */
function define_cdn_constants() {

    $serverhost = "https://staticcdn.edwiser.org";
    /*
        QA server Link:
        $serverhost = "https://qastaticcdn.edwiser.org";
    */

    defined('BLOCKS_CDN_URL') || define("BLOCKS_CDN_URL", $serverhost);
    defined('BLOCKS_CONTENT_URL') || define("BLOCKS_CONTENT_URL", $serverhost . "/json/");
    defined('BLOCKS_LIST_URL') || define("BLOCKS_LIST_URL", $serverhost . "/json/list_of_blocks.json");
    defined('CDNIMAGES') || define("CDNIMAGES", $serverhost . "/CDN/");

    defined('PAGE_LIST_URL') || define("PAGE_LIST_URL", $serverhost . "/json/list_of_pages.json");
    // Proxy API URL for fetching block content.
    defined('BLOCKS_PROXY_API_URL') ||
        define("BLOCKS_PROXY_API_URL", "https://staticcdn.edwiser.org/api/v1/block/get-block-content");
}

/**
 * Check if a plugin component is available.
 *
 * @param string $component The plugin component name.
 * @return bool True if the plugin is available.
 */
function check_plugin_available($component) {
    [$type, $name] = core_component::normalize_component($component);

    $dir = \core_component::get_plugin_directory($type, $name);
    if (!file_exists($dir ?? '')) {
        return false;
    }
    return true;
}

/**
 * Sort categories array by path
 * private function: only used by get_categories
 *
 * @param stdClass $category1
 * @param stdClass $category2
 * @return int result of strcmp
 * @since Moodle 2.3
 * @package local_edwiserpagebuilder
 */
function compare_categories_by_path($category1, $category2) {
    return strcmp($category1->path, $category2->path);
}

/**
 * Sort categories array by sortorder
 * private function: only used by get_categories
 *
 * @param array $category1
 * @param array $category2
 * @return int result of strcmp
 * @since Moodle 2.3
 * @package local_edwiserpagebuilder
 */
function compare_categories_by_sortorder($category1, $category2) {
    return strcmp($category1['sortorder'], $category2['sortorder']);
}



    /**
     * Returns the courses form.
     * @param  stdClass &config data object
     * @return stdClass config data
     * @package local_edwiserpagebuilder
     */
function html_data($configdata) {
        global $CFG, $OUTPUT;
        $systemcontext = context_system::instance();
        $count = count($configdata->block);
        $classname = "html-block p-0 col-12";
    switch ($count) {
        case 2:
            $classname .= " col-lg-6";
            break;
        case 3:
            $classname .= " col-lg-4";
            break;
        case 4:
            $classname .= " col-lg-3 col-md-6";
            break;
    }
        $configdata->classname = $classname;
        $styleprefix = ".home-sections [data-instance='" . $configdata->id . "'] .html-blocks .html-block:nth-child(";
    foreach ($configdata->block as $kblock => $block) {
        $configdata->block[$kblock]->html->text = file_rewrite_pluginfile_urls(
            $block->html->text,
            'pluginfile.php',
            $systemcontext->id,
            'theme_remui',
            'section_html',
            $block->html->itemid
        );
        $configdata->block[$kblock]->style = process_css($block->style, $styleprefix . ($kblock + 1) . ")");
        if (isset($configdata->applyfilter) && $configdata->applyfilter) {
            $configdata->block[$kblock]->html->text = format_text(
                $configdata->block[$kblock]->html->text,
                FORMAT_HTML,
                ["noclean" => true]
            );
        }
    }
        return $configdata;
}

    /**
     * Process custom css using regular expression
     * @param  String $styles      Css Style
     * @param  String $styleprefix Style prefix
     * @return String              Css Style
     * @package local_edwiserpagebuilder
     */
function process_css($styles, $styleprefix) {
    try {
        $scss = new core_scss();
        return $scss->compile($styleprefix . '{ ' . $styles . ' }');
    } catch (\Exception $e) {
        return '';
    }
}

/**
 * Get categories list from user settings
 * @param  array $configdata Config data array
 * @return array             Array of categories
 * @package local_edwiserpagebuilder
 */
function get_homepaegcourses_categories($categories) {
    global $DB;
    if (empty($categories)) {
        return \local_edwiserpagebuilder\coursehandler::get_allowed_categories('all');
    }
    foreach ($categories as $category) {
        $cats = \local_edwiserpagebuilder\coursehandler::get_allowed_categories($category);
        foreach ($cats as $cat) {
            if (!in_array($cat, $categories)) {
                array_push($categories, $cat);
            }
        }
    }
    return $categories;
}

    /**
     * Get all details of categories
     * @param  array   $categories Array of categories
     * @param  integer $start      Start index of categories
     * @param  integer $limit      Number of categories to be fetched
     * @return array               Array of categories with category details
     * @package local_edwiserpagebuilder
     */
function get_category_details($categories, $start = 0, $limit = 20) {
        global $OUTPUT, $CFG, $USER, $DB;
        $result = [];

        $activeflag = true;

        $total = count($categories);

        $categories = array_slice($categories, $start, $limit);

        $coursehandler = new \local_edwiserpagebuilder\coursehandler();

    foreach ($categories as $key => $category) {
        $rescategories = [];
        if ($activeflag) {
            $rescategories['active'] = true;
            $activeflag = false;
        }

        // Skip category id deleted or not exists.
        if (!$DB->record_exists('course_categories', ['id' => $category])) {
            continue;
        }

        if (class_exists('core_course_category')) {
            $category = \core_course_category::get($category, MUST_EXIST, true);
        } else if ('coursecat') {
            $category = \coursecat::get($category, MUST_EXIST, true);
        } else {
            continue;
        }

        if (!$category->visible) {
            $rescategories['ishidden'] = true;
        }

        $rescategories['categoryid'] = $category->id;
        $rescategories['categoryname'] = strip_tags(format_text($category->name));

        $categorycontext = context_coursecat::instance($category->id);
        $text = $category->description;
        $text = file_rewrite_pluginfile_urls($text, 'pluginfile.php', $categorycontext->id, 'coursecat', 'description', null);
        $catsummary = strip_tags(format_text($text));

        $catsummary = preg_replace('/\n+/', '', $catsummary);
        $catsummary = strlen($catsummary) > 150 ? mb_substr($catsummary, 0, 150) . "..." : $catsummary;
        $rescategories['categorydesc'] = $catsummary;
        $rescategories['categoryurl'] = $CFG->wwwroot . '/course/index.php?categoryid=' . $category->id;
        // ... Legacy course count method replaced below.
        $count = $coursehandler->get_courses(true, null, $category->id, 0, 0, null, null);
        $rescategories['coursecount'] = $count;

        if ($category->idnumber != '') {
            $rescategories['idnumber'] = $category->idnumber;
        }

        if ($category->parent != "0") {
            if (class_exists('core_course_category')) {
                $parent = \core_course_category::get($category->parent);
            } else if (class_exists('coursecat')) {
                $parent = \coursecat::get($category->parent);
            } else {
                continue;
            }
            $rescategories['parentname'] = $parent->name;
        }
        $result[] = $rescategories;
    }
        return [$total, $result];
}

/**
 * Get course block HTML content.
 *
 * @param stdClass $configdata The configuration data.
 * @param string $section The section name.
 * @return string The rendered course block HTML.
 */
function course_block($configdata, $section) {
    global $OUTPUT, $PAGE;
    $type = $configdata->show;
    $catid = '';
    $header = $OUTPUT->render_from_template('local_edwiserpagebuilder/course_title', $configdata);
    if (!empty($configdata->categories)) {
        foreach ($configdata->categories as $category) {
            $catid .= ',' . $category;
        }
    } else {
        $catid = 'all';
    }
    switch ($type) {
        case 'courses':
            return $header . "<div class='edwiser-cnc' data-edwiser-dynamic data-shortcode='edwiser-cnc'"
                . " data-vvveb-disabled-area data-layout='coursesncategories' data-show='courses'"
                . " data-catid='$catid' data-date='all' data-btnlabel='Explore'"
                . " contenteditable='false'>[edwiser-cnc layout='coursesncategories'"
                . " show='courses' catid='$catid' date='all']</div>";

        case 'categories':
            return $header . "<div class='edwiser-cnc' data-edwiser-dynamic data-shortcode='edwiser-cnc'"
                . " data-vvveb-disabled-area data-layout='coursesncategories' data-show='categories'"
                . " data-catid='$catid' data-date='all' data-btnlabel='Explore'"
                . " contenteditable='false'>[edwiser-cnc layout='coursesncategories'"
                . " show='categories' catid='$catid' date='all']</div>";

        case 'categoryandcourses':
            return $header . "<div class='edwiser-cnc' data-edwiser-dynamic data-shortcode='edwiser-cnc'"
                . " data-vvveb-disabled-area data-layout='coursesncategories' data-show='courses'"
                . " data-catid='$catid' data-date='all' data-btnlabel='Explore'"
                . " contenteditable='false'>[edwiser-cnc layout='coursesncategories'"
                . " show='courseandcategories' catid='$catid' date='all']</div>";
        default:
            break;
    }
}

/**
 * Get CSS for a given section name.
 *
 * @param string $sectionname The section name.
 * @return string The CSS for the section.
 */
function section_css($sectionname) {
    $sectioncss = [
        'contact' => '.section-contact a>i {
            font-size: 24px;
            filter: drop-shadow(1px 2px 2px rgba(0,0,0,.3));
        }
        .section-contact .social-icons .btn{
            min-width: 48px;
            min-height: 48px;
            color: #fff;
        } ',

        'aboutus' => '.section-aboutus.bg-image{
            background-attachment: fixed;
            background-repeat: no-repeat;
            background-size: cover;
        }
        .section-aboutus .btn.round {
            background-color: #8afb89;
            border-color: #8afb89 !important;
            transition: all .5s ease;
            height: 70px;
            width: 70px;
            margin-top: -20px;
        }
        .section-aboutus .btn.round i{
            font-size: 30px;
            color: #fff;
        }
        .section-aboutus .btn.round:hover{
            transform: rotate(360deg);
        }',

        'courses' => '.categoryandcourses-view.h-400 {
            height: 400px !important;
        }
        .categoryandcourses-view  .category-list .wrapper{
            border: none !important;
            height: 96% !important;
            display: flex;
            flex-direction: column;
        }
        .categoryandcourses-view .pb-lg-15 {
            padding-bottom: 15px!important;
        }
        .categoryandcourses-view.course-category-wrapper .card-list {
            width: 100%;
            overflow-y: auto;
            flex-grow: 1;
        }
        .course-category-wrapper ::-webkit-scrollbar {
            display: none;
        }
        .course-category-wrapper .card-list .category-item {
            width: 100%;
            margin-bottom: 12.5px;
            border-radius: 5px;
            padding: 15px;
            cursor: pointer;
        }
        .course-category-wrapper .card__owner {
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-transform: capitalize;
            margin-bottom: 5px;
        }',

        'feature' => '',

        'slider' => 'a[class^="carousel-control-"].nonav {
            display: none;
       }
        @media (max-width: 600px) {
            a[class^="carousel-control-"]:not(.nonav) i {
                font-size: 24px !important;
           }
       }
        @media (min-width: 600px) {
            a[class^="carousel-control-"]:not(.nonav) i {
                font-size: 37px;
           }
       }
        a[class^="carousel-control-"]:not(.nonav):not(.navarrows) i {
            background-color: #0000007a;
       }
        @media (max-width: 600px) {
            a[class^="carousel-control-"]:not(.nonav):not(.navarrows) i {
                width: 40px;
                height: 40px;
           }
       }
        @media (min-width: 600px) {
            a[class^="carousel-control-"]:not(.nonav):not(.navarrows) i {
                width: 60px;
                height: 60px;
           }
       }
        a[class^="carousel-control-"].navarrowscircle i {
            border-radius: 50%;
       }
        ',

        'team' => '',

        'testimonial' => '.section-testimonial .img-box img {
            width: 135px;
            height: 135px;
            margin: 5px auto;
            padding: 5px;
            border-radius: 50%;
        }',
        'separator' => '',
        'html' => '',
    ];
    return $sectioncss[$sectionname];
}

/**
 * Check and return the advanced block type flag.
 *
 * @param string $blockname The block name.
 * @param string $blocktype The block type.
 * @return string The block type flag.
 */
function check_advblock_type($blockname, $blocktype) {
    $blocktypearray = [
        'dynamic' => 'dynamic-block',
        'static' => 'static-block',
        'blocklayout' => 'block-page-layout',
    ];
    $dynamicblockflag = $blocktypearray['static'];
    if (array_key_exists($blocktype, $blocktypearray)) {
        $dynamicblockflag = $blocktypearray[$blocktype];
    }
    if ($blockname == 'courses' || $blockname == "categories" || $blockname == "coursesncategories") {
        $dynamicblockflag = $blocktypearray['dynamic'];
    }
    return $dynamicblockflag;
}

/**
 * Get block info for the add block modal.
 *
 * @param string $blockname The block name.
 * @return string|false The block description or false.
 */
function block_info_in_addblockmodel($blockname) {

    $key = $blockname . 'blockdesc';

    $blockinfo = [
        'coursesncategoriesblockdesc' => get_string("coursesncategoriesblockdesc", "local_edwiserpagebuilder"),
        'addnotesblockdesc' => get_string("addnotesblockdesc", "local_edwiserpagebuilder"),
        'latestmembersblockdesc' => get_string("latestmembersblockdesc", "local_edwiserpagebuilder"),
        'courseanalyticsblockdesc' => get_string("courseanalyticsblockdesc", "local_edwiserpagebuilder"),
        'courseprogressblockdesc' => get_string("courseprogressblockdesc", "local_edwiserpagebuilder"),
        'enrolledusersblockdesc' => get_string("enrolledusersblockdesc", "local_edwiserpagebuilder"),
        'quizattemptsblockdesc' => get_string("quizattemptsblockdesc", "local_edwiserpagebuilder"),
        'recentfeedbackblockdesc' => get_string("recentfeedbackblockdesc", "local_edwiserpagebuilder"),
        'recentforumsblockdesc' => get_string("recentforumsblockdesc", "local_edwiserpagebuilder"),
        'todolistblockdesc' => get_string("todolistblockdesc", "local_edwiserpagebuilder"),
        'featuredcoursesblockdesc' => get_string("featuredcoursesblockdesc", "local_edwiserpagebuilder"),
    ];

    $blockdescription = false;

    if (array_key_exists($key, $blockinfo)) {
        $blockdescription = $blockinfo[$key];
    }

    return $blockdescription;
}

/**
 * Rearrange blocks to put HTML block first.
 *
 * @param array $blocks The array of blocks.
 * @return array The rearranged blocks array.
 */
function html_block_rearrange($blocks) {
    $htmlblock = '';
    for ($i = 0; $i < count($blocks); $i++) {
        if (isset(($blocks[$i]->title)) && ($blocks[$i]->title == 'html' )) {
            $htmlblock = $blocks[$i];
            $htmlblock->label = get_string('createcustomblocks', 'local_edwiserpagebuilder');
            unset($blocks[$i]);
            break;
        }
    }
    array_unshift($blocks, $htmlblock);
    return $blocks;
}


/**
 * Get plugin release info.
 *
 * @return stdClass plugin release
 * @package local_edwiserpagebuilder
 */
function get_pagebuilder_req_plugin_release_info($pluginname) {
    $pluginman = core_plugin_manager::instance();
    $plugininfo = $pluginman->get_plugin_info($pluginname);
    return $plugininfo;
}

/**
 * This function check  plugin is available or not.
 *
 * @return boolean
 */

/**
 * Check if a required plugin is available.
 *
 * @param string $component The plugin component name.
 * @return bool True if the plugin is available.
 */
function is_pagebuilder_req_plugin_available($component) {

    [$type, $name] = core_component::normalize_component($component);

    $dir = \core_component::get_plugin_directory($type, $name);
    if ($dir === null || !file_exists($dir)) {
        return false;
    }
    return true;
}

/**
 * Check the plugin version series.
 *
 * @param string $versionstring The version string.
 * @return string The version series.
 */
function check_plugin_version_series($versionstring) {
    $versionparts = explode('.', $versionstring);
    $majorversion = (int) $versionparts[0];
    $minorversion = (int) $versionparts[1];
    if ($majorversion === 4) {
        if ($minorversion === 1) {
            return '4.1';
        } else if ($minorversion === 0) {
            return '4.0';
        } else if ($minorversion === 2) {
            return '4.2';
        }
    } else {
        return 'nodata';
    }
}

/**
 * Get section config by section instance id
 * @param  int    $instanceid Instance id of section
 * @return object             Section record
 */

/**
 * Get configuration by instance ID.
 *
 * @param int $instanceid The instance ID.
 * @return object The section record.
 */
function get_config_by_instanceid($instanceid) {
    global $DB;
    $record = $DB->get_record('remuihomepage_sections', ['id' => $instanceid]);
    return $record;
}

    /**
     * Get all courses from selected categories and date filter
     * @param  array  $categories Categories list
     * @param  string $date       Date filter
     * @return array              Array of course
     * @package local_edwiserpagebuilder
     */
function get_courses_from_category($categories, $date, $start = 0) {
    global $CFG;

    // Retrieve list of courses in category.
    $coursehandler = new \local_edwiserpagebuilder\coursehandler();
    $where = 'WHERE c.id <> :siteid ';
    $params = ['siteid' => SITEID];
    $join = '';
    $sesskey = strtolower(sesskey());
    $cattable = 'catids' . $sesskey;
    if (is_numeric($categories) || is_array($categories)) {
        if (is_numeric($categories)) {
            $categories = \local_edwiserpagebuilder\coursehandler::get_allowed_categories($categories);
        } else {
            $categories = get_homepaegcourses_categories($categories);
        }
        if (!empty($categories)) {
            $cats = [];
            foreach ($categories as $category) {
                $cats[] = (object)[
                    'tempid' => $category,
                ];
            }
            $coursehandler->create_temp_table($cattable, $cats);
            $join = " INNER JOIN {" . $cattable . "} catids ON c.category = catids.tempid";
        }
    }

    if (!empty($search)) {
        $search = '%' . str_replace(' ', '%', $search) . '%';
        $where .= " AND ( LOWER(c.fullname) like LOWER(:name1) OR LOWER(c.shortname) like LOWER(:name2) )";
        $params = $params + ["name1" => $search, "name2" => $search];
    }
    // Get list of courses without preloaded coursecontacts because we don't need them for every course.

    [$total, $courses] = $coursehandler->get_course_records(
        $where,
        $join,
        $params,
        [
           'summary' => true,
           'filtermodified' => true,
           'limitfrom' => $start,
           'limitto' => 25,
           'totalcount' => true,
        ]
    );
    if (is_numeric($categories) || is_array($categories)) {
        $coursehandler->drop_table($cattable);
    }

    if (empty($courses)) {
        return [0, []];
    }

    $obj = new \stdClass();
    $obj->sort = null;
    $obj->search = "";
    $obj->tab = false;
    $obj->page = (object)['courses' => -1];
    $obj->pagination = false;
    $obj->view = null;
    $obj->isFilterModified = "0";
    $obj->limiteddata = true;
    $obj->courses = $courses;
    $obj->category = 0;
    $obj->limiteddata = true;

    $result = $coursehandler->get_course_cards_content($obj, $date);

    return [$total, $result['courses']];
}


/**
 * Get the RemUI theme release version information.
 *
 * @return array The theme release version data.
 */
function edw_get_remui_theme_release_version() {
    if (is_pagebuilder_req_plugin_available("theme_remui")) {
        $themereleasedata = get_pagebuilder_req_plugin_release_info("theme_remui");
        $themerelease = $themereleasedata->release;
        $themeversion = $themereleasedata->versiondb;
    } else {
        $themerelease = 0;
        $themerelease = 0;
    }
    $pluginversions = [
        '4.0' => '4.0.10',
        '4.1' => '4.1.5',
        '4.2' => '4.2.0',
        'nodata' => $themerelease,
    ];

    $pluginseries = check_plugin_version_series($themerelease);
    return [
        'oldthemerelease' => $pluginversions[$pluginseries],
        'newthemereleaseversion' => $themerelease,
        'themeversion' => $themeversion,
    ];
}

/**
 * Callback function for modal fragment in ToDoList Block.
 *
 * @param array $args The fragment arguments.
 * @return string The rendered task form.
 */
function local_edwiserpagebuilder_output_fragment_task_form($args) {
    $taskid = $args['taskid'];
    $mform = new local_edwiserpagebuilder_task_popup_form($taskid);
    if ($taskid != -1) {
        $taskhandler = new \local_edwiserpagebuilder\remuiblck\taskhandler($taskid);
        $task = $taskhandler->get_task();
        $mform->set_data([
            'subject' => $task->subject,
            'summary' => $task->summary,
            'timedue' => $task->timedue,
            'userlist' => json_decode($task->assignedto, true),
            'visible' => $task->visible,
            'notify' => $task->notify,
        ]);
    }
    return $mform->render();
}

/**
 * A centralised location for the all name fields. Returns an array / sql string snippet.
 *
 * @param bool $returnsql True for an sql select field snippet.
 * @param string $tableprefix table query prefix to use in front of each field.
 * @param string $prefix prefix added to the name fields e.g. authorfirstname.
 * @param string $fieldprefix sql field prefix e.g. id AS userid.
 * @param bool $order moves firstname and lastname to the top of the array / start of the string.
 * @return array|string All name fields.
 * @see \core_user\fields
 * @package local_edwiserpagebuilder
 */
function get_all_users_name_fields_rmblck(
    $returnsql = false,
    $tableprefix = null,
    $prefix = null,
    $fieldprefix = null,
    $order = false
) {
    // This array is provided in this order because when called by fullname() (above) if firstname is before
    // firstnamephonetic str_replace() will change the wrong placeholder.
    $alternatenames = [];
    foreach (\core_user\fields::get_name_fields() as $field) {
        $alternatenames[$field] = $field;
    }

    // Let's add a prefix to the array of user name fields if provided.
    if ($prefix) {
        foreach ($alternatenames as $key => $altname) {
            $alternatenames[$key] = $prefix . $altname;
        }
    }

    // If we want the end result to have firstname and lastname at the front / top of the result.
    if ($order) {
        // Move the last two elements (firstname, lastname) off the array and put them at the top.
        for ($i = 0; $i < 2; $i++) {
            // Get the last element.
            $lastelement = end($alternatenames);
            // Remove it from the array.
            unset($alternatenames[$lastelement]);
            // Put the element back on the top of the array.
            $alternatenames = array_merge([$lastelement => $lastelement], $alternatenames);
        }
    }

    // Create an sql field snippet if requested.
    if ($returnsql) {
        if ($tableprefix) {
            if ($fieldprefix) {
                foreach ($alternatenames as $key => $altname) {
                    $alternatenames[$key] = $tableprefix . '.' . $altname . ' AS ' . $fieldprefix . $altname;
                }
            } else {
                foreach ($alternatenames as $key => $altname) {
                    $alternatenames[$key] = $tableprefix . '.' . $altname;
                }
            }
        }
        $alternatenames = implode(',', $alternatenames);
    }
    return $alternatenames;
}

/**
 * Get the date difference between two timestamps.
 *
 * @param int $timecreated The created timestamp.
 * @param int $currenttime The current timestamp.
 * @return DateInterval The date difference.
 */
function get_date_differences($timecreated, $currenttime) {
    $date1 = new DateTime();
    $date1->setTimeStamp($currenttime);
    $date2 = new DateTime();
    $date2->setTimeStamp($timecreated);
    return date_diff($date1, $date2);
}

/**
 * Define user preferences for the plugin.
 *
 * @return array The user preferences definition.
 */
function local_edwiserpagebuilder_user_preferences(): array {
    return [
        'always-load-progress' => [
            'type' => PARAM_BOOL,
            'null' => NULL_NOT_ALLOWED,
            'default' => false,
            'permissioncallback' => [core_user::class, 'is_current_user'],
        ],
    ];
}

/**
 * Maximum number of free AI generation requests per admin user.
 *
 * @return int The maximum free requests allowed.
 * @package local_edwiserpagebuilder
 */
function get_ai_max_free_requests() {
    return 10;
}

/**
 * Get the HMAC signing key derived from Moodle's secret salt and a plugin-specific pepper.
 *
 * @return string The signing key.
 * @package local_edwiserpagebuilder
 */
function epb_get_signing_key() {
    global $CFG;
    return hash('sha256', $CFG->secretsaltmain . '::epb_ai_gen_requests_v1', true);
}

/**
 * Sign and store AI generation request data.
 *
 * The data is stored as base64(json_payload.hmac_signature) so that
 * tampering with the config value is detectable.
 *
 * @param array $data The request count data.
 * @package local_edwiserpagebuilder
 */
function epb_save_ai_request_data(array $data) {
    $payload = json_encode($data);
    $signature = hash_hmac('sha256', $payload, epb_get_signing_key());
    $signed = base64_encode($payload . '.' . $signature);
    set_config('ai_generation_requests', $signed, 'local_edwiserpagebuilder');
}

/**
 * Load and verify AI generation request data.
 *
 * Returns the decoded data array if the signature is valid.
 * Returns null if the data has been tampered with or is missing.
 *
 * @return array|null The request count data, or null if invalid/tampered.
 * @package local_edwiserpagebuilder
 */
function epb_load_ai_request_data() {
    $config = get_config('local_edwiserpagebuilder', 'ai_generation_requests');
    if (empty($config)) {
        return [];
    }

    $decoded = base64_decode($config, true);
    if ($decoded === false) {
        // Not valid base64 — could be old unencrypted data, migrate it.
        return epb_migrate_legacy_ai_data($config);
    }

    $dotpos = strrpos($decoded, '.');
    if ($dotpos === false) {
        return epb_migrate_legacy_ai_data($config);
    }

    $payload = substr($decoded, 0, $dotpos);
    $signature = substr($decoded, $dotpos + 1);

    $expected = hash_hmac('sha256', $payload, epb_get_signing_key());
    if (!hash_equals($expected, $signature)) {
        // Signature mismatch — data has been tampered with.
        return null;
    }

    $data = json_decode($payload, true);
    return is_array($data) ? $data : null;
}

/**
 * Migrate legacy unencrypted AI request data to signed format.
 *
 * @param string $config The raw config value.
 * @return array The migrated data.
 * @package local_edwiserpagebuilder
 */
function epb_migrate_legacy_ai_data($config) {
    $data = json_decode($config, true);
    if (!is_array($data)) {
        $data = [];
    }
    // Re-save with signature.
    epb_save_ai_request_data($data);
    return $data;
}

/**
 * Get the number of AI generation requests remaining for a user.
 *
 * Pro users get unlimited requests (returns -1).
 * Free-tier users are limited to get_ai_max_free_requests().
 * Returns 0 if the stored data has been tampered with.
 *
 * @param int $userid The user ID.
 * @return int Remaining requests, or -1 for unlimited (pro users).
 * @package local_edwiserpagebuilder
 */
function get_ai_requests_remaining($userid) {
    if (is_epb_pro_allowed()) {
        return -1;
    }

    $data = epb_load_ai_request_data();
    if ($data === null) {
        // Tampered data — treat as exhausted.
        return 0;
    }

    $max = get_ai_max_free_requests();
    $key = $userid . '_genrequest';
    $used = isset($data[$key]) ? (int) $data[$key] : 0;

    return max(0, $max - $used);
}

/**
 * Increment the AI generation request count for a user.
 *
 * @param int $userid The user ID.
 * @return int The new remaining request count.
 * @package local_edwiserpagebuilder
 */
function increment_ai_request_count($userid) {
    $data = epb_load_ai_request_data();
    if ($data === null) {
        // Tampered data — treat as exhausted.
        return 0;
    }

    $key = $userid . '_genrequest';
    $current = isset($data[$key]) ? (int) $data[$key] : 0;
    $data[$key] = $current + 1;

    epb_save_ai_request_data($data);

    $max = get_ai_max_free_requests();
    return max(0, $max - $data[$key]);
}
