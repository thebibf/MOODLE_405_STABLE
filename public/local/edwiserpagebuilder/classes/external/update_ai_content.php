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
 * Trait for update_ai_content web service.
 *
 * Saves AI-generated HTML/CSS/JS to a block instance's configdata field
 * in the block_instances table.
 *
 * @package   local_edwiserpagebuilder
 * @copyright (c) 2026 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_edwiserpagebuilder\external;

use external_function_parameters;
use external_single_structure;
use external_value;

/**
 * Trait for update_ai_content service.
 *
 * @package local_edwiserpagebuilder
 */
trait update_ai_content {
    /**
     * Parameter definition for update_ai_content.
     *
     * @return external_function_parameters
     */
    public static function update_ai_content_parameters(): external_function_parameters {
        return new external_function_parameters([
            'instanceid' => new external_value(PARAM_INT, 'The block instance ID'),
            'html' => new external_value(PARAM_RAW, 'The HTML content', VALUE_DEFAULT, ''),
            'css' => new external_value(PARAM_RAW, 'The CSS content', VALUE_DEFAULT, ''),
            'js' => new external_value(PARAM_RAW, 'The JavaScript content', VALUE_DEFAULT, ''),
        ]);
    }

    /**
     * Save AI-generated content to a block instance's configdata.
     *
     * @param int $instanceid The block instance ID.
     * @param string $html The HTML content.
     * @param string $css The CSS content.
     * @param string $js The JavaScript content.
     * @return array Result with success status and message.
     */
    public static function update_ai_content(int $instanceid, string $html = '', string $css = '', string $js = ''): array {
        global $DB;

        $params = self::validate_parameters(self::update_ai_content_parameters(), [
            'instanceid' => $instanceid,
            'html' => $html,
            'css' => $css,
            'js' => $js,
        ]);

        // Get the block instance.
        $blockinstance = $DB->get_record('block_instances', ['id' => $params['instanceid']], '*', MUST_EXIST);

        // Get context and validate.
        $context = \context_block::instance($params['instanceid']);
        self::validate_context($context);

        require_capability('local/edwiserpagebuilder:epb_can_view_page', $context);

        try {
            // Get current configdata.
            $configdata = unserialize(base64_decode($blockinstance->configdata));
            if ($configdata === false) {
                $configdata = new \stdClass();
            }

            // Update content using array format for compatibility with edwiseradvancedblock.
            $configdata->html = ['text' => $params['html'], 'format' => FORMAT_HTML];
            $configdata->css = ['text' => $params['css'], 'format' => FORMAT_HTML];
            $configdata->js = ['text' => $params['js'], 'format' => FORMAT_HTML];

            // Save the updated configdata.
            $DB->set_field(
                'block_instances',
                'configdata',
                base64_encode(serialize($configdata)),
                ['id' => $params['instanceid']]
            );

            return [
                'success' => true,
                'message' => \get_string('ai_update_content_success', 'local_edwiserpagebuilder'),
            ];
        } catch (\Exception $e) {
            \debugging('update_ai_content error: ' . $e->getMessage(), DEBUG_DEVELOPER);
            return [
                'success' => false,
                'message' => \get_string('ai_update_content_failed', 'local_edwiserpagebuilder'),
            ];
        }
    }

    /**
     * Return definition for update_ai_content.
     *
     * @return external_single_structure
     */
    public static function update_ai_content_returns(): external_single_structure {
        return new external_single_structure([
            'success' => new external_value(PARAM_BOOL, 'Whether the update succeeded'),
            'message' => new external_value(PARAM_TEXT, 'Result message'),
        ]);
    }
}
