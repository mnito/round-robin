<?php
/**
 * Round-robin schedule generation library
 * 
 * @author Michael P. Nitowski <mpnitowski@gmail.com>
 * 
 * MIT License
 *
 * Copyright (c) 2016 Michael P. Nitowski
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:

 * The above copyright notice and this permission notice shall be included in 
 * all copies or substantial portions of the Software.

 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

/**
 * Generate a round-robin schedule from an array of teams
 * 
 * @param array $teams An array of teams which may be any valid type
 * 
 * @param int $rounds The number of rounds, will default to the number of 
 *     rounds required for each contestant to meet all other contestants
 * 
 * @param bool $shuffle Whether to shuffle the teams before generating the 
 *     schedule, default is true
 * 
 * @param int $seed Seed to use for shuffling if shuffle is enabled, if no seed 
 *     will use random_int with PHP_INT_MIN and PHP_INT_MAX is no seed is 
 *     provided
 * 
 * @return array An array of rounds, in the format of $round => $matchups, 
 *     where each matchup has only two elements with the two teams as elements 
 *     [0] and [1] or for a $teams array with an odd element count, may have 
 *     one of these elements as null to signify a bye for the other actual team 
 *     element in the matchup array
 */
function schedule(array $teams, int $rounds = null, bool $shuffle = true, int $seed = null): array
{
    $teamCount = count($teams);
    if($teamCount < 2) {
        return [];
    }
    //Account for odd number of teams by adding a bye
    if($teamCount % 2 === 1) {
        array_push($teams, null);
        $teamCount += 1;
    }
    if($shuffle) {
        //Seed shuffle with random_int for better randomness if seed is null
        srand($seed ?? random_int(PHP_INT_MIN, PHP_INT_MAX));
        shuffle($teams);
    } elseif(!is_null($seed)) {
        //Generate friendly notice that seed is set but shuffle is set to false
        trigger_error('Seed parameter has no effect when shuffle parameter is set to false');
    }
    $halfTeamCount = $teamCount / 2;
    if($rounds === null) {
        $rounds = $teamCount - 1;
    }
    $schedule = [];
    for($round = 1; $round <= $rounds; $round += 1) {
        foreach($teams as $key => $team) {
            if($key >= $halfTeamCount) {
                break;
            }
            $team1 = $team;
            $team2 = $teams[$key + $halfTeamCount];
            //Home-away swapping
            $matchup = $round % 2 === 0 ? [$team1, $team2] : [$team2, $team1];
            $schedule[$round][] = $matchup;
        }
        rotate($teams);
    }
    return $schedule;
}
