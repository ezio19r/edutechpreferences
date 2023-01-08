<?php

global $CFG;
require_once($CFG->dirroot . '/blocks/moodleblock.class.php');
require(dirname(dirname(__DIR__)). '\block_edutechpreferences.php');
require_once($CFG->dirroot . '/blocks/edutechpreferences/classes/form/edit.php');
require_once($CFG->dirroot . '/blocks/edutechpreferences/classes/report/getreport.php');

use block_edutechpreferences\edit\edit;
use block_edutechpreferences\report\get_report;

class block_test extends \advanced_testcase{
    public function test_show_content_for_student(){
        $block = new block_edutechpreferences();
        $this->resetAfterTest(true);

        $user = $this->getDataGenerator()->create_user();
        $course = $this->getDataGenerator()->create_course();

        $this->getDataGenerator()->enrol_user($user->id, $course->id, 'student');
        $this->setUser($user);

        $preferences = [
            "id1" => "1",
            "id2" => "1",
            "id3" => "1",
            "id4" => "1",
            "id5" => "1"
        ];

        $recordtoinsert = new stdClass();
        $recordtoinsert->userid = $user->id;
        $recordtoinsert->preferences = json_encode($preferences);
        $preferences = new edit();
        $preferences->block_edutechpreferences_insert_answer($recordtoinsert); 

        $content = $block->block_edutechpreferences_fill_content($course->id);

        $stats = new get_report();

        $context = context_course::instance($course->id);

        $reportdata = $stats->block_edutechpreferences_report_data($course->id, $context);

        $this->assertStringContainsString($reportdata["stats"][0]["areas"][0]["name"], $content[1]);
        $this->assertStringContainsString($reportdata["stats"][0]["areas"][1]["name"], $content[1]);
        $this->assertStringContainsString($reportdata["stats"][0]["areas"][2]["name"], $content[1]);
        $this->assertStringContainsString($reportdata["stats"][1]["areas"][3]["name"], $content[1]);
        $this->assertStringContainsString($reportdata["stats"][1]["areas"][4]["name"], $content[1]);
        $this->assertStringContainsString(get_string("editpreferences", "block_edutechpreferences") , $content[0]);
    }

    public function test_show_content_for_teacher(){
        $block = new block_edutechpreferences();
        $this->resetAfterTest(true);

        $user = $this->getDataGenerator()->create_user();
        $user1 = $this->getDataGenerator()->create_user();
        $course = $this->getDataGenerator()->create_course();

        $this->getDataGenerator()->enrol_user($user->id, $course->id, 'teacher');
        $this->getDataGenerator()->enrol_user($user1->id, $course->id, 'student');

        $this->setUser($user);

        $preferences = [
            "id1" => "1",
            "id2" => "1",
            "id3" => "1",
            "id4" => "1",
            "id5" => "1"
        ];

        $recordtoinsert = new stdClass();
        $recordtoinsert->userid = $user1->id;
        $recordtoinsert->preferences = json_encode($preferences);
        $preferences = new edit();
        $preferences->block_edutechpreferences_insert_answer($recordtoinsert); 

        $content = $block->block_edutechpreferences_fill_content($course->id);

        $stats = new get_report();

        $context = context_course::instance($course->id);

        $reportdata = $stats->block_edutechpreferences_report_data($course->id, $context);

        $reportdata["stats"][0]["areas"][0]["name"];

        $this->assertStringContainsString($reportdata["stats"][0]["areas"][0]["name"].'(1)', $content[1]);
        $this->assertStringContainsString($reportdata["stats"][0]["areas"][1]["name"].'(1)', $content[1]);
        $this->assertStringContainsString($reportdata["stats"][0]["areas"][2]["name"].'(1)', $content[1]);
        $this->assertStringContainsString($reportdata["stats"][1]["areas"][3]["name"].'(1)', $content[1]);
        $this->assertStringContainsString($reportdata["stats"][1]["areas"][4]["name"].'(1)', $content[1]);
        $this->assertStringContainsString(get_string("openreport", "block_edutechpreferences") , $content[0]);
    }

}

