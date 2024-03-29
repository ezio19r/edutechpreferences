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
 * Course report
 *
 * @package     block_edutechpreferences
 * @copyright   2022 EduTech
 * @author      2022 Ricardo Emmanuel Reyes Acosta<ricardo.ra@aguascalientes.tecnm.mx>
 * @author      2022 Ricardo Mendoza Gonzalez<mendozagric@aguascalientes.tecnm.mx>
 * @author      2022 Mario Alberto Rodriguez Diaz<mario.rd@aguascalientes.tecnm.mx>
 * @author      2022 Carlos Humberto Duron Lara<berthum.ondur@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(__DIR__ . '/../../config.php');
global $CFG, $OUTPUT, $PAGE;
require_once($CFG->dirroot . '/blocks/edutechpreferences/classes/report/getreport.php');
use block_edutechpreferences\report\get_report;
defined('MOODLE_INTERNAL') || die();

$PAGE->set_url(new moodle_url('/blocks/edutechpreferences/coursereport.php'));
require_login();
$PAGE->set_context(\context_system::instance());
$report = new course_report();
$courseid = optional_param('id', 0, PARAM_INT);
$PAGE->set_title(get_string("preferencesreport", "block_edutechpreferences"));
$PAGE->set_heading(get_string("preferencesreport", "block_edutechpreferences"));
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('course'), new moodle_url('/course/view.php?id=' . $courseid . ''));
$PAGE->navbar->add(get_string('openreport', "block_edutechpreferences"));

echo $OUTPUT->header();
echo $report->init($courseid);
echo $OUTPUT->footer();

/**
 * Course report class
 *
 * @package     block_edutechpreferences
 * @copyright   2022 EduTech
 * @author      2022 Ricardo Emmanuel Reyes Acosta<ricardo.ra@aguascalientes.tecnm.mx>
 * @author      2022 Ricardo Mendoza Gonzalez<mendozagric@aguascalientes.tecnm.mx>
 * @author      2022 Mario Alberto Rodriguez Diaz<mario.rd@aguascalientes.tecnm.mx>
 * @author      2022 Carlos Humberto Duron Lara<berthum.ondur@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class course_report {
    /**
     * Gives the view of the course report
     * @param int $course  Moodle's course id
     */
    public function init($courseid) {
        global $PAGE, $OUTPUT;
        $report = new get_report();
        $courseexists = $report->block_edutechpreferences_course_exists($courseid);
        // Tries to get all the preferences for each student in the course and display statistical information.
        if (isset($courseexists) && $courseexists > 1) {
            $context = context_course::instance($courseid);
            if (has_capability('block/edutechpreferences:viewreport', $context)) {
                $reportdata = $report->block_edutechpreferences_report_data($courseid, $context);
                $summarystats = $report->block_edutechpreferences_summary_stats($courseid, $context);
                $buttoninfo = $report->block_edutechpreferences_button_info($courseid);
                $arrayfortemplate = $reportdata;
                $arrayfortemplate = array_merge($arrayfortemplate, $summarystats);
                $arrayfortemplate = array_merge($arrayfortemplate, $buttoninfo);
                return $OUTPUT->render_from_template('block_edutechpreferences/stats', $arrayfortemplate);
            } else {
                \core\notification::error(get_string("donthavepermissions", "block_edutechpreferences"));
            }
        } else {
            \core\notification::error(get_string("courseerror", "block_edutechpreferences"));
        }
    }
}
