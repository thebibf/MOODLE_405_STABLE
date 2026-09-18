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
 * Editor page loader for VvvebJs page builder.
 *
 * @package   local_edwiserpagebuilder
 * @copyright (c) 2023 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$html = file_get_contents('editor.html');

// Search for html files in demo and my-pages folders.
$htmlfiles = array_merge(glob('my-pages/*.html'), glob('demo/*\/*.html'), glob('demo/*.html'));
$files = '';
foreach ($htmlfiles as $file) {
    // Skip template files.
    if (in_array($file, ['new-page-blank-template.html', 'editor.html'])) {
        continue;
    }
    $pathinfo = pathinfo($file);
    $filename = $pathinfo['filename'];
    $folder = preg_replace('@/.+?$@', '', $pathinfo['dirname']);
    $subfolder = preg_replace('@^.+?/@', '', $pathinfo['dirname']);
    if ($filename == 'index' && $subfolder) {
        $filename = $subfolder;
    }
    $url = $pathinfo['dirname'] . '/' . $pathinfo['basename'];
    $name = $filename;
    $title = ucfirst($name);

    $files .= "{name:'$name', file:'$file', title:'$title',  url: '$url', folder:'$folder'},";
}

// Replace files list from html with the dynamic list from demo folder.
$html = str_replace('= defaultPages;', " = [$files];", $html);

echo $html;
