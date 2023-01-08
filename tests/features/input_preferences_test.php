<?php

global $CFG;
require_once($CFG->dirroot . '/blocks/edutechpreferences/classes/form/edit.php');
require_once($CFG->dirroot . '/blocks/edutechpreferences/classes/report/getreport.php');
require_once($CFG->dirroot . '/blocks/edutechpreferences/classes/block/block.php');

use block_edutechpreferences\report\get_report;
use block_edutechpreferences\edit\edit;


class input_preferences_test extends \advanced_testcase
{
    public function test_input()
    {
        global $DB;
        $this->resetAfterTest(true);

        $users = [];

        $user = $this->getDataGenerator()->create_user();
        array_push($users, $user);
        $user1 = $this->getDataGenerator()->create_user();
        array_push($users, $user1);
        $user2 = $this->getDataGenerator()->create_user();
        array_push($users, $user2);
        $user3 = $this->getDataGenerator()->create_user();
        array_push($users, $user3);
        $user4 = $this->getDataGenerator()->create_user();
        array_push($users, $user4);
        $user5 = $this->getDataGenerator()->create_user();
        array_push($users, $user5);
        $user6 = $this->getDataGenerator()->create_user();
        array_push($users, $user6);
        $user7 = $this->getDataGenerator()->create_user();
        array_push($users, $user7);

        $course = $this->getDataGenerator()->create_course();

        $this->getDataGenerator()->enrol_user($user->id, $course->id, 'student');
        $this->getDataGenerator()->enrol_user($user1->id, $course->id, 'student');
        $this->getDataGenerator()->enrol_user($user2->id, $course->id, 'student');
        $this->getDataGenerator()->enrol_user($user3->id, $course->id, 'student');
        $this->getDataGenerator()->enrol_user($user4->id, $course->id, 'student');
        $this->getDataGenerator()->enrol_user($user5->id, $course->id, 'teacher');
        $this->getDataGenerator()->enrol_user($user6->id, $course->id, 'teacher');
        $this->getDataGenerator()->enrol_user($user7->id, $course->id, 'guest');


        $this->setUser($user);
        $this->setUser($user1);
        $this->setUser($user2);
        $this->setUser($user3);
        $this->setUser($user4);
        $this->setUser($user5);
        $this->setUser($user6);
        $this->setUser($user7);

        $preferences_area = [
            "id1" => "1",
            "id2" => "1",
            "id3" => "1",
            "id4" => "1",
            "id5" => "1",
            "id6" => "1",
            "id7" => "1",
            "id8" => "1",
            "id9" => "1",
            "id10" => "1",
            "id11" => "1",
            "id12" => "1",
            "id13" => "1",
        ];

        for ($i = 0; $i < 5; $i++) {
            $idpreferences = array_rand($preferences_area, 4);
            $preferences = [];

            foreach ($idpreferences as $key) {
                $preferences[$key] = "1";
            }

            $user = $users[$i];

            $recordtoinsert = new stdClass();
            $recordtoinsert->userid = $user->id;
            $recordtoinsert->preferences = json_encode($preferences);
            $preferences = new edit();
            $preferences->block_edutechpreferences_insert_answer($recordtoinsert);

            $query = $DB->get_record_sql('SELECT COUNT(id) as count FROM {block_edutechpreferences} WHERE  userid = ?', [$user->id]);
            if (isset($query->count)) {
                $count = $query->count;
            } else {
                $count = 0;
            }
            //Check if the previous insertion has been succesfully
            $this->assertEquals(1, $count);
        }

            //Check how many students give an answer to the form of preferences
            $query = $DB->get_record_sql('SELECT DISTINCT COUNT(userid) as count FROM {block_edutechpreferences}');
            if (isset($query->count)) {
                $count = $query->count;
            } else {
                $count = 0;
            }
            $this->assertEquals(5, $count);

            //Check if at least one answer is 1
            $query = $DB->get_record_sql('SELECT preferences FROM {block_edutechpreferences} LIMIT 1');
            if (isset($query->preferences)) {
                $answer = json_encode($query->preferences[8]);
            } else {
                $answer = 0;
            }
            $this->assertEquals('"1"', $answer);
    }

    public function test_report()
    {
        $this->resetAfterTest(true);

        $users = [];

        $user = $this->getDataGenerator()->create_user();
        array_push($users, $user);
        $user1 = $this->getDataGenerator()->create_user();
        array_push($users, $user1);
        $user2 = $this->getDataGenerator()->create_user();
        array_push($users, $user2);
        $user3 = $this->getDataGenerator()->create_user();
        array_push($users, $user3);
        $user4 = $this->getDataGenerator()->create_user();
        array_push($users, $user4);
        $user5 = $this->getDataGenerator()->create_user();
        array_push($users, $user5);
        $user6 = $this->getDataGenerator()->create_user();
        array_push($users, $user6);
        $user7 = $this->getDataGenerator()->create_user();
        array_push($users, $user7);

        $course = $this->getDataGenerator()->create_course();

        $this->getDataGenerator()->enrol_user($user->id, $course->id, 'student');
        $this->getDataGenerator()->enrol_user($user1->id, $course->id, 'student');
        $this->getDataGenerator()->enrol_user($user2->id, $course->id, 'student');
        $this->getDataGenerator()->enrol_user($user3->id, $course->id, 'student');
        $this->getDataGenerator()->enrol_user($user4->id, $course->id, 'student');
        $this->getDataGenerator()->enrol_user($user5->id, $course->id, 'teacher');
        $this->getDataGenerator()->enrol_user($user6->id, $course->id, 'teacher');
        $this->getDataGenerator()->enrol_user($user7->id, $course->id, 'guest');


        $this->setUser($user);
        $this->setUser($user1);
        $this->setUser($user2);
        $this->setUser($user3);
        $this->setUser($user4);
        $this->setUser($user5);
        $this->setUser($user6);
        $this->setUser($user7);

        for ($i = 0; $i < 4; $i++) {
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
        }

        $stats = new get_report();

        $context = context_course::instance($course->id);

        $reportdata = $stats->block_edutechpreferences_report_data($course->id, $context);

        $this->assertEquals(80, $reportdata["stats"][0]["areas"][0]["count"]);
        $this->assertEquals(80, $reportdata["stats"][0]["areas"][1]["count"]);
        $this->assertEquals(80, $reportdata["stats"][0]["areas"][2]["count"]);

        $this->assertEquals(0, $reportdata["stats"][1]["areas"][0]["count"]);
        $this->assertEquals(0, $reportdata["stats"][1]["areas"][1]["count"]);
        $this->assertEquals(0, $reportdata["stats"][1]["areas"][2]["count"]);
        $this->assertEquals(80, $reportdata["stats"][1]["areas"][3]["count"]);
        $this->assertEquals(80, $reportdata["stats"][1]["areas"][4]["count"]);

        $this->assertEquals(0, $reportdata["stats"][2]["areas"][0]["count"]);

        $this->assertEquals(0, $reportdata["stats"][3]["areas"][0]["count"]);
        $this->assertEquals(0, $reportdata["stats"][3]["areas"][1]["count"]);
        $this->assertEquals(0, $reportdata["stats"][3]["areas"][2]["count"]);
        $this->assertEquals(0, $reportdata["stats"][3]["areas"][3]["count"]);
    }
}
