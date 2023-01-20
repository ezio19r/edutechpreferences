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
 * Preferences
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
global $CFG, $PAGE, $OUTPUT;
require_once($CFG->dirroot . '/blocks/edutechpreferences/classes/form/edit.php');
use block_edutechpreferences\edit\edit;
defined('MOODLE_INTERNAL') || die();

$PAGE->set_url(new moodle_url('/blocks/edutechpreferences/preferences.php'));
require_login();
$PAGE->set_context(\context_system::instance());
$PAGE->set_title(get_string("preferencesreport", "block_edutechpreferences"));
$PAGE->set_heading(get_string("preferencesreport", "block_edutechpreferences"));
$PAGE->navbar->ignore_active();
$PAGE->navbar->add(get_string('editpreferences', "block_edutechpreferences"));

$edit = new preferences();

echo $OUTPUT->header();
echo $edit->init();
echo $OUTPUT->footer();

/**
 * Preferences class
 *
 * @package     block_edutechpreferences
 * @copyright   2022 EduTech
 * @author      2022 Ricardo Emmanuel Reyes Acosta<ricardo.ra@aguascalientes.tecnm.mx>
 * @author      2022 Ricardo Mendoza Gonzalez<mendozagric@aguascalientes.tecnm.mx>
 * @author      2022 Mario Alberto Rodriguez Diaz<mario.rd@aguascalientes.tecnm.mx>
 * @author      2022 Carlos Humberto Duron Lara<berthum.ondur@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class preferences {
    public function init() {
        global $CFG, $USER;
        $mform = new edit();
        // Form processing and displaying is done here.
        if ($mform->is_cancelled()) {
            redirect($CFG->wwwroot . "/", get_string("databasediscarted", "block_edutechpreferences"));
        } else if ($fromform = $mform->get_data()) {
            // In this case you process validated data. $mform->get_data() returns data posted in form.
            $recordtoinsert = new stdClass();
            $recordtoinsert->userid = $USER->id;
            $answers = $fromform;
            unset($answers->submitbutton);
            $recordtoinsert->preferences = json_encode($answers);
            $mform->block_edutechpreferences_insert_answer($recordtoinsert);
            // Display form with the information previously given and stored in the database.
            return $mform->display();
        } else {
            // This branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed.
            // Or on the first display of the form.
            return $mform->display();
        }
    }
}
