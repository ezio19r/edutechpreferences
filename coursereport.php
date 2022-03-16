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
 * Plugin version and other meta-data are defined here.
 *
 * @package     block_edutechpreferences
 * @copyright   2022 Ricardo Reyes <ricardo.ra@aguascalientes.tecnm.mx>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_login($course, true, $cm);
require_once($CFG->dirroot . '/blocks/edutechpreferences/classes/report/getreport.php');

$PAGE->set_url(new moodle_url(url: '/blocks/simplemessage/coursereport.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title(title: get_string("preferencesreport", "block_edutechpreferences"));
$PAGE->set_heading(get_string("preferencesreport", "block_edutechpreferences"));

$report = new getreport();
$courseid = optional_param('id', 0, PARAM_INT);
echo $OUTPUT->header();
if ($courseid > 1) {
    echo $report->courseexists($courseid);
}
echo $OUTPUT->footer();
