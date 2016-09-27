<?php

use PHPUnit\Framework\TestCase;

class MakeScheduleTest extends TestCase
{
    public function testArbitrarySchedule()
    {
        $teams = range(0, 9);
        $teamSchedules = [];
        $schedule = make_schedule($teams);
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
        $teams = ['Team 1', 'Team 2', 'Team 3'];
        $schedule = make_schedule($teams, null, false);
        //Make sure initial array wasn't changed
        $this->assertEquals(['Team 1', 'Team 2', 'Team 3'], $teams);
        $singleNullCount = 0;
        foreach($schedule as $round) {
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
        $teams = ['Team 1', 'Team 2', 'Team 3', 'Team 4'];
        $schedule = make_schedule($teams);
        $this->assertCount(3, $schedule);
        $teams2 = range(1, 11);
        array_walk($teams2, function(&$value) { $value = 'Team '.$value; });
        $schedule2 = make_schedule($teams2);
        $this->assertCount(11, $schedule2);
    }

    public function testHomeAwaySchedule()
    {
        $teams = ['Team 1', 'Team 2', 'Team 3', 'Team 4'];
        $factor = ($temp = count($teams) % 2 === 0) ? $temp - 1 : $temp;
        $schedule = make_schedule($teams, $factor * 2);
        for($i = 1; $i < $factor; $i += 1) {
            $initial = $schedule[$i];
            $opposite = $schedule[$i + $factor];
            foreach($initial as $key => $matchup) {
                $this->assertEquals($matchup[0], $opposite[$key][1]);
                $this->assertEquals($matchup[1], $opposite[$key][0]);
            }
        }
    }

    public function testManualSeeding()
    {
        $teams = ['Team 1', 'Team 2', 'Team 3', 'Team 4', 'Team 5', 'Team 6', 'Team 7'];
        $schedule1 = make_schedule($teams, 18, true, 89);
        $schedule2 = make_schedule($teams, 18, true, 89);
        $this->assertEquals($schedule1, $schedule2);
        $schedule3 = make_schedule($teams, null, true, 44);
        $schedule4 = make_schedule($teams, null, true, 44);
        $this->assertEquals($schedule3, $schedule4);
    }

    public function testNoShuffle()
    {
        $teams = ['Team 1', 'Team 2', 'Team 3', 'Team 4'];
        $schedule1 = make_schedule($teams, null, false);
        $schedule2 = make_schedule($teams, null, false);
        $this->assertEquals($schedule1, $schedule2);
        $schedule3 = make_schedule($teams, 2, false);
        $schedule4 = make_schedule($teams, 2, false);
        $this->assertEquals($schedule3, $schedule4);
    }

    public function testNoTeams()
    {
        $this->assertEmpty(make_schedule([]));
    }

    public function testRoundMatchupCount()
    {
        $teams = ['Team 1', 'Team 2', 'Team 3', 'Team 4', 'Team 5'];
        $schedule = make_schedule($teams, 14);
        foreach($schedule as $round) {
            $this->assertCount(3, $round);
        }
        $teams2 = ['Team 1', 'Team 2', 'Team 3', 'Team 4', 'Team 5', 'Team 6', 'Team 7', 'Team 8'];
        $schedule2 = make_schedule($teams2, 3, true, 3);
        foreach($schedule2 as $round) {
            $this->assertCount(4, $round);
        }
    }

    public function testSingleTeam()
    {
        $this->assertEmpty(make_schedule(['Team 1']));
    }

    public function testZeroRounds()
    {
        $this->assertEmpty(make_schedule(['Team 1', 'Team 2', 'Team 3'], 0));
    }

    public function testWraparound()
    {
        $teams = ['Team 1', 'Team 2', 'Team 3', 'Team 4'];
        $factor = ($temp = count($teams) % 2 === 0) ? $temp - 1 : $temp;
        $schedule = make_schedule($teams, $factor * 3);
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
