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
// Moodleform is defined in formslib.php.

require_once($CFG->dirroot . '/config.php');
require_once("$CFG->libdir/formslib.php");
require_once($CFG->dirroot . '/blocks/edutechpreferences/classes/api/api.php');
defined('MOODLE_INTERNAL') || die();
class edit extends moodleform {
    // Add elements to form.
    public function definition() {
        global $USER;
        global $CFG;
        $apis = new api();
        $mform = $this->_form; // Don't forget the underscore!
        $x = $apis->getapi();
        if ($x != 0) {
            $y = json_decode($x);
            foreach ($y as $key) {
                $mform->addElement('static', 'description', "<b>$key->preferences_are</b>");
                foreach ($key->preferences as $data) {
                    $id = json_encode("id$data->id");
                    $z = $this->checkdata($USER->id, $id);
                    $mform->addElement('checkbox', "id$data->id", "$data->description");
                    if ($z == 1) {
                        $mform->setDefault("id$data->id", array('checked' => '1'));
                    }
                }
            }
            $this->add_action_buttons($cancel = true, $submitlabel = null);
        } else {
              \core\notification::error("Ocurrio un error al intentar conectarse al servidor Edutech");
        }
    }
    // Custom validation should be added here.
    function validation($data, $files) {
        return array();
    }

    public function checkdata($userid, $id) {
        global $DB;
        $query = $DB->get_records_sql("SELECT COUNT(id) as COUNT FROM {block_edutechpreferences} WHERE userid=$userid
        AND preferences LIKE '%$id%'");
        $count = 0;
        foreach ($query as $record) {
            $count = $record->count;
        }
        return $count;
    }
}
