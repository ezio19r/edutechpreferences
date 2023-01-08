<?php


class access_test extends \advanced_testcase
{
    public function test_allow_teacher()
    {
        $this->resetAfterTest(true);

        $user = $this->getDataGenerator()->create_user();
        $course = $this->getDataGenerator()->create_course();

        $this->getDataGenerator()->enrol_user($user->id, $course->id, 'teacher');
        $this->setUser($user);

        $generator = $this->getDataGenerator()->create_block('edutechpreferences', array('course' => $course->id));

        require_once(dirname(dirname(__DIR__)) . '\coursereport.php');

        $report = new course_report();

        $view =  $report->init($course->id);

        $this->setUser(null);

        $contain = str_contains($view, get_string("totalstudents", "block_edutechpreferences"));

        $this->assertTrue($contain);
    }

    public function test_denegate_student()
    {
        $this->resetAfterTest(true);

        $user = $this->getDataGenerator()->create_user();
        $course = $this->getDataGenerator()->create_course();

        $this->getDataGenerator()->enrol_user($user->id, $course->id, 'student');
        $this->setUser($user);

        $generator = $this->getDataGenerator()->create_block('edutechpreferences', array('course' => $course->id));

        require_once(dirname(dirname(__DIR__)) . '\coursereport.php');

        $reports = new course_report();

        $view =  $reports->init($course->id);

        $this->setUser(null);

        $this->assertNull($view);
    }
}
