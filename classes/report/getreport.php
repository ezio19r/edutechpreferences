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

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot . '/blocks/edutechpreferences/classes/api/api.php');
class getreport {
    public function courseexists($courseid) {
        global $DB;
        $id = 0;
        $report = '';
        $query = $DB->get_records_sql('SELECT id FROM {course} WHERE id = '.$courseid.'');
        foreach ($query as $value) {
            if ($value->id > 1) {
                $id = $value->id;
                $report = $this->display($id);
            }
        }
        return $report;
    }

    public function display($courseid) {
        $context = get_context_instance(CONTEXT_COURSE, $courseid);
        $apis = new api();
        $preferenceareas = $apis->getapi();
        if ($preferenceareas != 0){
            $preferenceareas = json_decode($preferenceareas);
            $totalstudents = $this->totalstudents($context->id);
            $totalresponses = $this->totalresponses($context->id);
            $responsecount = 0;
            $report = $this->_report;

            $report = '<div class="row">';
            foreach ($preferenceareas as $key) {
                $report .= '<div class="col-lg-3 col-md-6" style="margin-bottom:10px; padding:5px;">
                <div class="card">
                <h5 class="card-header">
                <b>'.$key->preferences_are.'</b>
                </h5>
                <div class="card-body" style="padding:20px;">';
                $responsecount = 0;
                foreach ($key->preferences as $data) {
                    $id = json_encode("id$data->id");
                    $stat = 0;
                    if ($totalstudents > 0) {
                        $stat = ($responsecount = $this->responsestats($context->id, $id) * 100) / $totalstudents;
                    }
                    $report .= '<div>
                    '.$data->description.'
                    <div class="progress">
                    <div class="progress-bar progress-bar-striped" role="progressbar" style="width: '.$stat.'%;"
                    aria-valuenow="'.$stat.'" aria-valuemin="0" aria-valuemax="100">'.$stat.'%</div>
                    </div><br>
                    </div>';
                }
                $report .= '</div>
                </div>
                </div>';
            }
            $report .= '</div>';
            $avg = 0;
            if ($totalstudents > 0) {
                $avg = ($totalresponses * 100) / $totalstudents;
            }
            $report .= '
            <div class="row">
            <div class="col-md-12">
            <table style="text-align:right">
            <tr><td><b>'.get_string("totalstudents", "block_edutechpreferences").':</b></td><td>'.$totalstudents.'</td></tr>
            <tr><td><b>'.get_string("totalresponses", "block_edutechpreferences").':</b></td><td>'.$totalresponses.'</td></tr>
            <tr><td><b>% '.get_string("responserate", "block_edutechpreferences").':</b></td><td> &nbsp'.$avg.'</td></tr>
            </table>
            </div>
            </div>
            ';
            $report .= '
            <div class="row">
            		<div class="col-md-12" style="text-align:center;"><br><br>
                <a href="'.$CFG->wwwroot. '../../course/view.php?id='.$courseid.'"><button type="button" class="btn btn-primary">
            				'.get_string("goback", "block_edutechpreferences").'
            			</button></a>
                </div>
            </div>
            ';
            return $report;
       }
       else {
           \core\notification::error("Ocurrio un error al intentar conectarse al servidor Edutech");
           return '';
       }
    }


    public function totalstudents($context) {
        global $DB;
        $totalstudentsincourse = $DB->get_records_sql('SELECT count(ra.userid) as total FROM {role_assignments} ra
        JOIN {user} u ON ra.userid=u.id WHERE ra.contextid='.$context.' AND ra.roleid = 5');
        $tsic = 0;
        foreach ($totalstudentsincourse as $record) {
            $tsic = $record->total;
        }
        return $tsic;
    }

    public function totalresponses($context) {
        global $DB;
        $totalstudentsresponses = $DB->get_records_sql('SELECT count(ra.userid) as total FROM {role_assignments} ra
        JOIN {user} u ON ra.userid = u.id JOIN {block_edutechpreferences} bl ON ra.userid=bl.userid
        WHERE ra.contextid='.$context.' AND ra.roleid = 5 ' );
        $tsr = 0;
        foreach ($totalstudentsresponses as $record) {
            $tsr = $record->total;
        }
        return $tsr;
    }

    public function responsestats($context, $id) {
        global $DB;
        $z = $DB->get_records_sql("SELECT COUNT(bl.id) as Total FROM {role_assignments} ra
        JOIN {user} u ON ra.userid = u.id JOIN {block_edutechpreferences} bl ON ra.userid = bl.userid
        WHERE ra.contextid = '.$context.' AND ra.roleid=5 AND bl.preferences LIKE '%$id%'");
        $count = 0;
        foreach ($z as $record) {
            $count = $record->total;
        }
        return $count;
    }
}
