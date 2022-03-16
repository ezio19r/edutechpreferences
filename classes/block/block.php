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
class block {

    public function getreportsummary($context) {
        global $DB;
        $query = $DB->get_records_sql('SELECT bl.preferences FROM {role_assignments} ra JOIN {user} u ON ra.userid = u.id
        JOIN {block_edutechpreferences} bl ON ra.userid=bl.userid WHERE ra.contextid='.$context.' AND ra.roleid = 5');
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
        $z = $this->areaname2($stats);
        $footer = $this->getfooter($z);
        return $footer;
    }

    public function getfooter($array) {
        $footer = '<div> <br/><label>'.get_string("contentsuggestions", "block_edutechpreferences").':</label><br/>';
        foreach ($array as $x => $xvalue) {
            if ($xvalue > 0) {
                $footer .= '<span class="badge badge-pill badge-light" style="margin:2px;">'.$x.'('.$xvalue.')</span><br>';
            }
        }
        $footer .= '<div>';
        return $footer;
    }
    public function areaname2($array) {
        $names = array('id3' => 'Descripción de texto para imágenes',
                      'id2' => 'Evitar recursos con dependencia de color',
                      'id1' => 'Información mayoritariamente visual',
                      'id8' => 'Información mayoritariamente auditivo',
                      'id7' => 'Evitar sonido de fondo sin control',
                      'id6' => 'Transcripción para audio y video',
                      'id5' => 'Descripción de audio para video',
                      'id4' => 'Subtítulos para audio y video',
                      'id9' => 'Información mayoritariamente textual',
                      'id13' => 'Manejo total con mouse',
                      'id12' => 'Manejo total con teclado',
                      'id11' => 'Evitar simulación/movimiento',
                      'id10' => 'Evitar luces parpadeantes'
        );
        $array2 = [];
        foreach ($array as $key => $value) {
            foreach ($names as $key2 => $value2) {
                if ($key == $key2) {
                    $array2 = array_merge($array2, [$value2 => $value]);
                }
            }
        }
          return $array2;
    }

    public function getstudentpreferences() {
        global $DB;
        global $USER;
        $apis = new api();
        $query = $DB->get_records_sql('SELECT * FROM  {block_edutechpreferences} bl WHERE bl.userid = '.$USER->id.' LIMIT 1');
        $array = new stdClass();
        foreach ($query as $record) {
            $array = json_decode($record->preferences, true);
            $z = $this->areaName($array);
        }
        $footer = $this->getfooter2($z);
        return $footer;
    }

    public function getfooter2($array) {
        $footer = '<div> <br/><label>'.get_string("yourpreferences", "block_edutechpreferences").':</label><br/>';
        foreach ($array as $x => $xvalue) {
            if ($xvalue > 0) {
                $footer .= '<span class="badge badge-pill badge-light" style="margin:2px;">'.$xvalue.'</span><br>';
            }
        }
        $footer .= '<div>';
        return $footer;
    }

    public function areaname($array) {
        $names = array('id3' => 'Descripción de texto para imágenes',
                      'id2' => 'Evitar recursos con dependencia de color',
                      'id1' => 'Información mayoritariamente visual',
                      'id8' => 'Información mayoritariamente auditivo',
                      'id7' => 'Evitar sonido de fondo sin control',
                      'id6' => 'Transcripción para audio y video',
                      'id5' => 'Descripción de audio para video',
                      'id4' => 'Subtítulos para audio y video',
                      'id9' => 'Información mayoritariamente textual',
                      'id13' => 'Manejo total con mouse',
                      'id12' => 'Manejo total con teclado',
                      'id11' => 'Evitar simulación/movimiento',
                      'id10' => 'Evitar luces parpadeantes'
          );
        $array2 = [];
        foreach ($array as $key => $value) {
            foreach ($names as $key2 => $value2) {
                if ($key == $key2) {
                    $array2 = array_merge($array2, [$key => $value2]);
                }
            }
        }
        return $array2;
    }
}
