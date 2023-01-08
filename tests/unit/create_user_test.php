<?php

use block_edutechpreferences\report\get_report;

class create_user_test extends \advanced_testcase{
    public function test_totalstudents(){
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
