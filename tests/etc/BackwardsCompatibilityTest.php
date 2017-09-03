<?php

use \PHPUnit\Framework\TestCase;

class BackwardsCompatibilityTest extends TestCase
{
    public function testScheduleBackwardsCompatibility()
    {
        $teams = ['Team 1', 'Team 2', 'Team 3', 'Team 4', 'Team 5'];
        $this->assertEquals(schedule($teams, 17, false), make_schedule($teams, 17, false));
        $this->assertEquals(schedule($teams, null, true, 16), make_schedule($teams, null, true, 16));
    }
}
