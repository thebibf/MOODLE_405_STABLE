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
 * Block handler class for Edwiser Page Builder.
 *
 * @package   local_edwiserpagebuilder
 * @copyright (c) 2022 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author    Gourav Govande
 */

namespace local_edwiserpagebuilder;

/**
 * Blocks Manager
 */
class block_handler {
    /** @var int Block ID. */
    private $id;
    /** @var string Block title. */
    private $title;
    /** @var string Block label. */
    private $label;
    /** @var string Block thumbnail URL. */
    private $thumbnail;
    /** @var string Block content. */
    private $content;
    /** @var string Block version. */
    private $version;
    /** @var int Update available flag. */
    private $update;
    /** @var int Visibility flag. */
    private $visible;

    /** @var string Table name where block data is stored at. */
    private $table = "local_edwiserpagebuilder_blocks";

    /** @var string Card Layout Table name where block data is stored at. */
    private $cltable = "local_edwiserpagebuilder_blklayouts";

    /**
     * Insert or Update the block content.
     *
     * @param object $data Block data to insert or update.
     * @param bool $bypassversion Whether to bypass version check.
     * @return int|string|null Record ID or error message.
     */
    public function make_entry($data, $bypassversion = false) {
        global $DB;

        $reftable = $this->get_block_table_name();

        $record = $this->get_data_with_title($data->title, $reftable);
        $recordid = null;
        try {
            if (!$record) {
                // Make an entry for new data.
                if (isset($data->categories)) {
                    $data->categories = $data->categories;
                }
                $recordid = $DB->insert_record($reftable, $data, true, false);
            } else {
                // Update the db only when latest version is available, unless bypassversion is true.
                if ($bypassversion || $data->version > $record->version) {
                    // Update updateavailable parameter only.
                    if (isset($data->categories)) {
                        $record->categories = $data->categories;
                    }
                    $record->updateavailable = 1;
                    $record->visible = 1;
                    $record->thumbnail = $data->thumbnail;

                    // If bypassing version, update all fields from data including locked field.
                    if ($bypassversion) {
                        $data->id = $record->id;
                        $data->updateavailable = 0;
                        $myrecord = $DB->update_record($reftable, $data, false);
                    } else {
                        $myrecord = $DB->update_record($reftable, $record, false);
                    }
                    $recordid = $record->id;
                }
            }
        } catch (Exception $ex) {
            return $e->getMessage();
        }
        return $recordid;
    }

    /**
     * Get the block table name.
     *
     * @return string
     */
    public function get_block_table_name() {
        return $this->table;
    }

    /**
     * Get the card layout table name.
     *
     * @return string
     */
    public function get_cl_table_name() {
        return $this->cltable;
    }

    /**
     * Fetch and update the block content.
     *
     * @param object $data Block data to update.
     * @param bool $islayout Whether to update a layout.
     * @return bool|string True on success, false or error message on failure.
     */
    public function update_block_content($data, $islayout = false) {
        global $DB;

        $reftable = (!$islayout) ? $this->get_block_table_name() : $this->get_cl_table_name();

        $record = $this->get_data_with_title($data->title, $reftable);
        if ($record) {
            $data->id = $record->id;
            $data->updateavailable = 0;

            // First check if visibility is on, if not then directly update the block.
            if ($record->visible) {
                if ($data->version <= $record->version) {
                    return false;
                }
            }

            $data->visible = 1;

            try {
                $DB->update_record($reftable, $data, false);
                return true;
            } catch (Exception $ex) {
                return $e->getMessage();
            }
        }
    }

    /**
     * Get the DB record for given title.
     *
     * @param string $title Block title to search for.
     * @param string $reftable Table name to search in.
     * @return object|null
     */
    public function get_data_with_title($title, $reftable) {
        global $DB;

        $record = $DB->get_record($reftable, ['title' => $title], '*');

        if (!$record) {
            return null;
        }

        return $record;
    }

    /**
     * Fetch blocks list
     * @param array $condition optional array $fieldname=>requestedvalue with AND in between
     * @param string $fields a comma separated list of fields to return (optional, by default
     *   all fields are returned). The first field will be used as key for the
     */
    public function fetch_blocks_list($condition = [], $fields = '*') {
        $reftable = $this->get_block_table_name();
        return $this->get_record_from_table($reftable, $condition, $fields);
    }

    /**
     * Fetch Layouts list
     * @param array $condition optional array $fieldname=>requestedvalue with AND in between
     * @param string $fields a comma separated list of fields to return (optional, by default
     *   all fields are returned). The first field will be used as key for the
     */
    public function get_layouts_list($condition = [], $fields = '*') {
        $reftable = $this->get_cl_table_name();
        return $this->get_record_from_table($reftable, $condition, $fields);
    }

    /**
     * Delete the block.
     * @param int $id block id
     * @param boolean $islayout if true delete the layout or delete the block
     */
    public function delete_the_block($id, $islayout = false) {
        global $DB;
        if ($islayout) {
            $reftable = $this->get_cl_table_name();
        } else {
            $reftable = $this->get_block_table_name();
        }
        return $DB->delete_records($reftable, ["id" => $id]);
    }

    /**
     * Fetch Data record from table
     * @param string $reftable table name to refer
     * @param array $condition optional array $fieldname=>requestedvalue with AND in between
     * @param string $fields a comma separated list of fields to return (optional, by default
     *   all fields are returned). The first field will be used as key for the
     */
    public function get_record_from_table($reftable, $condition = [], $fields = '*') {
        global $DB;

        return $DB->get_records(
            $reftable,
            $condition,
            'title', // Sort by title.
            $fields
        );
    }

    /**
     * Insert or Update the card layouts.
     *
     * @param object $data Layout data to insert or update.
     * @param bool $updatecontent Whether to update content.
     * @param bool $bypassversion Whether to bypass version check.
     * @return bool|string True on success, error message on failure.
     */
    public function make_entry_layout($data, $updatecontent = true, $bypassversion = false) {
        global $DB;
        $reftable = $this->get_cl_table_name();

        $record = $this->get_data_with_title($data->title, $reftable);

        try {
            if (!$record) {
                // Make an entry for new data.
                $DB->insert_record($reftable, $data, true, false);
            } else {
                // Update the db only when latest version is available, unless bypassversion is true.
                if ($bypassversion || $data->version > $record->version) {
                    if ($updatecontent) {
                        $data->id = $record->id;
                        $data->updateavailable = 0;
                        $DB->update_record($reftable, $data, false);
                    } else {
                        // Update updateavailable parameter only.
                        $record->updateavailable = 1;
                        $record->visible = 1;
                        $record->thumbnail = $data->thumbnail;
                        $DB->update_record($reftable, $record, false);
                    }
                }
            }
        } catch (Exception $ex) {
            return $e->getMessage();
        }
        return true;
    }

    /**
     * Deprecate the block.
     *
     * @param string $reftable Table name.
     * @param object $data Block data to deprecate.
     * @return void
     */
    public function deprecate_block($reftable, $data) {
        global $DB;
        // Set visibility to zero for deprecation.
        $data->visible = 0;
        $DB->update_record($reftable, $data, false);
    }

    /**
     * Create a block instance on a page.
     *
     * @param string $blockname Block name to create.
     * @param string $region Region to place block in.
     * @param int $weight Block weight.
     * @param bool $showinsubcontexts Whether to show in subcontexts.
     * @param string|null $pagetypepattern Page type pattern.
     * @param string|null $subpagepattern Sub page pattern.
     * @param object $page Page object.
     * @return object Block instance.
     */
    public function create_block_on_page(
        $blockname,
        $region,
        $weight,
        $showinsubcontexts,
        $pagetypepattern = null,
        $subpagepattern = null,
        $page = ''
    ) {

        global $DB;

        if (empty($pagetypepattern)) {
            $pagetypepattern = $this->page->pagetype;
        }

        $blockinstance = new \stdClass();
        $blockinstance->blockname = $blockname;
        $blockinstance->parentcontextid = $page->context->id;
        $blockinstance->showinsubcontexts = !empty($showinsubcontexts);
        $blockinstance->pagetypepattern = $pagetypepattern;
        $blockinstance->subpagepattern = $subpagepattern;
        $blockinstance->defaultregion = $region;
        $blockinstance->defaultweight = $weight;
        $blockinstance->configdata = '';
        $blockinstance->timecreated = time();
        $blockinstance->timemodified = $blockinstance->timecreated;
        $blockinstance->id = $DB->insert_record('block_instances', $blockinstance);

        // Ensure the block context is created.
        \context_block::instance($blockinstance->id);

        // If the new instance was created, allow it to do additional setup.
        if ($block = block_instance($blockname, $blockinstance)) {
            $block->instance_create();
        }

        return $block;
    }

    /**
     * Retrieves the categories and blocks from the edwiser page builder.
     *
     * This function fetches the blocks from the database, groups them by category,
     * and returns the categories and blocks in a structured format.
     *
     * @return array An array containing blockscontext and categoriescontext.
     */
    public function get_edwblocks_categories_with_blocks() {
        $reftable = $this->get_block_table_name();
        // Retrieve all records from the block table.
        $blocks = $this->get_record_from_table($reftable);

        $uncategorizedblocks = [];
        $categorieswithblocks = [];
        foreach ($blocks as $block) {
            // Check if the current block is an HTML block.
            if ($block->title == "html") {
                $key = "htmlblock";
                $block->categories = "htmlblock";
            }

            // Check if the current block is an import block.
            if ($block->title == "importblock") {
                $key = "importblock";
                $block->categories = "importblock";
            }

            // Check if the block title is courses, categories, or coursesncategories.
            if ($block->title == "courses" || $block->title == "categories" || $block->title == "coursesncategories") {
                // If the block type is block, change it to dynamic.
                if ($block->type == "block") {
                    $block->type = 'dynamic';
                }
            }

            // Construct the key for the category.
            $key = $block->type . ($block->categories ?: 'Others');
            $categorytitle = $block->categories ?: 'Others';
            $categoryvalue = $block->categories
                ? preg_replace('/[^A-Za-z0-9_]/', '', strtolower(str_replace(' ', '_', $block->categories)))
                : 'others';
            $category = [
                'categorytitle' => $categorytitle,
                'categoryvalue' => $categoryvalue,
                'type' => $block->type,
                'blocks' => [],
            ];

            // Check if the category already exists in categorieswithblocks or uncategorizedblocks.
            if (isset($categorieswithblocks[$key])) {
                // Add block to existing category.
                $categorieswithblocks[$key]['blocks'][] = $block;
            } else if (isset($uncategorizedblocks[$key])) {
                // Add block to existing uncategorized category.
                $uncategorizedblocks[$key]['blocks'][] = $block;
            } else {
                // Add block to new category.
                $category['blocks'][] = $block;
                if ($category['categorytitle'] == 'Others') {
                    // Add new uncategorized category.
                    $uncategorizedblocks[$key] = $category;
                } else {
                    // Add new categorized category.
                    $categorieswithblocks[$key] = $category;
                }
            }
        }

        // Sort categorized blocks alphabetically by category title.
        usort($categorieswithblocks, function ($a, $b) {
            return strcasecmp($a['categorytitle'], $b['categorytitle']);
        });
        // Merge categorized and uncategorized blocks.
        $categories = array_merge(array_values($categorieswithblocks), array_values($uncategorizedblocks));

        $blockscontext = [];
        $categoriescontext = [];

        // Prepare data for output.
        foreach ($categories as $category) {
            // Add category to blocks context.
            $blockscontext[] = $category;
            if ($category['categorytitle'] != 'htmlblock' && $category['categorytitle'] != 'importblock') {
                $categoriescontext[] = [
                    'categorytitle' => $category['categorytitle'],
                    'categoryvalue' => $category['categoryvalue'],
                    'type' => $category['type'],
                ];
            }
        }

        // Return the prepared data.
        return [
            "blockscontext" => $blockscontext,
            "categoriescontext" => $categoriescontext,
        ];
    }
}
