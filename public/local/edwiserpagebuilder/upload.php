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
 * Image upload handler for VvvebJs page builder.
 *
 * This script is used by image upload input to save the image on the
 * server and return the image url to be set as image src attribute.
 *
 * Copyright 2017 Ziadin Givan
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * https://github.com/givanz/VvvebJs
 *
 * @package   local_edwiserpagebuilder
 * @copyright (c) 2023 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');
require_login();
require_sesskey();
require_capability('local/edwiserpagebuilder:epb_can_manage_page', context_system::instance());

$uploaddenyextensions  = ['php'];
$uploadallowextensions = ['ico', 'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];

/**
 * Show an error and terminate execution.
 *
 * @param string $error The error message.
 */
function show_error($error) {
    http_response_code(500);
    die($error);
}

/**
 * Sanitize a file name for safe filesystem usage.
 *
 * @param string $file The file path to sanitize.
 * @return string The sanitized file path.
 */
function sanitize_file_name($file) {
    $disallow = ['.htaccess', 'passwd'];
    $file = str_replace($disallow, '', $file);

    // Sanitize, remove double dot and remove get parameters if any.
    $file = preg_replace('@\?.*$@', '', preg_replace('@\.{2,}@', '', preg_replace('@[^\/\\a-zA-Z0-9\-\._]@', '', $file)));

    return $file;
}

define('UPLOAD_FOLDER', __DIR__ . DIRECTORY_SEPARATOR);
$mediapath = optional_param('mediaPath', '', PARAM_PATH);
if ($mediapath !== '') {
    define('UPLOAD_PATH', sanitize_file_name($mediapath) . DIRECTORY_SEPARATOR);
} else {
    define('UPLOAD_PATH', DIRECTORY_SEPARATOR);
}

if (!isset($_FILES['file']) || empty($_FILES['file']['name'])) {
    show_error('No file uploaded!');
}
$filename = sanitize_file_name($_FILES['file']['name']);
if (!$filename) {
    show_error(get_string('error_invalidfilename', 'local_edwiserpagebuilder'));
}

$extension = strtolower(substr($filename, strrpos($filename, '.') + 1));

// Check if extension is on deny list.
if (in_array($extension, $uploaddenyextensions)) {
    show_error(get_string('error_filetypenotallowed', 'local_edwiserpagebuilder', $extension));
}

// Check if extension is on allow list.
if (!in_array($extension, $uploadallowextensions)) {
    show_error(get_string('error_filetypenotallowed', 'local_edwiserpagebuilder', $extension));
}

$destination = UPLOAD_FOLDER . UPLOAD_PATH . DIRECTORY_SEPARATOR . $filename;
move_uploaded_file($_FILES['file']['tmp_name'], $destination);

$onlyfilename = optional_param('onlyFilename', '', PARAM_ALPHA);
if ($onlyfilename !== '') {
    echo $filename;
} else {
    echo UPLOAD_PATH . $filename;
}
