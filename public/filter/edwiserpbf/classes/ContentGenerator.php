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
 * Content generator for Edwiser Page Builder Filter.
 *
 * @package   filter_edwiserpbf
 * @copyright (c) 2022 WisdmLabs (https://wisdmlabs.com/) <support@wisdmlabs.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author    Gourav Govande
 */
namespace filter_edwiserpbf;

use stdClass;
use core_course_category;
use context_system;
use core\url;

define("_CATID", "catid");
define("_LAYOUT", "layout");

// Use the correct table name based on which version of the page builder is installed.
global $DB;
$dbman = $DB->get_manager();
if ($dbman->table_exists(new \xmldb_table('local_edwiserpagebuilder_blklayouts'))) {
    define("EDWISERPBF_LAYOUT_TABLE", "local_edwiserpagebuilder_blklayouts");
} else {
    define("EDWISERPBF_LAYOUT_TABLE", "edw_page_block_layouts");
}

/**
 * Class ContentGenerator for generating course and category content.
 */
class ContentGenerator {
    /** @var int|null Default category ID. */
    private $catiddefault = null;
    /** @var int|null Default layout ID. */
    private $layoutiddefault = null;

    /**
     * Generate courses HTML content based on tags.
     *
     * @param array $tags The filter tags.
     * @return string The generated HTML content.
     */
    public function generate_courses($tags) {
        global $OUTPUT, $CFG, $DB;
        require_once($CFG->dirroot . '/filter/edwiserpbf/lib.php');
        require_once($CFG->dirroot . '/course/renderer.php');
        $tags = $this->validate_tags($tags);

        if ($tags->error) {
            // Returning the error msg.
            return $tags->msg;
        }

        $tags = $tags->tags; // We have $tags->tags after validation.

        $catids = explode(",", $tags[_CATID]);

        $finalcourses = ["courses" => []];

        $count = 0;

        while (count($catids) > $count) {
            $catid = $catids[$count];

            if ($catid == "all" || $catid == 0) {
                $finalcourses['courses'] = get_courses();
                break;
            }

            $catinst = core_course_category::get($catid);
            if (!$catinst->is_uservisible()) {
                continue;
            }

            $children = $catinst->get_all_children_ids();

            foreach ($children as $key => $child) {
                if (!in_array($child, $catids)) {
                    $catids[] = $child;
                }
            }

            $courses = get_courses($catid);
            $finalcourses['courses'] = array_merge($finalcourses['courses'], $courses);
            $count++;
        }

        if (empty($finalcourses['courses'])) {
            return $this->no_data_available();
        }

        $limit = $tags["layout"]->constraints->limit;
        $group = $tags["layout"]->constraints->group;

        $count = 1;
        $courses = [];
        $isactive = true;
        $itemnumber = 0;

        // Check to show navigation or not.
        // -1 to remove system level course.
        if (count($finalcourses['courses']) - 1 > $group) {
            $courses['shownav'] = true;
        }

        foreach ($finalcourses['courses'] as $key => $course) {
            if ($count > $limit && $limit > -1) {
                break;
            }
            if ($course->id == 0 || $course->id == 1) {
                continue;
            }
            if ($isactive) {
                $courses["item"][$itemnumber]["isactive"] = true;
                $isactive = false;
            }
            $corecourselistelement = new \core_course_list_element($course);
            $chelper = new \coursecat_helper();
            $course->courseimage = $this->get_course_image($course);
            $course->courseurl = $CFG->wwwroot . "/course/view.php?id=" . $course->id;

            $course->fullname = format_text($course->fullname, FORMAT_HTML);
            $record = $DB->get_record('course_categories', ['id' => $course->category]);
            if ($record) {
                $course->categoryname = format_text($record->name, FORMAT_HTML);
            }

            $teacher = get_course_teacher($course);
            if ($teacher) {
                $course->teacher = $teacher;
            }
            $epoch = $course->timemodified;
            $dt = new \DateTime("@$epoch");
            $course->lastmodified = $dt->format('d M, Y');

            if (isset($course->startdate)) {
                $epoch = $course->startdate;
                $dt = new \DateTime("@$epoch");
                $course->startdate = $dt->format('d M, Y');

                $startdate['day'] = substr($course->startdate, 0, 2);
                $startdate['month'] = substr($course->startdate, 3, 3);
                $startdate['year'] = substr($course->startdate, 8, 4);

                $course->startdate = $startdate;
            }

            $coursesummary = strip_tags($chelper->get_course_formatted_summary($corecourselistelement));
            $coursesummary = preg_replace('/\n+/', '', $coursesummary);
            $course->shortsummary = strlen($coursesummary) > 80 ? mb_substr($coursesummary, 0, 80) . "..." : $coursesummary;
            $course->shortsummary = format_text($course->shortsummary, FORMAT_HTML);

            $courses["item"][$itemnumber]["courses"][] = $course;
            if ($count % $group == 0) {
                $itemnumber++;
            }

            $count++;
        }

        return $this->generate_content($tags[_LAYOUT], $courses);
    }

    /**
     * Get the course image URL.
     *
     * @param object $course The course object.
     * @return string The course image URL.
     */
    public function get_course_image($course) {
        global $CFG, $OUTPUT;
        $corecourselistelement = new \core_course_list_element($course);
        // Course image.
        foreach ($corecourselistelement->get_course_overviewfiles() as $file) {
            $isimage = $file->is_valid_image();
            if ($CFG->branch >= 501) {
                $courseimage = url::make_pluginfile_url(
                    $file->get_contextid(),
                    $file->get_component(),
                    $file->get_filearea(),
                    null,
                    $file->get_filepath(),
                    $file->get_filename(),
                    !$isimage
                )->out();
            } else {
                $courseimage = file_encode_url(
                    "$CFG->wwwroot/pluginfile.php",
                    '/' . $file->get_contextid() . '/' . $file->get_component() . '/' .
                    $file->get_filearea() . $file->get_filepath() . $file->get_filename(),
                    !$isimage
                );
            }
            if ($isimage) {
                break;
            }
        }
        if (empty($courseimage)) {
            $courseimage = $OUTPUT->get_generated_image_for_id($course->id);
        }

        return $courseimage;
    }

    /**
     * Generate categories HTML content based on tags.
     *
     * @param array $tags The filter tags.
     * @return string The generated HTML content.
     */
    public function generate_categories($tags) {
        global $OUTPUT, $CFG;
        $tags = $this->validate_tags($tags);

        if ($tags->error) {
            // Returning the error msg.
            return $tags->msg;
        }

        $tags = $tags->tags; // We have $tags->tags after validation.

        $limit = $tags["layout"]->constraints->limit;
        $group = $tags["layout"]->constraints->group;
        $count = 1;
        $categories = [];
        $isactive = true;
        $itemnumber = 0;

        $data = core_course_category::get_all();

        if (empty($data)) {
            return $this->no_data_available();
        }

        // Check to show navigation or not.
        if (count($data) > $group) {
            $categories['shownav'] = true;
        }

        foreach ($data as $key => $category) {
            if ($count > $limit && $limit > -1) {
                break;
            }

            if ($isactive) {
                $categories["item"][$itemnumber]["isactive"] = true;
                $isactive = false;
            }

            $obj = new stdClass();

            $obj->id = $category->id;
            $obj->name = format_text($category->name, FORMAT_HTML);
            $obj->parentid = $category->parent;
            $obj->parentname = ($category->parent != 0) ? format_text($data[$category->parent]->name, FORMAT_HTML) : get_string('top', 'filter_edwiserpbf');

            if ($tags['count'] == 'on') {
                $obj->hascount = true;
                $obj->coursecount = core_course_category::get(
                    $category->id,
                    $strictness = MUST_EXIST,
                    $alwaysreturnhidden = true
                )->get_courses_count(['recursive' => true]);
            }
            $obj->description = format_text($category->description);
            $obj->categoryurl = $CFG->wwwroot . "/course/index.php?categoryid=" . $category->id;

            $obj->viewcourses = ($tags['btnlabel'] == "" || !$tags['btnlabel']) ? "Explore" : $tags['btnlabel'];

            $catdesc = strip_tags($obj->description);
            $catdesc = preg_replace('/\n+/', '', $catdesc);
            $obj->shortdesc = strlen($catdesc) > 80 ? mb_substr($catdesc, 0, 80) . "..." : $catdesc;

            $categories["item"][$itemnumber]["categories"][] = $obj;
            if ($count % $group == 0) {
                $itemnumber++;
            }

            $count++;
        }

        return $this->generate_content($tags[_LAYOUT], $categories);
    }

    /**
     * Validate filter tags for required fields.
     *
     * @param array $tags The filter tags to validate.
     * @return object Validation result with error flag and tags.
     */
    public function validate_tags($tags) {

        $retobj = new stdClass();
        $retobj->error = false;

        if (!isset($tags[_CATID]) || $tags[_CATID] == "" || $tags[_CATID] == null) {
            $tags[_CATID] = "all";
        }

        if (!isset($tags[_LAYOUT]) || $tags[_LAYOUT] == "" || $tags[_LAYOUT] == null) {
            $retobj->error = true;
            $retobj->msg = get_string("selectthelayout", "filter_edwiserpbf");
        } else if (!$tags[_LAYOUT] = $this->get_layout($tags[_LAYOUT])) {
            $retobj->error = true;
            $retobj->msg = get_string("selectedlayoutnotfound", "filter_edwiserpbf");
        }

        $retobj->tags = $tags;

        return $retobj;
    }

    /**
     * Get layout record from the database.
     *
     * @param string|int $layout Layout ID or title.
     * @return object|false The layout content or false if not found.
     */
    public function get_layout($layout) {
        global $DB;

        if (is_numeric($layout)) {
            $condition = ["id" => $layout];
        } else {
            $condition = ["title" => $layout];
        }
        $record = $DB->get_record(EDWISERPBF_LAYOUT_TABLE, $condition, '*');

        if (!$record) {
            return false;
        }

        return json_decode($record->content);
    }

    /**
     * Generate final content from layout and context data.
     *
     * @param object $content The layout content object with html, css, and js.
     * @param array $context The template context data.
     * @return string The rendered HTML content.
     */
    public function generate_content($content, $context) {
        global $CFG;
        require_once($CFG->dirroot . '/filter/edwiserpbf/lib.php');
        if (epbf_is_plugin_available("block_edwiseradvancedblock")) {
            require_once($CFG->dirroot . '/blocks/edwiseradvancedblock/lib.php');
            $content->html = replace_cdn_url($content->html);
        }

        $mustache = new \core\output\mustache_engine();

        $finalcontent = $mustache->render($content->html, $context);
        $finalcontent .= $this->get_wrapped_css($content->css);
        $finalcontent .= $this->generate_js_content($content->js);

        return $this->replace_inst_with_fakeid($finalcontent);
    }

    /**
     * Replace [[inst]] placeholder with a random ID.
     *
     * @param string $content The content with [[inst]] placeholders.
     * @return string The content with replaced placeholders.
     */
    public function replace_inst_with_fakeid($content) {
        return str_replace("[[inst]]", rand(0, 9), $content);
    }

    /**
     * Wrap JavaScript content in script tags.
     *
     * @param string $js The JavaScript content.
     * @return string The wrapped JavaScript.
     */
    public function generate_js_content($js) {
        return "<script>" . $js . "</script>";
    }

    /**
     * Return a no data available message.
     *
     * @return string The no data HTML message.
     */
    public function no_data_available() {
        return "<h1 class='text-center'>" . get_string("nodataavailable", "filter_edwiserpbf") . "</h1>";
    }

    /**
     * Wrap CSS content in style tags after SCSS processing.
     *
     * @param string $css The CSS content.
     * @return string The wrapped CSS or empty string.
     */
    public function get_wrapped_css($css) {
        global $CFG;
        if ($css == "") {
            return "";
        }
        try {
            require_once($CFG->libdir . "/classes/scss.php");

            $scss = ".section-[[inst]]{" . htmlspecialchars_decode($css) . "}";
            $scssprocessor = new \core_scss();
            $scssprocessor->append_raw_scss($scss);
            $css = $scssprocessor->to_css();
        } catch (Exception $e) {
            return "";
        }

        return "<style>" . $css . "</style>";
    }

    /**
     * Get advanced category data for a given category.
     *
     * @param object $category The category object.
     * @return object The category data object.
     */
    public function get_edw_adv_category_data($category) {
        global $CFG;
        $obj = new stdClass();
        $obj->id = $category->id;
        $obj->name = format_text($category->name, FORMAT_HTML);
        $obj->parentid = $category->parent;
        $obj->parentname = ($category->parent != 0)
            ? core_course_category::get($category->parent)->name
            : get_string('top', "filter_edwiserpbf");
        $obj->hascount = true;
        $obj->coursecount = core_course_category::get(
            $category->id,
            $strictness = MUST_EXIST,
            $alwaysreturnhidden = true
        )->get_courses_count(['recursive' => true]);
        $categorycontext = \context_coursecat::instance($category->id);
        $text = $category->description;
        $text = file_rewrite_pluginfile_urls($text, 'pluginfile.php', $categorycontext->id, 'coursecat', 'description', null);
        $catsummary = strip_tags(format_text($text));
        $catsummary = preg_replace('/\n+/', '', $catsummary);
        $catsummary = strlen($catsummary) > 150 ? mb_substr($catsummary, 0, 150) . "..." : $catsummary;
        $obj->description = format_text($catsummary, FORMAT_HTML);
        $obj->categoryurl = $CFG->wwwroot . "/course/index.php?categoryid=" . $category->id;

        $obj->viewcourses = get_string('explore', "filter_edwiserpbf");

        return $obj;
    }

    /**
     * Get categories from filter tags.
     *
     * @param array $tags The filter tags containing catid.
     * @return array The categories data array.
     */
    public function get_edw_categories_from_tag($tags) {
        $data = [];
        $cats = explode(',', $tags['catid']);

        if (in_array('all', $cats) || in_array(0, $cats)) {
            $data = core_course_category::get_all();
        } else {
            $data = core_course_category::get_many($cats);
        }
        $categories = [];
        foreach ($data as $key => $category) {
            $categories["categories"][] = $this->get_edw_adv_category_data($category);
        }
        $categories["categories"][0]->active = true;

        return $categories["categories"];
    }

    /**
     * Generate courses and categories content.
     *
     * @param array $tags The filter tags.
     * @return string The rendered HTML content.
     */
    public function generate_cnc_content($tags) {
        global $OUTPUT;

        $context = [];
        $context['instid'] = rand(1, 1000);
        $showcourse = $tags['show'] == 'courses';
        $showcategory = $tags['show'] == 'categories';
        $showcourseandcategory = $tags['show'] == 'courseandcategories';
        $coursehandler = new \local_edwiserpagebuilder\coursehandler();
        $context['cdnurl'] = BLOCKS_CDN_URL;
        if ($showcourse || $showcourseandcategory) {
            $maxlimit = 11;
            if ($showcourseandcategory) {
                $categories = $this->get_edw_categories_from_tag($tags);
                $context['hascategories'] = true;
                $context['categories'] = $categories;
                $context['catids'] = $categories[0]->id;
            } else {
                $context['catids'] = $tags['catid'];
            }
            $catidsnew = $context['catids'];
            $catidsnewarray = explode(",", $catidsnew);
            if (in_array("all", $catidsnewarray) || in_array(0, $catidsnewarray)) {
                $catidsnew = 'all';
            }
            [$totalcoursecount, $courses] = $coursehandler->get_courses(false, null, $catidsnew, 0, $maxlimit);
            $context['coursecount'] = $totalcoursecount;
            $context['courses'] = $courses;
            $context['fetchedcoursecount'] = count($courses);
            $context['loadmore'] = ($context['fetchedcoursecount'] == $maxlimit
                && $context['fetchedcoursecount'] < $totalcoursecount) ? true : false;
            $remaingcourses = $totalcoursecount - $context['fetchedcoursecount'];
            if ($context['loadmore']) {
                $context['remaingcourses'] = $remaingcourses;
            } else {
                $context['remaingcourses'] = 0;
            }
            return $OUTPUT->render_from_template("filter_edwiserpbf/cnccoursesncategories", $context);
        }
        if ($showcategory) {
            $categories = $this->get_edw_categories_from_tag($tags);
            $context["categories"] = $categories;
            return $OUTPUT->render_from_template("filter_edwiserpbf/categoriesnew", $context);
        }
    }

    /**
     * Generate featured courses content.
     *
     * @param array $tags The filter tags.
     * @return string The rendered HTML content.
     */
    public function generate_fc_content($tags) {
        global $OUTPUT, $DB, $PAGE;
        $fccontent = "";

        $context = [];
        $context['instid'] = rand(1, 1000);
        $showcourse = $tags['show'] == 'courses';
        $showcourseandcategory = $tags['show'] == 'courseandcategories';
        $coursehandler = new \local_edwiserpagebuilder\coursehandler();
        $context['cdnurl'] = BLOCKS_CDN_URL;
        $context['blockclass'] = 'fc-block';
        $context['showinforegion'] = false;

        if ($showcourse || $showcourseandcategory) {
            $maxlimit = 11;

            $context['catids'] = null;

            $fccourses = [];

            if ($tags['courseid'] != 0) {
                $context['catids'] = 'all';
                $courseids = explode(",", $tags['courseid']);

                foreach ($courseids as $courseid) {
                    $course = $DB->get_record('course', ['id' => $courseid]);

                    if (
                        $course
                        && isset($course->id)
                        && core_course_category::can_view_course_info($course)
                    ) {
                        $coursecat = core_course_category::get($course->category, IGNORE_MISSING);
                        if (
                            $coursecat
                            && ($coursecat->visible
                                || has_capability('moodle/category:viewhiddencategories', context_system::instance()))
                        ) {
                            $fccourses[] = $course;
                        }
                    }
                }

                if (empty($fccourses)) {
                    $context['hascourses'] = false;

                    if (self::check_user_admin_cap()) {
                        $context['showinforegion'] = true;
                        $context['showfcmainpageinfo'] = true;
                        if ($PAGE->pagelayout == "base") {
                            $context['showfcinfoineditor'] = true;
                            $context['showfcmainpageinfo'] = false;
                        }
                    }

                    return $OUTPUT->render_from_template("filter_edwiserpbf/fccourses", $context);
                }
                [$totalcoursecount, $courses] = $coursehandler->get_courses(
                    false,
                    null,
                    $context['catids'],
                    0,
                    $maxlimit,
                    null,
                    null,
                    $fccourses
                );

                $context['coursecount'] = $totalcoursecount;

                $context['geariconimg'] = $OUTPUT->image_url("Gear_icon", "local_edwiserpagebuilder")->__toString();

                $context['courses'] = $courses;

                $context['fetchedcoursecount'] = count($courses);

                $context['hascourses'] = true;

                $context['loadmore'] = ($context['fetchedcoursecount'] == $maxlimit
                    && $context['fetchedcoursecount'] < $totalcoursecount) ? true : false;

                $remaingcourses = $totalcoursecount - $context['fetchedcoursecount'];

                if ($context['loadmore']) {
                    $context['remaingcourses'] = $remaingcourses;
                } else {
                    $context['remaingcourses'] = 0;
                }
            } else {
                $context['showinforegion'] = true;
                $context['showfcmainpageinfo'] = true;
                if ($PAGE->pagelayout == "base") {
                    $context['showfcinfoineditor'] = true;
                    $context['showfcmainpageinfo'] = false;
                }
            }

            return $OUTPUT->render_from_template("filter_edwiserpbf/fccourses", $context);
        }

        return $fccontent;
    }

    /**
     * Generate RemUI block content.
     *
     * @param array $tags The filter tags.
     * @return string The rendered content.
     */
    public function generate_remuiblck_content($tags) {

        $obj = new \local_edwiserpagebuilder\remuiblck\remuiblck_handler();

        $content = $obj->get_content($tags);

        return $content;
    }

    /**
     * Check if the user has admin or manager capabilities.
     *
     * @param object|null $userobject The user object, defaults to current user.
     * @return bool True if user has admin or manager capabilities.
     */
    public static function check_user_admin_cap($userobject = null) {
        global $USER;
        if (!$userobject) {
            $userobject = $USER;
        }
        if (is_siteadmin()) {
            return true;
        }
        $context = \context_system::instance();
        $roles = get_user_roles($context, $userobject->id, false);
        foreach ($roles as $role) {
            if ($role->roleid == 1 && $role->shortname == 'manager') {
                return true;
            }
        }
        return false;
    }
}
