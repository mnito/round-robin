<?php

use \PHPUnit\Framework\TestCase;

class ScheduleBuilderTest extends TestCase
{
    public function testManualSeeding()
    {
        $teams = ['Team 1', 'Team 2', 'Team 3', 'Team 4', 'Team 5', 'Team 6', 'Team 7'];
        $scheduleBuilder = new ScheduleBuilder();
        $scheduleBuilder->setTeams($teams);
        $scheduleBuilder->setRounds(18);
        $scheduleBuilder->shuffle(89);
        $this->assertEquals($scheduleBuilder->build(), $scheduleBuilder->build());
        $scheduleBuilder->setRounds(null);
        $this->assertEquals($scheduleBuilder->build(), $scheduleBuilder->build());
    }

    public function testNoShuffle()
    {
        $teams = ['Team 1', 'Team 2', 'Team 3', 'Team 4'];
        $scheduleBuilder = new ScheduleBuilder();
        $scheduleBuilder->setTeams($teams);
        $scheduleBuilder->doNotShuffle();
        $schedule1 = $scheduleBuilder->build();
        $schedule2 = $scheduleBuilder->build();
        $this->assertEquals($schedule1, $schedule2);
        $scheduleBuilder->setRounds(2);
        $schedule3 = $scheduleBuilder->build();
        $schedule4 = $scheduleBuilder->build();
        $this->assertEquals($schedule3, $schedule4);
    }

    public function testTeamAddition()
    {
        $scheduleBuilder = new ScheduleBuilder();
        $scheduleBuilder->addTeam('Team 1');
        $scheduleBuilder->addTeam('Team 2');
        $scheduleBuilder->addTeam('Team 3');
        $teams1 = $scheduleBuilder->build()->teams();
        sort($teams1);
        $this->assertEquals(['Team 1', 'Team 2', 'Team 3'], $teams1);
    }

    public function testTeamRemoval()
    {
        $scheduleBuilder = new ScheduleBuilder();
        $scheduleBuilder->addTeam('Team 1');
        $scheduleBuilder->addTeam('Team 2');
        $scheduleBuilder->addTeam('Team 3');
        $scheduleBuilder->removeTeam('Team 2');
        $teams1 = $scheduleBuilder->build()->teams();
        sort($teams1);
        $this->assertEquals(['Team 1', 'Team 3'], $teams1);
    }

    public function testInvalidTeamRemoval()
    {
        $scheduleBuilder = new ScheduleBuilder();
        try {
            $scheduleBuilder->removeTeam('Team 1');
            $this->fail('Removal of nonexistent team failed to throw an exception.');
        } catch (Exception $e) {}
        $scheduleBuilder->addTeam('Team 1');
        try {
            $scheduleBuilder->removeTeam('Team 2');
            $this->fail('Removal of nonexistent team failed to throw an exception.');
        } catch (Exception $e) {}
        $this->assertTrue(true);
    }

    public function testRounds()
    {
        $scheduleBuilder = new ScheduleBuilder();
        $scheduleBuilder->setRounds(14);
        $scheduleBuilder->setTeams(['Team 1', 'Team 2', 'Team 3']);
        $this->assertCount(14, $scheduleBuilder->build());
    }

    public function testRoundDefaultBehavior()
    {
        $scheduleBuilder = new ScheduleBuilder();
        $scheduleBuilder->setTeams(['Team 1', 'Team 2', 'Team 3', 'Team 4']);
        $this->assertCount(3, $scheduleBuilder->build());
    }

    public function testEnoughRounds()
    {
        $scheduleBuilder = new ScheduleBuilder();
        $scheduleBuilder->setTeams(['Team 1', 'Team 2', 'Team 3', 'Team 4']);
        $scheduleBuilder->setRounds(16);
        $scheduleBuilder->enoughRounds();
        $this->assertCount(3, $scheduleBuilder->build());
    }

    public function testNullRounds()
    {
        $scheduleBuilder = new ScheduleBuilder();
        $scheduleBuilder->setTeams(['Team 1', 'Team 2', 'Team 3', 'Team 4']);
        $scheduleBuilder->setRounds(null);
        $this->assertCount(3, $scheduleBuilder->build());
    }

    public function testTeamSetting()
    {
        $scheduleBuilder = new ScheduleBuilder();
        $scheduleBuilder->setTeams(['Team 1', 'Team 2']);
        $scheduleBuilder->setTeams(['Team 3', 'Team 4']);
        $teams = $scheduleBuilder->build()->teams();
        sort($teams);
        $this->assertEquals(['Team 3', 'Team 4'], $teams);
    }
}
