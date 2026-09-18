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
 * Page manager class for creating and managing page templates and instances.
 *
 * @package   local_edwiserpagebuilder
 * @copyright (c) 2022 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author    Gourav Govande
 */

namespace local_edwiserpagebuilder;

defined('MOODLE_INTERNAL') || die;

global $CFG;
require_once($CFG->dirroot . "/local/edwiserpagebuilder/lib.php");
define_cdn_constants();

use stdClass;
use context_course;
use moodle_page;
/**
 * Page Manager
 */
class page_manager {
    /**
     * Page content table name.
     * @var string
     */
    private $pagecontenttable = "edw_page_content";

    /**
     * Page instance table name.
     * @var string
     */
    private $pageinstancetable = "edw_page_instance";

    /**
     * Can create page
     * Check if current user has capability to create page.
     */
    public function has_capability_to_create_page() {
        $context = context_course::instance(1); // System level course.
        if (has_capability('moodle/course:manageactivities', $context)) {
            return true;
        }

        return false;
    }
    /**
     * Can create page
     * Check if current user has capability to create page and check if mod_page plugin is available.
     */
    public function can_add_page_activity() {
        if ($this->has_capability_to_create_page() && check_plugin_available("mod_page")) {
            return true;
        }
        return false;
    }

    /**
     * Perform page action based on the action type.
     *
     * @param string $action     Action to perform.
     * @param int    $instid     Instance id.
     * @param int    $pageid     Page id.
     * @param string $pagename   Page name.
     * @param bool   $fullscreen Fullscreen mode.
     * @return mixed Result of the action.
     */
    public function perform_page_action($action, $instid, $pageid, $pagename, $fullscreen) {
        switch ($action) {
            case "create":
                return $this->create_page($pageid, $pagename, $fullscreen);
            case "update":
                break;
            case "delete":
                return false;
            default:
                return true;
        }
    }

    /**
     * Create a new page from template.
     *
     * @param int    $pageid     Page template id.
     * @param string $pagename   Page name.
     * @param bool   $fullscreen Fullscreen mode.
     * @return mixed Page creation result.
     */
    public function create_page($pageid, $pagename, $fullscreen) {

        // Check if user can add this page.
        if (!$this->can_add_page_activity()) {
            // TODO :: return proper failure msg to user.
            return false;
        }

        // Fetch selected template configuration.
        $templateconfig = $this->get_page_default_configuration(["id" => $pageid]);

        if (!$templateconfig) {
            return false;
        }

        // Create Page module with fetched configuration.
        $modinfo = $this->create_page_module($pagename);

        if (isset($modinfo->status) && $modinfo->status == false) {
            return $modinfo;
        }

        // Create instance of created page module in our custom tables.
        $config = json_decode($templateconfig->config);
        $config->pageref = $pageid;
        $config->title = $pagename;
        $config->fullscreen = $fullscreen;
        $this->set_page_inst_configurations($modinfo->id, $config);

        $addblock = 'edwiseradvancedblock';
        $this->create_block_instance_with_config('side-top', $addblock, json_decode($templateconfig->listofblocks), $modinfo);
    }

    /**
     * Create block instances with config on a page.
     *
     * @param string $region       Block region.
     * @param string $blockname    Block name.
     * @param array  $listofblocks List of blocks to add.
     * @param object $modinfo      Module info object.
     */
    public function create_block_instance_with_config($region, $blockname, $listofblocks, $modinfo) {

        // Create new page object.
        $page = new moodle_page();

        // Set page context for newly created page module.
        $page->set_context(\context_module::instance($modinfo->coursemodule));

        // Add region where you want to add your new blocks.
        $page->blocks->add_region('side-top');

        // Load all available block on the page.
        $page->blocks->load_blocks();

        // Page pattern to be added on page.
        $pagepattern = 'mod-page-*';

        $bh = new block_handler();

        // Fetched configuration has blocks list to be added on the page.
        foreach ($listofblocks as $key => $block) {
            $config = new \stdClass();
            $config->html['text'] = $block->content->html;
            $config->css['text'] = $block->content->css;
            $config->js['text'] = $block->content->js;

            $blockinstance = $bh->create_block_on_page($blockname, $region, 0, false, $pagepattern, null, $page);
            $blockinstance->instance_config_save($config, false);
        }
    }

    /**
     * Create a page module in the system course.
     *
     * @param string $pagename Page name.
     * @return mixed Module info or error response.
     */
    public function create_page_module($pagename) {
        global $DB, $CFG;

        if (!$this->can_add_page_activity()) {
            return $this->send_response_with_msg(false, get_string("cannotaddpage", "local_edwiserpagebuilder"));
        }

        require_once($CFG->dirroot . '/course/modlib.php');

        // Add module to system level course.
        $course = $DB->get_record('course', ['id' => 1], '*', MUST_EXIST);

        $formobj = new stdClass();
        // We just modify the title.
        $formobj->name = $pagename;

        // Default settings to keep.
        $formobj->showdescription = 0;
        $formobj->display = 5;
        $formobj->printheading = 0;
        $formobj->printintro = 0;
        $formobj->printlastmodified = 0;
        $formobj->visible = 1;
        $formobj->visibleoncoursepage = 1;
        $formobj->tags = [];
        $formobj->course = 1;
        $formobj->coursemodule = 0;
        $formobj->section = 1;
        $formobj->module = $DB->get_record('modules', ['name' => "page"], 'id', MUST_EXIST)->id;
        $formobj->modulename = "page";
        $formobj->instance = 0;
        $formobj->add = "page";
        $formobj->update = 0;
        $formobj->return = 0;
        $formobj->sr = 0;
        $formobj->competencies = [];
        $formobj->competency_rule = 0;

        return add_moduleinfo($formobj, $course);
    }

    /**
     * Set all page instance configurations.
     *
     * @param int    $instid Instance id.
     * @param object $config Configuration object.
     */
    public function set_page_inst_configurations($instid, $config) {
        foreach ($config as $key => $value) {
            $this->set_page_inst_configuration($instid, $key, $value);
        }
    }

    /**
     * Set a single page instance configuration value.
     *
     * @param int    $instid Instance id.
     * @param string $key    Configuration key.
     * @param mixed  $value  Configuration value.
     */
    public function set_page_inst_configuration($instid, $key, $value) {
        global $DB;

        // Generate standard object.
        $object = new stdClass();
        $object->instid = $instid;
        $object->meta_key = $key;
        $object->meta_value = $value;

        // Check if entry already present for given instid and key.
        $record = $this->get_page_inst_configuration($instid, $key);

        if ($record) {
            // Update the value only in instance table for given instid and key.
            $object->id = $record->id;
            $DB->update_record($this->pageinstancetable, $object, false);
        } else {
            // Create a DB query and make an entry in instance table.
            $DB->insert_record($this->pageinstancetable, $object, true, false);
        }
    }

    /**
     * Get all the instance configuration for given condition.
     *
     * @param array $condition Query conditions.
     * @return array Configuration records.
     */
    public function get_page_inst_configurations($condition) {
        global $DB;

        // Fetch all configuration for give id.
        $record = $DB->get_records($this->pageinstancetable, $condition);
        return $record;
    }

    /**
     * Get instance configuration for given instid and key.
     *
     * @param int    $instid Instance id.
     * @param string $key    Configuration key.
     * @return object|false Configuration record or false.
     */
    public function get_page_inst_configuration($instid, $key) {
        global $DB;

        // Fetch configuration for given instid and key.
        $record = $DB->get_record($this->pageinstancetable, ["instid" => $instid, "meta_key" => $key], '*');

        return $record;
    }


    /**
     * Delete instance configuration for given pageid and key.
     *
     * @param int    $pageid Page id.
     * @param string $key    Configuration key.
     */
    public function unset_page_inst_configuration($pageid, $key) {
        // TODO :: DB query to delete the key from instance table for given page id.
    }

    /* PAGE TEMPLATES CREATION */
    /**
     * This function will update the content of pages
     * This make an entry in page content table.
     */
    public function update_page_content($pageid, $content) {
        global $DB;

        $bh = new block_handler();

        $reftable = $this->pagecontenttable;

        $record = $bh->get_data_with_title($content->title, $reftable);
        try {
            if (!$record) {
                // Make an entry for new data.

                $content->pageid = $pageid;
                $content->config = json_encode($content->config);
                $content->categories = json_encode($content->categories);
                $content->listofblocks = json_encode($content->listofblocks);

                $recordid = $DB->insert_record($reftable, $content, true, false);
            }
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * Get available page templates grouped by category.
     *
     * @return array Page templates grouped by category.
     */
    public function get_available_page_templates_by_group() {
        $bh = new \local_edwiserpagebuilder\block_handler();
        $pages = $bh->fetch_blocks_list(["type" => "page"]);

        $pagegroup = [];
        foreach ($pages as $key => $page) {
            // Encode the categories first.
            $page->categories = json_decode($page->categories);
            // Replace the CDNURL for images.
            $page->thumbnail = str_replace("{{>cdnurl}}", CDNIMAGES, $page->thumbnail);
            foreach ($page->categories as $key => $category) {
                $pagegroup[$category]["grouptitle"] = get_string($category, "local_edwiserpagebuilder");
                $pagegroup[$category]["pages"][] = $page;
            }
        }

        $pagegroup = array_values($pagegroup);

        return $pagegroup;
    }

    /**
     * Get page default configuration for given condition.
     *
     * @param array $condition Query conditions.
     * @return object|false Page configuration record or false.
     */
    public function get_page_default_configuration($condition) {
        // Fetch page configuration for given condition.
        global $DB;
        $record = $DB->get_record($this->pagecontenttable, $condition);
        return $record;
    }

    /**
     * Generate context for page preview modal.
     *
     * @param object $pageconfig Page configuration object.
     * @return object Page config with preview content.
     */
    public function generate_preview_content_for_page($pageconfig) {
        global $DB, $CFG;

        $config = json_decode($pageconfig->config);

        $records = $DB->get_records_sql(
            "SELECT * FROM {edw_page_instance} WHERE instid IN (
                SELECT instid FROM {edw_page_instance}
                WHERE meta_key=:metakey AND meta_value=:metaval
            )",
            ['metakey' => 'pageref', 'metaval' => $pageconfig->id]
        );

        $finalarr = [];

        $finalarr[0]["instid"] = 0;
        $finalarr[0]["title"] = $config->title;
        $finalarr[0]["label"] = get_string("createnewpage", "local_edwiserpagebuilder");
        $finalarr[0]["fullscreen"] = $config->fullscreen;
        $finalarr[0]["pageref"] = $pageconfig->id;

        foreach ($records as $key => $record) {
            $finalarr[$record->instid]["instid"] = $record->instid;
            $finalarr[$record->instid][$record->meta_key] = $record->meta_value;

            if ($record->meta_key == "title") {
                $finalarr[$record->instid]["label"] = $record->meta_value;
            }
        }

        $pageconfig->pages = array_values($finalarr); // Returning page config together.

        // Replace cdn url for preview image.
        $pageconfig->previewurl = str_replace("{{>cdnurl}}", CDNIMAGES, $pageconfig->previewurl);

        return $pageconfig;
    }

    /**
     * Deprecate a page template.
     *
     * @param string $pagetitle Page title.
     */
    public function deprecate_page($pagetitle) {
        // TODO :: Deprecate the page template.
        // TODO :: allow then to delete the page.
    }

    /**
     * Delete a page instance.
     *
     * @param int $pageid Page id.
     */
    public function delete_page_instance($pageid) {
        // TODO :: this todo is not to be written here, create event observer for
        // \core\event\course_module_deleted, and check if current module which is
        // being deleted is page module and is activity from course id 1.
        // TODO :: Delete all configuration saved in instance table.
    }

    /**
     * Create a header menu entry for a page.
     *
     * @param int $pageid Page id.
     */
    public function create_header_menu($pageid) {
        // TODO :: Create an entry for given pageid to header custom menu.
    }

    /**
     * Send a standardized response with status and message.
     *
     * @param bool   $status Response status.
     * @param string $msg    Response message.
     * @return array Response array.
     */
    public function send_response_with_msg($status, $msg) {
        return ["status" => $status, "msg" => $msg];
    }
}
