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
require_once($CFG->dirroot . '/blocks/edutechpreferences/classes/form/edit.php');
defined('MOODLE_INTERNAL') || die();
global $DB;

$PAGE->set_url(new moodle_url(url: '/blocks/simplemessage/preferences.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title(title: get_string("preferencesreport", "block_edutechpreferences"));
$PAGE->set_heading(get_string("preferencesreport", "block_edutechpreferences"));

$mform = new edit();
//$courseid = optional_param('id', 50, PARAM_INT);
// Form processing and displaying is done here.
if ($mform->is_cancelled()) {
    redirect(url: $CFG->wwwroot . "/my", message: 'Los cambios han sido descartados');
} else if ($fromform = $mform->get_data()) {
    // In this case you process validated data. $mform->get_data() returns data posted in form.
    // var_dump($fromform); //check info before saved to the DB.
    $recordtoinsert = new stdClass();
    $recordtoinsert->userid = $USER->id;
    $some = $fromform;
    unset($some->submitbutton);
    $recordtoinsert->preferences = json_encode($some);

    $query = $DB->get_records_sql('SELECT id FROM {block_edutechpreferences} WHERE userid ='.$USER->id.' LIMIT 1');
    $rowid = 0;
    foreach ($query as $record) {
        if ($record->id > 0) {
            $rowid = $record->id;
        }
    }

    if ($rowid === 0) {
        try {
            $DB->insert_record('block_edutechpreferences', $recordtoinsert);
            \core\notification::success("Cambios Guardados");
        } catch (\Exception $e) {
            \core\notification::error("Ocurrio un Error");
        }
    } else if ($rowid > 1) {
        $dataobject = array(
            'id'            => $rowid,
            'userid'        => $USER->id,
            'preferences'   => json_encode($some)
        );
        try {
            $DB->update_record('block_edutechpreferences', $dataobject, $bulk = false);
            \core\notification::success("Cambios Guardados");
        } catch (\Exception $e) {
            \core\notification::error("Ocurrio un Error");
        }
    } else {
        \core\notification::error("Ocurrio un Error");
    }

    echo $OUTPUT->header();
    $mform->display();
    echo $OUTPUT->footer();

} else {
      // This branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed.
      // Or on the first display of the form.
      echo $OUTPUT->header();
      $mform->display();
      echo $OUTPUT->footer();
}
