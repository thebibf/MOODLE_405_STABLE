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
 * Scan media folder for all files to display in media modal.
 *
 * @package   local_edwiserpagebuilder
 * @copyright (c) 2023 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');
require_login();
require_sesskey();
require_capability('local/edwiserpagebuilder:epb_can_view_page', context_system::instance());

// Scan media folder for all files to display in media modal.

define('MEDIA_FOLDER', 'media');

/**
 * Sanitize a file path by removing dangerous characters and sequences.
 *
 * @param string $path The path to sanitize.
 * @return string The sanitized real path, or empty string if invalid.
 */
function sanitize_path($path) {
    // Sanitize, remove double dot .. and remove get parameters if any.
    $path = preg_replace(
        '@/+@',
        DIRECTORY_SEPARATOR,
        preg_replace('@\?.*$@', '', preg_replace('@\.{2,}@', '', preg_replace('@[^\/\\a-zA-Z0-9\-\._]@', '', $path)))
    );

    return realpath($path) ?: '';
}

$mediapath = optional_param('mediaPath', '', PARAM_PATH);
if ($mediapath !== '' && ($path = sanitize_path(substr($mediapath, 0, 256)))) {
    define('UPLOAD_PATH', MEDIA_FOLDER . DIRECTORY_SEPARATOR . $path);
} else {
    define('UPLOAD_PATH', MEDIA_FOLDER);
}

$scandir = __DIR__ . DIRECTORY_SEPARATOR . UPLOAD_PATH;

// Run the recursive function.
// This function scans the files folder recursively, and builds a large array.

$scan = function ($dir) use ($scandir, &$scan) {
    $files = [];

    // Is there actually such a folder/file?

    if (file_exists($dir)) {
        foreach (scandir($dir) as $f) {
            if (! $f || $f[0] == '.') {
                continue; // Ignore hidden files.
            }

            if (is_dir($dir . '/' . $f)) {
                // The path is a folder.

                $files[] = [
                    'name'  => $f,
                    'type'  => 'folder',
                    'path'  => str_replace($scandir, '', $dir) . '/' . $f,
                    'items' => $scan($dir . '/' . $f), // Recursively get the contents of the folder.
                ];
            } else {
                // It is a file.

                $files[] = [
                    'name' => $f,
                    'type' => 'file',
                    'path' => str_replace($scandir, '', $dir) . '/' . $f,
                    'size' => filesize($dir . '/' . $f), // Gets the size of this file.
                ];
            }
        }
    }

    return $files;
};

$response = $scan($scandir);

// Output the directory listing as JSON.

header('Content-type: application/json');

echo json_encode([
    'name'  => '',
    'type'  => 'folder',
    'path'  => '',
    'items' => $response,
]);
