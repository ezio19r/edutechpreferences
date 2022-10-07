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
 * @copyright   2022 EduTech
 * @author      2022 Ricardo Emmanuel Reyes Acosta<ricardo.ra@aguascalientes.tecnm.mx>
 * @author      2022 Ricardo Mendoza Gonzalez<mendozagric@aguascalientes.tecnm.mx>
 * @author      2022 Mario Alberto Rodriguez Diaz<mario.rd@aguascalientes.tecnm.mx>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();
// Moodleform is defined in formslib.php.
require_once("$CFG->libdir/formslib.php");
require_once($CFG->dirroot . '/blocks/edutechpreferences/classes/api/api.php');
use block_edutechpreferences\api\api;
class edit extends moodleform {
    /**
     * Generates the preferences form with the data obtained of the API/getapi()
     * if the form was previously filled by the current student, it will be prefilled with the stored data
     * in case of failure prints a notification with the error
     * in case of success, the form will be shown.
     * @return void
     */
    public function definition() {
        global $USER;
        global $CFG;
        $apis = new api();
        $mform = $this->_form; // Don't forget the underscore!
        $x = $apis->getapi();
        if ($x != '0') {
            $y = json_decode($x);
            foreach ($y as $key) {
                $mform->addElement('static', 'description', "<b>$key->preferences_are</b>");
                foreach ($key->preferences as $data) {
                    $id = json_encode("id$data->id");
                    $answered = $this->checkdata($USER->id, $id);
                    $mform->addElement('checkbox', "id$data->id", "$data->description");
                    if ($answered == 1) {
                        $mform->setDefault("id$data->id", array('checked' => '1'));
                    }
                }
            }
            $this->add_action_buttons($cancel = true, $submitlabel = null);
        } else {
              \core\notification::error(get_string("apierror", "block_edutechpreferences"));
        }
    }
    /**
     * Checks in the database if the current student previously filled the form
     * in case of failure returns a zero
     * in case of success, will return the numer of rows prefilled by the user.
     * @param int $userid  Moodle user's id
     * @param int $id edutech_preferences row id
     * @return int $query->count number of student's answers
     */
    private function checkdata($userid, $id) {
        global $DB;
        $id = "%$id%";
        $query = $DB->get_record_sql('SELECT COUNT(id) as count FROM {block_edutechpreferences} WHERE  userid = ?
        AND preferences LIKE ? ', [$userid, $id] );
        if (isset($query->count)) {
            return $query->count;
        } else {
            return 0;
        }
    }
}
