<?php

use \PHPUnit\Framework\TestCase;

class HighLevelTest extends TestCase
{
    public function testArbitrarySchedule()
    {
        $scheduleBuilder = new ScheduleBuilder();
        $teams = range(0, 9);
        $teamSchedules = [];
        $scheduleBuilder->setTeams($teams);
        $schedule = $scheduleBuilder->build();
        foreach($schedule as $round) {
            $roundTeams = [];
            foreach($round as $matchup) {
                $team1 = $matchup[0];
                $team2 = $matchup[1];
                $teamSchedules[$team1][] = $team2;
                $teamSchedules[$team2][] = $team1;
                $roundTeams[] = $team1;
                $roundTeams[] = $team2;
            }
            sort($roundTeams);
            //Ensure all teams are included each round only once
            $this->assertEquals($teams, $roundTeams);
        }
        foreach($teamSchedules as $team => $teamSchedule) {
            //Unset current team from matchup base to generate expected matchup array
            $temp = $teams;
            unset($temp[$team]);
            $expectedMatchups = array_values($temp);
            sort($teamSchedule);
            $actualMatchups = array_values($teamSchedule);
            //Ensure each team plays every other team only once
            $this->assertEquals($expectedMatchups, $actualMatchups);
        }
    }

    public function testByeAddition()
    {
        $scheduleBuilder = new ScheduleBuilder();
        $scheduleBuilder->addTeam('Team 1');
        $scheduleBuilder->addTeam('Team 2');
        $scheduleBuilder->addTeam('Team 3');
        $scheduleBuilder->doNotShuffle();
        $schedule = $scheduleBuilder->build();
        //Make sure initial array wasn't changed
        $teams = $schedule->teams();
        sort($teams);
        $this->assertEquals(['Team 1', 'Team 2', 'Team 3'], $teams);
        $singleNullCount = 0;
        foreach($schedule->get() as $round) {
            foreach($round as $matchup) {
                //xor to make sure only a single element is null
                if(is_null($matchup[0]) xor is_null($matchup[1])) {
                    $singleNullCount += 1;
                }
            }
        }
        $this->assertEquals(3, $singleNullCount);
    }

    public function testDefaultScheduleRoundCount()
    {
        $scheduleBuilder = new ScheduleBuilder();
        $teams = ['Team 1', 'Team 2', 'Team 3', 'Team 4'];
        $scheduleBuilder->setTeams($teams);
        $schedule = $scheduleBuilder->build()->master();
        $this->assertCount(3, $schedule);
        $teams2 = range(1, 11);
        array_walk($teams2, function(&$value) { $value = 'Team '.$value; });
        $scheduleBuilder->setTeams($teams2);
        $schedule2 = $scheduleBuilder->build();
        $this->assertCount(11, $schedule2);
    }

    public function testHomeAwaySchedule()
    {
        $teams = ['Team 1', 'Team 2', 'Team 3', 'Team 4'];
        $factor = (($count = count($teams)) % 2 === 0 ? $count - 1 : $count);
        $scheduleBuilder = new ScheduleBuilder($teams);
        $scheduleBuilder->setRounds($factor * 2);
        $schedule = $scheduleBuilder->build()->getIterator();
        for($i = 1; $i < $factor; $i += 1) {
            $initial = $schedule[$i];
            $opposite = $schedule[$i + $factor];
            foreach($initial as $key => $matchup) {
                $this->assertEquals($matchup[0], $opposite[$key][1]);
                $this->assertEquals($matchup[1], $opposite[$key][0]);
            }
        }
    }

    public function testNoTeams()
    {
        $scheduleBuilder = new ScheduleBuilder();
        $this->assertEmpty($scheduleBuilder->build()->master());
    }

    public function testRoundMatchupCount()
    {
        $teams = ['Team 1', 'Team 2', 'Team 3', 'Team 4', 'Team 5'];
        $schedule = schedule($teams, 14);
        foreach($schedule as $round) {
            $this->assertCount(3, $round);
        }
        $teams2 = ['Team 1', 'Team 2', 'Team 3', 'Team 4', 'Team 5', 'Team 6', 'Team 7', 'Team 8'];
        $schedule2 = schedule($teams2, 3, true, 3);
        foreach($schedule2 as $round) {
            $this->assertCount(4, $round);
        }
    }

    public function testSingleTeam()
    {
        $scheduleBuilder = new ScheduleBuilder();
        $scheduleBuilder->addTeam('Team 1');
        $this->assertEmpty($scheduleBuilder->build()->get());
    }

    public function testZeroRounds()
    {
        $scheduleBuilder = new ScheduleBuilder();
        $scheduleBuilder->setTeams(['Team 1', 'Team 2', 'Team 3']);
        $scheduleBuilder->setRounds(0);
        $this->assertEmpty($scheduleBuilder->build()->master());
    }

    public function testWraparound()
    {
        $teams = ['Team 1', 'Team 2', 'Team 3', 'Team 4'];
        $factor = (($count = count($teams)) % 2 === 0 ? $count - 1 : $count);
        $scheduleBuilder = new ScheduleBuilder($teams);
        $scheduleBuilder->setRounds($factor * 3);
        $schedule = $scheduleBuilder->build()->get();
        for($i = 1; $i < $factor; $i += 1) {
            $initial = $schedule[$i];
            $opposite = $schedule[$i + $factor * 2];
            foreach($initial as $key => $matchup) {
                $this->assertEquals($matchup[0], $opposite[$key][0]);
                $this->assertEquals($matchup[1], $opposite[$key][1]);
            }
        }
    }
}
