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
 * Capabilities
 *
 * @package     block_edutechpreferences
 * @copyright   2022 EduTech
 * @author      2022 Ricardo Emmanuel Reyes Acosta<ricardo.ra@aguascalientes.tecnm.mx>
 * @author      2022 Ricardo Mendoza Gonzalez<mendozagric@aguascalientes.tecnm.mx>
 * @author      2022 Mario Alberto Rodriguez Diaz<mario.rd@aguascalientes.tecnm.mx>
 * @author      2022 Carlos Humberto Duron Lara<berthum.ondur@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();
$capabilities = array(
      'block/edutechpreferences:myaddinstance' => array(
          'captype' => 'write',
          'contextlevel' => CONTEXT_SYSTEM,
          'archetypes' => array(
              'user' => CAP_ALLOW
          ),

          'clonepermissionsfrom' => 'moodle/my:manageblocks'
      ),
      'block/edutechpreferences:addinstance' => array(
          'captype' => 'read',
          'contextlevel' => CONTEXT_COURSE
      ),
      'block/edutechpreferences:view' => array(
          'captype' => 'read',
          'contextlevel' => CONTEXT_COURSE,
          'archetypes' => array(
              'student' => CAP_ALLOW
          )
      ),
      'block/edutechpreferences:viewreport' => array(
          'captype' => 'read',
          'contextlevel' => CONTEXT_COURSE,
          'archetypes' => array(
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
          )
      )
);
