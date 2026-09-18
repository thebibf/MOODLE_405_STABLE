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
 * Library functions for block_edwiseradvancedblock.
 *
 * @package   block_edwiseradvancedblock
 * @copyright (c) 2022 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author    Gourav Govande
 */
defined('MOODLE_INTERNAL') || die();

global $CFG;
if (edwb_is_plugin_available("local_edwiserpagebuilder")) {
    require_once($CFG->dirroot . "/local/edwiserpagebuilder/lib.php");
    define_cdn_constants();
}

/**
 * Get the configuration for a block instance.
 *
 * @param int $instanceid The block instance ID.
 * @return object|null The block instance config or null.
 */
function block_edwiseradvancedblock_get_config($instanceid) {
    global $DB;
    if (is_null($instanceid)  || !is_numeric($instanceid)) {
        return null;
    }

    $blockrecord = $DB->get_record('block_instances', ['id' => $instanceid]);
    if (!$blockrecord) {
        return null;
    }

    $instance = block_instance($blockrecord->blockname, $blockrecord);

    return $instance->config;
}

/**
 * Wrap CSS with instance-specific selector and compile SCSS.
 *
 * @param string $css The CSS content.
 * @param int $instid The block instance ID.
 * @return string The compiled CSS.
 */
function get_wrapped_css($css, $instid) {
    global $CFG;
    if ($css == "") {
        return "";
    }

    $css = str_replace(".m-0.p-50.editingbody", ".blockcontent", $css);

    try {
        require_once($CFG->libdir . "/classes/scss.php");

        $scss = "#inst" . $instid . "{" . htmlspecialchars_decode($css) . "}";
        $scssprocessor = new core_scss();
        $scssprocessor->append_raw_scss($scss);
        $css = $scssprocessor->to_css();
    } catch (Exception $e) {
        return "";
    }

    return $css;
}

/**
 * Pre-process HTML content for a block instance.
 *
 * @param string $html The HTML content.
 * @param int $instanceid The block instance ID.
 * @return string The processed HTML.
 */
function pre_process_html($html, $instanceid) {

    // Replacing the CDN URL.
    $html = replace_cdn_url($html);
    $html = replace_instance_id($html, $instanceid);
    return $html;
}

/**
 * Pre-process CSS content for a block instance.
 *
 * @param string $css The CSS content.
 * @param int $instid The block instance ID.
 * @return string The processed and wrapped CSS.
 */
function pre_process_css($css, $instid) {

    $css = replace_cdn_url($css);
    $css = replace_instance_id($css, $instid);
    return get_wrapped_css($css, $instid);
}
/**
 * Replace instance ID placeholder in content.
 *
 * @param string $content The content string.
 * @param int $instanceid The block instance ID.
 * @return string The content with replaced instance ID.
 */
function replace_instance_id($content, $instanceid) {
    return str_replace("[[inst]]", $instanceid, $content);
}

/**
 * Replace CDN URL placeholder with actual CDN URL.
 *
 * @param string $content The content string.
 * @return string The content with replaced CDN URL.
 */
function replace_cdn_url($content) {
    // Replacing the CDN URL.
    return str_replace("{{>cdnurl}}", CDNIMAGES, $content);
}

/**
 * Revert CDN URL back to placeholder.
 *
 * @param string $content The content string.
 * @return string The content with reverted CDN URL.
 */
function revert_cdn_url($content) {
    // Replacing back the CDN URL with {{>cdnurl}}.
    return str_replace(CDNIMAGES, "{{>cdnurl}}", $content);
}

/**
 * Replace shortcodes with a message when the plugin is disabled.
 *
 * @param string $content The content string.
 * @return string The content with replaced shortcodes.
 */
function shortcode_replace_on_disableplugin($content) {
    global $OUTPUT, $CFG, $PAGE;
    // Process shortcode formats.
    $pattern = '/\[edwiser[\w-]*(?:\s+[^]]+)?\]/';
    if (preg_match($pattern, $content, $match)) {
        // Check if edwiserpbf filter is enabled.
        if ((filter_get_active_state('edwiserpbf') != 1) && is_siteadmin() && $PAGE->user_is_editing()) {
            $message = $OUTPUT->render_from_template(
                'block_edwiseradvancedblock/edwiserpbf_disabled_message',
                [
                    'wwwroot' => $CFG->wwwroot,
                    'edwiserpbfavailable' => edwb_is_plugin_available("filter_edwiserpbf"),
                ]
            );

            // Replace only the first occurrence with the message.
            $content = preg_replace(
                $pattern,
                $message,
                $content,
                1
            );
        }
    }

    return $content;
}

/**
 * Check if a plugin is available.
 *
 * @param string $component The plugin component name.
 * @return bool True if the plugin is available.
 */
function edwb_is_plugin_available($component) {

    [$type, $name] = core_component::normalize_component($component);

    $dir = \core_component::get_plugin_directory($type, $name);
    if (!file_exists($dir)) {
        return false;
    }
    return true;
}
