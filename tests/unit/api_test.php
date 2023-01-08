<?php

use block_edutechpreferences\api\api;

class api_test extends \advanced_testcase{
    public function test_get_api_list(){
        $api = new api();
        $apiresp = json_decode($api->block_edutechpreferences_get_list());

        $this->assertIsArray($apiresp);
        $this->assertGreaterThan(1, count($apiresp));
        $this->assertTrue(isset($apiresp[0]->id));
        $this->assertTrue(isset($apiresp[0]->preferences_are));
        $this->assertTrue(isset($apiresp[0]->preferences[0]->id));
        $this->assertTrue(isset($apiresp[0]->preferences[0]->description));
    }
}
