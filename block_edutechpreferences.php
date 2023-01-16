<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Block edutechpreferences
 *
 * @package     block_edutechpreferences
 * @copyright   2022 EduTech
 * @author      2022 Ricardo Emmanuel Reyes Acosta<ricardo.ra@aguascalientes.tecnm.mx>
 * @author      2022 Ricardo Mendoza Gonzalez<mendozagric@aguascalientes.tecnm.mx>
 * @author      2022 Mario Alberto Rodriguez Diaz<mario.rd@aguascalientes.tecnm.mx>
 * @author      2022 Carlos Humberto Duron Lara<18151652@aguascalientes.tecnm.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/blocks/edutechpreferences/classes/block/block.php');

/**
 * Block edutechpreferences class
 *
 * @package     block_edutechpreferences
 * @copyright   2022 EduTech
 * @author      2022 Ricardo Emmanuel Reyes Acosta<ricardo.ra@aguascalientes.tecnm.mx>
 * @author      2022 Ricardo Mendoza Gonzalez<mendozagric@aguascalientes.tecnm.mx>
 * @author      2022 Mario Alberto Rodriguez Diaz<mario.rd@aguascalientes.tecnm.mx>
 * @author      2022 Carlos Humberto Duron Lara<18151652@aguascalientes.tecnm.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_edutechpreferences extends block_base {
    public function init() {
        $this->title = get_string("titleblock", "block_edutechpreferences");
    }
    public function get_content() {
        global $COURSE, $CFG;
        $content1 = $this->block_edutechpreferences_fill_content($COURSE->id);
        $this->content         = new stdClass;
        $this->content->text   = $content1[0];
        $this->content->footer = $content1[1];
        if ($this->content !== null) {
            return $this->content;
        }
    }

    public function block_edutechpreferences_fill_content($courseid) {
        global $COURSE, $CFG;
        $edutechblock = new edutechblock();
        $id = $courseid;
        $context = context_course::instance($id);
        $body = '';
        $footer = '';
        if (has_capability('block/edutechpreferences:viewreport', $context)) {
            if ($id && $id > 1) {
                $body = '<a href = "'.$CFG->wwwroot.'/blocks/edutechpreferences/coursereport.php?id='.$id.'">'
                .get_string("openreport", "block_edutechpreferences").'</a><br/>';
                $footer = $edutechblock->block_edutechpreferences_get_report_summary($context->id);
            }
        } else if (has_capability('block/edutechpreferences:view', $context)) {
              $body = '<a href = "'.$CFG->wwwroot.'/blocks/edutechpreferences/preferences.php">'
              .get_string("editpreferences", "block_edutechpreferences").'</a><br/>';
              $footer = $edutechblock->block_edutechpreferences_get_student_preferences();
        }
        $content = [];
        array_push($content, $body, $footer);
        return $content;
    }
}
