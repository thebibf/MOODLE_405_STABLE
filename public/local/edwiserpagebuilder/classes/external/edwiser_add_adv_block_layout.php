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
 * Trait for edwiser_fetch_layout_list service
 * @package   local_edwiserpagebuilder
 * @copyright (c) 2022 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author    Gourav Govande
 */

namespace local_edwiserpagebuilder\external;

use external_single_structure;
use external_function_parameters;
use external_value;
use context_system;
use context_course;
use context_module;
use stdClass;

/**
 * Service definition for create new form
 * @copyright (c) 2022 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
trait edwiser_add_adv_block_layout {
    /**
     * Returns the functional parameter for adding advanced block layout.
     * @return external_function_parameters Functional parameters
     */
    public static function edwiser_add_adv_block_layout_parameters() {
        return new external_function_parameters(
            [
                'layout' => new external_value(PARAM_RAW, 'Current page url'),
                'pagetype' => new external_value(PARAM_RAW, 'current page context'),
                'region' => new external_value(PARAM_RAW, 'blockpagetype'),
                'subpagetypepattern' => new external_value(PARAM_RAW, 'subpagetypepattern'),
                'courseid' => new external_value(PARAM_RAW, 'courseid'),
                'contextinstanceid' => new external_value(PARAM_RAW, 'contextInstanceId'),
            ]
        );
    }

    /**
     * Add advanced block layout to a page.
     *
     * @param string $layout Layout name
     * @param string $pagetype Page type
     * @param string $region Block region
     * @param string $subpagetypepattern Subpage type pattern
     * @param string $courseid Course id
     * @param string $contextinstanceid Context instance id
     * @return array Status and message
     */
    public static function edwiser_add_adv_block_layout(
        $layout,
        $pagetype,
        $region,
        $subpagetypepattern,
        $courseid,
        $contextinstanceid
    ) {
        global $DB, $CFG;
        $context = context_system::instance();
        self::validate_context($context);
        // Allow users who can edit blocks — matches old behaviour where any user
        // with block-editing access could add layouts.
        if (
            !has_capability('moodle/site:manageblocks', $context) &&
            !has_capability('moodle/block:edit', $context) &&
            !has_capability('moodle/my:manageblocks', $context)
        ) {
            require_capability('moodle/block:edit', $context);
        }

        $blockname = 'edwiseradvancedblock';
        $blockinstancetable = 'block_instances';
        require_once($CFG->dirroot . "/local/edwiserpagebuilder/lib.php");
        $responsemsg = "something went wrong please try again";
        $responsestatus = false;
        $bm = new \local_edwiserpagebuilder\block_handler();
        $blocks = $bm->fetch_blocks_list(["title" => $layout, "type" => "blocklayout"]);
        $iscoursepage = false;
        if (!empty($blocks) && $pagetype != '' && $region != '') {
            $page = new \moodle_page();
            $page->set_context(context_system::instance());
            $pagetypepattern = $pagetype;
            if (strpos($pagetypepattern, 'course-view') === 0) {
                $page->set_context(context_course::instance($courseid));
                $pagetypepattern = 'course-view-*';
                $iscoursepage = true;
            }
            if (preg_match('/^mod-.*-/', $pagetypepattern)) {
                $page->set_context(context_module::instance($contextinstanceid));
                $pagetypelist = generate_page_type_patterns($pagetype, null, $page->context);
                // Only go for the first if the pagetype is not a valid option.
                if (is_array($pagetypelist) && !array_key_exists($pagetypepattern, $pagetypelist)) {
                    $pagetypepattern = key($pagetypelist);
                }
                $iscoursepage = true;
            }
            $pagetype = $pagetypepattern;
            if ($iscoursepage) {
                $DB->delete_records($blockinstancetable, [
                    "parentcontextid" => $page->context->id,
                    "pagetypepattern" => $pagetype,
                    "defaultregion" => $region,
                ]);
            } else if ($pagetype == 'site-index') {
                $DB->delete_records($blockinstancetable, [
                    "pagetypepattern" => $pagetype,
                    "defaultregion" => $region,
                ]);
            } else {
                $DB->delete_records($blockinstancetable, [
                    "pagetypepattern" => $pagetype,
                    "subpagepattern" => $subpagetypepattern,
                    "defaultregion" => $region,
                ]);
            }
            $count = 0;
            foreach ($blocks as $block) {
                $blockcontent = json_decode($block->content);
                foreach ($blockcontent as $content) {
                    $count  = $count + 1;
                    $page->blocks->add_region($region);
                    if (property_exists($content, 'contenttype')) {
                        if ($content->contenttype == 'moodleblock' && property_exists($content, 'blockname')) {
                            $page->blocks->add_block(
                                $content->blockname,
                                $region,
                                $count,
                                false,
                                $pagetype,
                                $subpagetypepattern
                            );
                            $responsemsg = "blocks added sucessfully";
                            $responsestatus = true;
                        }
                        if ($content->contenttype == 'advancedblock') {
                            $page->blocks->add_block(
                                $blockname,
                                $region,
                                $count,
                                false,
                                $pagetype,
                                $subpagetypepattern
                            );
                            $blockrecord = $DB->get_record($blockinstancetable, [
                                "blockname" => $blockname,
                                "parentcontextid" => $page->context->id,
                                "pagetypepattern" => $pagetype,
                                "subpagepattern" => $subpagetypepattern,
                                "defaultregion" => $region,
                                "defaultweight" => $count,
                            ], '*');
                            $advancedblockcontent = $DB->get_record(
                                "local_edwiserpagebuilder_blocks",
                                ["title" => $content->blockname],
                                '*'
                            );
                            $advancedblockcontent = json_decode($advancedblockcontent->content);
                            $dataobj = new stdClass();
                            $dataobj->html = [
                                "text" => $advancedblockcontent->html,
                                "format" => 1,
                            ];

                            $dataobj->css = [
                                "text" => $advancedblockcontent->css,
                                "format" => 1,
                            ];

                            $dataobj->js = [
                                "text" => $advancedblockcontent->js,
                                "format" => 1,
                            ];
                            try {
                                $instance = block_instance($blockrecord->blockname, $blockrecord);
                                $instance->instance_config_save($dataobj, false);
                            } catch (\Exception $e) {
                                echo("something went wrong try again");
                            }
                            $responsemsg = "blocks added sucessfully";
                            $responsestatus = true;
                        }
                    } else {
                        $page->blocks->add_block(
                            $blockname,
                            $region,
                            $count,
                            false,
                            $pagetype,
                            $subpagetypepattern
                        );
                        $blockrecord = $DB->get_record($blockinstancetable, [
                            "blockname" => $blockname,
                            "parentcontextid" => $page->context->id,
                            "pagetypepattern" => $pagetype,
                            "subpagepattern" => $subpagetypepattern,
                            "defaultregion" => $region,
                            "defaultweight" => $count,
                        ], '*');
                        $content->html = preg_replace('/\sonload\s*=\s*["\'][^"\']*["\']/', '', $content->html);
                        $dataobj = new stdClass();
                        $dataobj->html = [
                            "text" => $content->html,
                            "format" => 1,
                        ];

                        $dataobj->css = [
                            "text" => $content->css,
                            "format" => 1,
                        ];

                        $dataobj->js = [
                            "text" => $content->js,
                            "format" => 1,
                        ];
                        try {
                            $instance = block_instance($blockrecord->blockname, $blockrecord);
                            $instance->instance_config_save($dataobj, false);
                        } catch (\Exception $e) {
                            echo("something went wrong try again");
                        }
                        $responsemsg = "blocks added sucessfully";
                        $responsestatus = true;
                    }
                }
            }
        }

        return [
            'status' => $responsestatus,
            'msg' => $responsemsg,
        ];
    }

    /**
     * Returns description of method result value.
     * @return external_single_structure return structure
     */
    public static function edwiser_add_adv_block_layout_returns() {
        return new \external_single_structure(
            [
                'status' => new external_value(PARAM_BOOL, 'Boolean success or fails.'),
                'msg'    => new external_value(PARAM_TEXT, 'Error or success message.'),
            ]
        );
    }
}
