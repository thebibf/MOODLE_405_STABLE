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
 * Save handler for VvvebJs page builder.
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

// 2 Megabytes max html file size.
define('MAX_FILE_LIMIT', 1024 * 1024 * 2);
// Check if saved html contains php tag and don't save if not allowed.
define('ALLOW_PHP', false);
// Load urls only from allowed websites for oembed.
define('ALLOWED_OEMBED_DOMAINS', [
    'https://www.youtube.com/',
    'https://www.vimeo.com/',
    'https://www.x.com/',
    'https://x.com/',
    'https://publish.twitter.com/',
    'https://www.twitter.com/',
    'https://www.reddit.com/',
]);

/**
 * Sanitize a file name and restrict to allowed extension.
 *
 * @param string $file The file path to sanitize.
 * @param string $allowedextension The allowed file extension.
 * @return string The sanitized file path.
 */
function sanitize_file_name($file, $allowedextension = 'html') {
    $basename = basename($file);
    $disallow = ['.htaccess', 'passwd'];
    if (in_array($basename, $disallow)) {
        show_error(get_string('error_filenamenotallowed', 'local_edwiserpagebuilder'));
        return '';
    }

    // Sanitize, remove double dot and remove get parameters if any.
    $file = preg_replace('@\?.*$@', '', preg_replace('@\.{2,}@', '', preg_replace('@[^\/\\a-zA-Z0-9\-\._]@', '', $file)));

    if ($file) {
        $file = __DIR__ . DIRECTORY_SEPARATOR . $file;
    } else {
        return '';
    }

    // Allow only specified extension.
    if ($allowedextension) {
        $file = preg_replace('/\.[^.]+$/', '', $file) . ".$allowedextension";
    }
    return $file;
}

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
 * Check if a URL is from an allowed oembed domain.
 *
 * @param string $url The URL to validate.
 * @return bool True if the URL is allowed.
 */
function valid_oembed_url($url) {
    foreach (ALLOWED_OEMBED_DOMAINS as $domain) {
        if (strpos($url, $domain) === 0) {
            return true;
        }
    }

    return false;
}

$html   = '';
$file   = '';
$action = optional_param('action', '', PARAM_ALPHA);

$starttemplateurl = optional_param('startTemplateUrl', '', PARAM_PATH);
if ($starttemplateurl !== '') {
    $starttemplateurl = sanitize_file_name($starttemplateurl);
    $html = '';
    if ($starttemplateurl) {
        $html = file_get_contents($starttemplateurl);
    }
} else {
    $rawhtml = optional_param('html', '', PARAM_RAW);
    if ($rawhtml !== '') {
        $html = substr($rawhtml, 0, MAX_FILE_LIMIT);
        if (!ALLOW_PHP) {
            if (preg_match('@<\?php|<\? |<\?=|<\s*script\s*language\s*=\s*"\s*php\s*"\s*>@', $html)) {
                show_error(get_string('error_phpnotallowed', 'local_edwiserpagebuilder'));
            }
        }
    }
}

$postfile = optional_param('file', '', PARAM_PATH);
if ($postfile !== '') {
    $file = sanitize_file_name($postfile);
}

if ($action) {
    // File manager actions, delete and rename.
    switch ($action) {
        case 'rename':
            $rawnewfile = optional_param('newfile', '', PARAM_PATH);
            $newfile = sanitize_file_name($rawnewfile);
            if ($file && $newfile) {
                if (rename($file, $newfile)) {
                    echo "File '$file' renamed to '$newfile'";
                } else {
                    show_error(get_string(
                        'error_filerenamefailed',
                        'local_edwiserpagebuilder',
                        (object)['oldfile' => $file, 'newfile' => $newfile]
                    ));
                }
            }
            break;
        case 'delete':
            if ($file) {
                if (unlink($file)) {
                    echo get_string('success_filedeleted', 'local_edwiserpagebuilder');
                } else {
                    show_error(get_string('error_filedeletefailed', 'local_edwiserpagebuilder', $file));
                }
            }
            break;
        case 'saveReusable':
            // Block or section.
            $type = optional_param('type', '', PARAM_ALPHA);
            $name = optional_param('name', '', PARAM_ALPHANUMEXT);
            $html = optional_param('html', '', PARAM_RAW);

            if ($type && $name && $html) {
                $file = sanitize_file_name("$type/$name");
                if ($file) {
                    $dir = dirname($file);
                    if (!is_dir($dir)) {
                        echo "$dir folder does not exist\n";
                        if (mkdir($dir, 0777, true)) {
                            echo "$dir folder was created\n";
                        } else {
                            show_error(get_string('error_foldercreatefailed', 'local_edwiserpagebuilder', $dir));
                        }
                    }

                    if (file_put_contents($file, $html)) {
                        echo get_string('success_filesaved', 'local_edwiserpagebuilder');
                    } else {
                        show_error(get_string('error_filesavefailed', 'local_edwiserpagebuilder', $file));
                    }
                } else {
                    show_error(get_string('error_invalidfilename', 'local_edwiserpagebuilder'));
                }
            } else {
                show_error(get_string('error_missingreusabledata', 'local_edwiserpagebuilder'));
            }
            break;
        case 'oembedProxy':
            $url = optional_param('url', '', PARAM_URL);
            if (valid_oembed_url($url)) {
                $useragent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Moodle';
                $options = [
                    'http' => [
                        'method' => "GET",
                        'header' => 'User-Agent: ' . $useragent . "\r\n",
                    ],
                ];
                $context = stream_context_create($options);
                header('Content-Type: application/json');
                echo file_get_contents($url, false, $context);
            } else {
                show_error(get_string('error_invalidurl', 'local_edwiserpagebuilder'));
            }
            break;
        default:
            show_error(get_string('error_invalidaction', 'local_edwiserpagebuilder', $action));
    }
} else {
    // Save page.
    if ($html) {
        if ($file) {
            $dir = dirname($file);
            if (!is_dir($dir)) {
                echo "$dir folder does not exist\n";
                if (mkdir($dir, 0777, true)) {
                    echo "$dir folder was created\n";
                } else {
                    show_error(get_string('error_foldercreatefailed', 'local_edwiserpagebuilder', $dir));
                }
            }

            if (file_put_contents($file, $html)) {
                echo get_string('success_filesaved', 'local_edwiserpagebuilder');
            } else {
                show_error(get_string('error_filesavefailed', 'local_edwiserpagebuilder', $file));
            }
        } else {
            show_error(get_string('error_filenameempty', 'local_edwiserpagebuilder'));
        }
    } else {
        show_error(get_string('error_htmlempty', 'local_edwiserpagebuilder'));
    }
}
