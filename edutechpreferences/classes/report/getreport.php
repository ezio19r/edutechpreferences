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
namespace block_edutechpreferences\report;
defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/blocks/edutechpreferences/classes/api/api.php');
use block_edutechpreferences\api\api;
class getreport {

    public function courseexists($courseid) {
        global $DB;
        $id = 0;
        $query = $DB->get_record_sql('SELECT id FROM {course} WHERE id = ? ', [$courseid]);
        if ((int)$query->id > 1) {
            $id = (int)$query->id;
        }
        return $id;
    }

    public function summarystats($courseid, $context) {
        $totalstudents = $this->totalstudents($context->id);
        $totalresponses = $this->totalresponses($context->id);
        $avg = 0;
        if ($totalstudents > 0) {
            $avg = ($totalresponses * 100) / $totalstudents;
        }
        $array = array('summarystats' => [
            ['name' => get_string("totalstudents", "block_edutechpreferences"), 'number' => $totalstudents],
            ['name' => get_string("totalresponses", "block_edutechpreferences"), 'number' => $totalresponses],
            ['name' => get_string("responserate", "block_edutechpreferences"), 'number' => $avg]
        ]);
        return $array;
    }

    public function reportdata($courseid, $context) {
        $apis = new api();
        $preferenceareas = $apis->getapi();
        $preferenceareas = json_decode($preferenceareas);
        $totalstudents = $this->totalstudents($context->id);
        $array = array();
        if ($preferenceareas != 0) {
            $categoryarray = [];
            foreach ($preferenceareas as $key) {
                $areaarray2 = [];
                $responsecount = 0;
                foreach ($key->preferences as $data) {
                    $id = json_encode("id$data->id");
                    if ($totalstudents > 0) {
                        $responsecount = ($this->responsestats($context->id, $id) * 100) / $totalstudents;
                    }
                    array_push($areaarray2, ['name' => $data->description, 'count' => $responsecount ]);
                }
                array_push($categoryarray, ['category' => $key->preferences_are, 'areas' => $areaarray2]);
            }
            $array['stats'] = $categoryarray;
            return $array;
        } else {
            \core\notification::error("Ocurrio un error al intentar conectarse al servidor Edutech");
            return '';
        }
        return $array;
    }

    public function buttoninfo($courseid) {
        global $CFG;
        $array = array('button' => ["name" => get_string("goback", "block_edutechpreferences"), "url"
         => "$CFG->wwwroot/course/view.php?id=$courseid"]);
        return $array;
    }

    public function totalstudents($context) {
        global $DB;
        $query = $DB->get_record_sql('SELECT count(ra.userid) as total FROM {role_assignments} ra
        JOIN {user} u ON ra.userid=u.id WHERE ra.contextid= ? AND ra.roleid = 5', [$context]);
        $totalstudents = (int)$query->total;
        return $totalstudents;
    }

    public function totalresponses($context) {
        global $DB;
        $query = $DB->get_record_sql('SELECT count(ra.userid) as total FROM {role_assignments} ra
        JOIN {user} u ON ra.userid = u.id JOIN {block_edutechpreferences} bl ON ra.userid = bl.userid
        WHERE ra.contextid = ? AND ra.roleid = 5', [$context]);
        $totalresponses = (int)$query->total;
        return $totalresponses;
    }

    public function responsestats($context, $id) {
        global $DB;
        $id = "%$id%";
        $query = $DB->get_record_sql('SELECT COUNT(bl.id) as total FROM {role_assignments} ra
        JOIN {user} u ON ra.userid = u.id JOIN {block_edutechpreferences} bl ON ra.userid = bl.userid
        WHERE ra.contextid = ? AND ra.roleid = 5 AND bl.preferences LIKE ?', [$context, $id]);
        $responsestats = (int)$query->total;
        return $responsestats;
    }
}
