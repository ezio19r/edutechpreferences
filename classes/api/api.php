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
class api {
    const SERVER = 'https://repositorio.edutech-project.org/';
    public function getapi() {
        $apidir = 'https://repositorio.edutech-project.org/api/v1/preferences-area/';
        $url = $apidir;
        try {
              $curl = curl_init($url);
              curl_setopt($curl, CURLOPT_URL, $url);
              curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
              //for debug only!
              curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
              curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
              curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 2);
              $resp = curl_exec($curl);
              //var_dump($resp);
              curl_close($curl);
              return($resp);
        } catch (\Exception $e) {
              return 0;
        }
    }


    public function getpreferenceName($id){
        $apidir = "https://repositorio.edutech-project.org/api/v1/user-preferences/".$id."/";
        $url = $apidir;
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        //for debug only!
        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 2);
        $resp = curl_exec($curl);
        curl_close($curl);

        return($resp);
    }
}
