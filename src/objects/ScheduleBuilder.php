<?php
/**
 * Round-robin schedule generation implementation
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
 * Builder class for generating an immutable schedule object
 */
class ScheduleBuilder
{
    /**
     * @var array Contains teams used to generate schedule
     */
    protected $teams = [];

    /**
     * @var int|null How many rounds to generate
     */
    protected $rounds = null;

    /**
     * @var bool Whether to shuffle the teams or not
     */
    protected $shuffle = true;

    /**
     * @var int|null Seed to use for shuffle
     */
    protected $seed = null;

    /**
     * Set teams
     * 
     * @param array $teams
     * @return void
     */
    public function setTeams(array $teams)
    {
        $this->teams = $teams;
    }

    /**
     * Add a team to the teams array
     * 
     * @param mixed $team
     * @return void
     */
    public function addTeam($team)
    {
        $this->teams[] = $team;
    }

    /**
     * Remove a team from the teams array
     * 
     * @param mixed $team
     * @throws Exception if team does not exist in array
     * @return void
     */
    public function removeTeam($team)
    {
        $teamKeys = array_keys($this->teams, $team, true);
        if(!array_key_exists(0, $teamKeys)) {
            throw new Exception('Attempted removal of team that does not currently exist.');
        }
        $key = $teamKeys[0];
        unset($this->teams[$key]);
    }

    /**
     * Set number of rounds to generate
     * 
     * @param int $rounds
     * @return void
     */
    public function setRounds(int $rounds)
    {
        $this->rounds = $rounds;
    }

    /**
     * Shuffle array when generating schedule with optional seed
     * 
     * @param int|null $seed
     * @return void
     */
    public function shuffle(int $seed = null)
    {
        $this->shuffle = true;
        $this->seed = $seed;
    }

    /**
     * Do not shuffle array when generating schedule, resets seed
     * 
     * @return void
     */
    public function doNotShuffle()
    {
        $this->shuffle = false;
        $this->seed = null;
    }

    /**
     * Builds schedule based on properties
     * 
     * @return Schedule
     */
    public function build(): Schedule
    {
        return new Schedule(make_schedule($this->teams, $this->rounds, $this->shuffle, $this->seed));
    }
}
