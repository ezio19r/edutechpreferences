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
 * Access test
 *
 * @package     block_edutechpreferences
 * @copyright   2022 EduTech
 * @author      2022 Ricardo Emmanuel Reyes Acosta<ricardo.ra@aguascalientes.tecnm.mx>
 * @author      2022 Ricardo Mendoza Gonzalez<mendozagric@aguascalientes.tecnm.mx>
 * @author      2022 Mario Alberto Rodriguez Diaz<mario.rd@aguascalientes.tecnm.mx>
 * @author      2022 Carlos Humberto Duron Lara<18151652@aguascalientes.tecnm.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Access test class
 *
 * @package     block_edutechpreferences
 * @copyright   2022 EduTech
 * @author      2022 Ricardo Emmanuel Reyes Acosta<ricardo.ra@aguascalientes.tecnm.mx>
 * @author      2022 Ricardo Mendoza Gonzalez<mendozagric@aguascalientes.tecnm.mx>
 * @author      2022 Mario Alberto Rodriguez Diaz<mario.rd@aguascalientes.tecnm.mx>
 * @author      2022 Carlos Humberto Duron Lara<18151652@aguascalientes.tecnm.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class access_test extends \advanced_testcase {
    /**
     * Test that only users with the
     * capabilitie to view the report can see it.
     */
    public function test_allow_teacher() {
        $this->resetAfterTest(true);

        $user = $this->getDataGenerator()->create_user();
        $course = $this->getDataGenerator()->create_course();

        $this->getDataGenerator()->enrol_user($user->id, $course->id, 'teacher');
        $this->setUser($user);

        $generator = $this->getDataGenerator()->create_block('edutechpreferences', array('course' => $course->id));

        require_once(dirname(dirname(__DIR__)) . '\coursereport.php');

        $report = new course_report();

        $view = $report->init($course->id);

        $this->setUser(null);

        $contain = str_contains($view, get_string("totalstudents", "block_edutechpreferences"));

        $this->assertTrue($contain);
    }

    /**
     * Test that users without the
     * capabilitie to view the report can't see it.
     */
    public function test_denegate_student() {
        $this->resetAfterTest(true);

        $user = $this->getDataGenerator()->create_user();
        $course = $this->getDataGenerator()->create_course();

        $this->getDataGenerator()->enrol_user($user->id, $course->id, 'student');
        $this->setUser($user);

        $generator = $this->getDataGenerator()->create_block('edutechpreferences', array('course' => $course->id));

        require_once(dirname(dirname(__DIR__)) . '\coursereport.php');

        $reports = new course_report();

        $view = $reports->init($course->id);

        $this->setUser(null);

        $this->assertNull($view);
    }
}
