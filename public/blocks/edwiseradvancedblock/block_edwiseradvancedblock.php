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
 * Block definition for block_edwiseradvancedblock.
 *
 * @package   block_edwiseradvancedblock
 * @copyright (c) 2022 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author    Gourav Govande
 */
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . "/blocks/edwiseradvancedblock/lib.php");

/**
 * Class block_edwiseradvancedblock for the Edwiser Advanced Block.
 */
class block_edwiseradvancedblock extends block_base {
    /**
     * Initialise the block.
     */
    public function init() {
        $this->title = get_string('edwiseradvancedblock', 'block_edwiseradvancedblock');
    }

    /**
     * Get the block content.
     *
     * @return stdClass The block content object.
     */
    public function get_content() {
        global $OUTPUT, $CFG, $USER;
        $this->content = new stdClass();

        $templatecontext = [];

        if (!edwb_is_plugin_available('local_edwiserpagebuilder')) {
            $this->content->text = "";
            if (is_siteadmin()) {
                $this->content->text = $OUTPUT->render_from_template(
                    'block_edwiseradvancedblock/plugin_not_available',
                    $templatecontext
                );
            }
            return $this->content;
        }

        require_once($CFG->dirroot . "/local/edwiserpagebuilder/lib.php");
        if (!is_null($this->config)) {
            // Handling Moodle's "Log in as" functionality for content formatting.
            // When an admin is logged in as another user, format_text() removes
            // essential HTML elements. To address this, we selectively format only
            // multilang blocks and shortcodes, leaving other HTML intact.

            // By using 'realuser' we can determine whether user is original or
            // admin logged in as another user.
            if (!empty($USER->realuser)) {
                $content = shortcode_replace_on_disableplugin($this->config->html['text']);

                // Process shortcode formats.
                $pattern = '/\[edwiser[\w-]*(?:\s+[^]]+)?\]/';
                preg_match_all($pattern, $content, $matches);

                foreach ($matches[0] as $match) {
                    $formatted = format_text($match, FORMAT_HTML, ["noclean" => true]);
                    $content = str_replace($match, $formatted, $content);
                }

                // Process multilang formats.
                $multilangpatterns = [
                    '/\{mlang\s+[a-z]+\}.*?\{mlang\}/is',
                ];

                foreach ($multilangpatterns as $pattern) {
                    preg_match_all($pattern, $content, $matches);
                    foreach ($matches[0] as $match) {
                        $formatted = format_text($match, FORMAT_HTML, ["noclean" => true]);
                        $content = str_replace($match, $formatted, $content);
                    }
                }

                $templatecontext['blockhtml'] = pre_process_html($content, $this->instance->id);
            } else {
                $content = shortcode_replace_on_disableplugin($this->config->html['text']);

                $formatted = format_text($content, FORMAT_HTML, ["noclean" => true]);
                $templatecontext['blockhtml'] = pre_process_html($formatted, $this->instance->id);
            }

            $templatecontext['blockcss'] = pre_process_css($this->config->css['text'], $this->instance->id);
            $templatecontext['blockjs'] = pre_process_html($this->config->js['text'], $this->instance->id);

            $bodyhaslimitedwidth = strpos($OUTPUT->body_attributes([]), "limitedwidth");
            $bodyhasrtlclass = strpos($OUTPUT->body_attributes([]), "dir-rtl");
            $instanceregion = $this->instance->defaultregion;
            if (
                $instanceregion != "full-width-top"
                && $instanceregion != "full-bottom"
                && $bodyhaslimitedwidth != false
            ) {
                // Append our class to class attribute.
                $templatecontext['haslimitedwidth'] = ' edw-limitedwidth-block';
            }
            if ($bodyhasrtlclass != false) {
                // Append our class to class attribute.
                $templatecontext['hasrtlclass'] = ' edw-rtl-block';
            }
        }

        $this->content->text = "";
        $templatecontext['blockid'] = "#inst" . $this->instance->id;
        $context = context_block::instance($this->instance->id);
        if (has_capability('block/edwiseradvancedblock:cancustomizelive', $context)) {
            $templatecontext['liveeditorlink'] = local_edwiserpagebuilder_customizer_button($this->instance->id);
        }

        $this->content->text .= $OUTPUT->render_from_template('block_edwiseradvancedblock/blockcontent', $templatecontext);

        return $this->content;
    }

    /**
     * Allow multiple instances of this block.
     *
     * @return bool True to allow multiple instances.
     */
    public function instance_allow_multiple() {
        return true;
    }

    /**
     * Whether this block has a configuration page.
     *
     * @return bool True if has config.
     */
    public function has_config() {
        return true;
    }

    /**
     * Whether to hide the block header.
     *
     * @return bool True to hide the header.
     */
    public function hide_header() {
        return true;
    }

    /**
     * Define the applicable formats for this block.
     *
     * @return array The applicable formats.
     */
    public function applicable_formats() {

        $allow = [];
        if (!edwb_is_plugin_available('local_edwiserpagebuilder')) {
            $allow['all'] = false;
            $allow['admin-index'] = true;

            return $allow;
        }
        $allow['all'] = true;
        return $allow;
    }
    /**
     * Do any additional initialization you may need at the time a new block instance is created
     * @return boolean
     */
    public function instance_create() {
        $blockname = optional_param('section', null, PARAM_RAW);

        $config = new stdClass();

        if (!$blockname) {
            $blockname = "html";
        }

        if (edwb_is_plugin_available('local_edwiserpagebuilder')) {
            $bm = new \local_edwiserpagebuilder\block_handler();
            $blockconfig = $bm->get_data_with_title($blockname, $bm->get_block_table_name());

            if ($blockconfig) {
                $blockconfig = json_decode($blockconfig->content);
                $config->html['text'] = $blockconfig->html;
                $config->css['text'] = $blockconfig->css;
                $config->js['text'] = $blockconfig->js;
            }
        }

        parent::instance_config_save($config, $nolongerused = false);
        return true;
    }
}
