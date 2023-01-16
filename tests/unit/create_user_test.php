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
 * Short description of the file
 * Plugin version and other meta-data are defined here.
 *
 * @package     block_edutechpreferences
 * @copyright   2022 EduTech
 * @author      2022 Ricardo Emmanuel Reyes Acosta<ricardo.ra@aguascalientes.tecnm.mx>
 * @author      2022 Ricardo Mendoza Gonzalez<mendozagric@aguascalientes.tecnm.mx>
 * @author      2022 Mario Alberto Rodriguez Diaz<mario.rd@aguascalientes.tecnm.mx>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use block_edutechpreferences\report\get_report;

/**
 * Short description for class
 * Plugin version and other meta-data are defined here.
 *
 * @package     block_edutechpreferences
 * @copyright   2022 EduTech
 * @author      2022 Ricardo Emmanuel Reyes Acosta<ricardo.ra@aguascalientes.tecnm.mx>
 * @author      2022 Ricardo Mendoza Gonzalez<mendozagric@aguascalientes.tecnm.mx>
 * @author      2022 Mario Alberto Rodriguez Diaz<mario.rd@aguascalientes.tecnm.mx>
 * @author      2022 Carlos Humberto Duron Lara<18151652@aguascalientes.tecnm.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class create_user_test extends \advanced_testcase {
    public function test_totalstudents() {
        $this->resetAfterTest(true);
        $report = new get_report();

        $user = $this->getDataGenerator()->create_user();
        $user1 = $this->getDataGenerator()->create_user();
        $user2 = $this->getDataGenerator()->create_user();
        $user3 = $this->getDataGenerator()->create_user();
        $user4 = $this->getDataGenerator()->create_user();
        $user5 = $this->getDataGenerator()->create_user();
        $user6 = $this->getDataGenerator()->create_user();
        $user7 = $this->getDataGenerator()->create_user();

        $course = $this->getDataGenerator()->create_course();

        $this->getDataGenerator()->create_role(array('shortname' => 'edutech', 'archetype' => 'student'));

        $this->getDataGenerator()->enrol_user($user->id, $course->id, 'student');
        $this->getDataGenerator()->enrol_user($user1->id, $course->id, 'student');
        $this->getDataGenerator()->enrol_user($user2->id, $course->id, 'student');
        $this->getDataGenerator()->enrol_user($user3->id, $course->id, 'manager');
        $this->getDataGenerator()->enrol_user($user4->id, $course->id, 'editingteacher');
        $this->getDataGenerator()->enrol_user($user5->id, $course->id, 'teacher');
        $this->getDataGenerator()->enrol_user($user6->id, $course->id, 'edutech');
        $this->getDataGenerator()->enrol_user($user7->id, $course->id, 'guest');

        $generator = $this->getDataGenerator()->create_block('edutechpreferences', array('course' => $course->id));

        $context = context_course::instance($course->id);

        $summary = $report->block_edutechpreferences_summary_stats($course->id, $context);

        $this->assertEquals($summary['summarystats'][0]['number'], 4);
        $this->assertEquals($summary['summarystats'][1]['number'], 0);
        $this->assertEquals($summary['summarystats'][2]['number'], 0);
    }
}
