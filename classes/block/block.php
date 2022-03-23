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
require_once($CFG->dirroot . '/blocks/edutechpreferences/classes/api/api.php');
use block_edutechpreferences\api\api;
class block {

    public function getreportsummary($context) {
        global $DB;
        $query = $DB->get_records_sql('SELECT bl.preferences FROM {role_assignments} ra JOIN {user} u ON ra.userid = u.id
          JOIN {block_edutechpreferences} bl ON ra.userid=bl.userid WHERE ra.contextid = ? AND ra.roleid = 5', [$context]);
        $stats = array('id3' => 0,
                      'id2' => 0,
                      'id1' => 0,
                      'id8' => 0,
                      'id7' => 0,
                      'id6' => 0,
                      'id5' => 0,
                      'id4' => 0,
                      'id9' => 0,
                      'id13' => 0,
                      'id12' => 0,
                      'id11' => 0,
                      'id10' => 0,
                    );
        $array = [];
        foreach ($query as $record) {
            $array = json_decode($record->preferences, true);
            foreach ($stats as $key2 => &$value2) {
                foreach ($array as $key => $value) {
                    if ($key == $key2) {
                        $value2 = $value2 + 1;
                    }
                }
            }
        }
        arsort($stats);
        $stats = array_slice($stats, 0, 5);
        $z = $this->getareanames($stats, 'professor');
        $footer = $this->getfooterprofessor($z);
        return $footer;
    }

    public function getfooterprofessor($array) {
        $footer = '<div> <br/><label>'.get_string("contentsuggestions", "block_edutechpreferences").':</label><br/>';
        foreach ($array as $x => $xvalue) {
            if ($xvalue > 0) {
                $footer .= '<span class="badge badge-pill badge-light" style="margin:2px;">'.$x.'('.$xvalue.')</span><br>';
            }
        }
        $footer .= '<div>';
        return $footer;
    }

    public function getstudentpreferences() {
        global $DB;
        global $USER;
        $apis = new api();
        $query = $DB->get_records_sql('SELECT preferences FROM  {block_edutechpreferences} bl
          WHERE bl.userid = ? LIMIT 1', [$USER->id]);
        $array = new stdClass();
        foreach ($query as $record) {
            $array = json_decode($record->preferences, true);
            $z = $this->getareanames($array, 'student');
        }
        $footer = $this->getfooterstudent($z);
        return $footer;
    }

    public function getfooterstudent($array) {
        $footer = '<div> <br/><label>'.get_string("yourpreferences", "block_edutechpreferences").':</label><br/>';
        foreach ($array as $x => $xvalue) {
            if ($xvalue > 0) {
                $footer .= '<span class="badge badge-pill badge-light" style="margin:2px;">'.$xvalue.'</span><br>';
            }
        }
        $footer .= '<div>';
        return $footer;
    }

    public function getareanames($array, $type) {
        $names = array('id3' => get_string("id3", "block_edutechpreferences"),
                      'id2' => get_string("id2", "block_edutechpreferences"),
                      'id1' => get_string("id1", "block_edutechpreferences"),
                      'id8' => get_string("id8", "block_edutechpreferences"),
                      'id7' => get_string("id7", "block_edutechpreferences"),
                      'id6' => get_string("id6", "block_edutechpreferences"),
                      'id5' => get_string("id5", "block_edutechpreferences"),
                      'id4' => get_string("id4", "block_edutechpreferences"),
                      'id9' => get_string("id9", "block_edutechpreferences"),
                      'id13' => get_string("id13", "block_edutechpreferences"),
                      'id12' => get_string("id12", "block_edutechpreferences"),
                      'id11' => get_string("id11", "block_edutechpreferences"),
                      'id10' => get_string("id10", "block_edutechpreferences")
          );
        $arraywithnames = [];
        foreach ($array as $key => $value) {
            foreach ($names as $key2 => $value2) {
                if ($key == $key2 and $type == 'student') {
                    $arraywithnames = array_merge($arraywithnames, [$key => $value2]);
                } else if ($key == $key2 and $type == 'professor') {
                    $arraywithnames = array_merge($arraywithnames, [$value2 => $value]);
                }
            }
        }
        return $arraywithnames;
    }
}
