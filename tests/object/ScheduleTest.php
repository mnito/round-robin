<?php

use \PHPUnit\Framework\TestCase;

class ScheduleTest extends TestCase
{
    public function testMasterScheduleEquivalency()
    {
        $teams = ['Team 1', 'Team 2', 'Team 3', 'Team 4'];
        $scheduleBuilder = new ScheduleBuilder();
        $scheduleBuilder->setTeams($teams);
        $scheduleBuilder->doNotShuffle();
        $schedule = $scheduleBuilder->build();
        $this->assertEquals($schedule->master(), $schedule->get());
        $this->assertEquals($schedule->master(), $schedule());
        $scheduleArray = [];
        foreach($schedule as $round => $matchups) {
            $scheduleArray[$round] = $matchups;
        }
        $this->assertEquals($schedule->master(), $scheduleArray);
    }

    public function testIndividualScheduleEquivalency()
    {
        $teams = ['Team 1', 'Team 2', 'Team 3', 'Team 4'];
        $scheduleBuilder = new ScheduleBuilder();
        $scheduleBuilder->setTeams($teams);
        $schedule = $scheduleBuilder->build();
        $this->assertEquals($schedule->forTeam('Team 1'), $schedule->get('Team 1'));
        $this->assertEquals($schedule->forTeam('Team 1'), $schedule('Team 1'));
    }

    public function testIndividualSchedule1()
    {
        $teams = ['Team 1', 'Team 2', 'Team 3', 'Team 4', 'Team 5'];
        $scheduleBuilder = new ScheduleBuilder();
        $scheduleBuilder->setTeams($teams);
        $schedule = $scheduleBuilder->build();
        $teamSchedule = [];
        foreach($schedule as $round => $matchups) {
            foreach($matchups as $matchup) {
                $team1 = $matchup[0];
                $team2 = $matchup[1];
                $teamSchedule[$team1][$round] = ['team' => $team2, 'home' => false];
                $teamSchedule[$team2][$round] = ['team' => $team1, 'home' => true];
            }
        }
        $this->assertEquals($teamSchedule['Team 1'], $schedule->forTeam('Team 1'));
    }

    public function testIndividualSchedule2()
    {
        $master = [
            1 => [['Team 1', 'Team 2'], [null, 'Team 3']],
            2 => [['Team 2', null], ['Team 3', 'Team 1']]
        ];
        $expected = [
            1 => ['team' => 'Team 1', 'home' => true],
            2=> ['team' => null, 'home' => false]
        ];
        $schedule = new Schedule($master);
        $this->assertEquals($expected, $schedule->forTeam('Team 2'));
    }
}
