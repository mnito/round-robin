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
    protected $rounds;

    /**
     * @var bool Whether to shuffle the teams or not
     */
    protected $shuffle = true;

    /**
     * @var int|null Seed to use for shuffle
     */
    protected $seed;

    /**
     * Set teams and rounds at construction
     */
    public function __construct(array $teams = [], ?int $rounds = null)
    {
        $this->setTeams($teams);
        $this->setRounds($rounds);
    }

    /**
     * Set teams
     */
    public function setTeams(array $teams): void
    {
        $this->teams = $teams;
    }

    /**
     * Add a team to the teams array
     *
     * @param mixed $team
     */
    public function addTeam($team): void
    {
        $this->teams[] = $team;
    }

    /**
     * Remove a team from the teams array
     *
     * @param mixed $team
     * @throws Exception if team does not exist in array
     */
    public function removeTeam($team): void
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
     */
    public function setRounds(?int $rounds = null): void
    {
        $this->rounds = $rounds;
    }

    /**
     * Set rounds to amount for each team to meet every other team
     */
    public function enoughRounds(): void
    {
        $this->setRounds(null);
    }

    /**
     * Shuffle array when generating schedule with optional seed
     */
    public function shuffle(?int $seed = null): void
    {
        $this->shuffle = true;
        $this->seed = $seed;
    }

    /**
     * Do not shuffle array when generating schedule, resets seed
     */
    public function doNotShuffle(): void
    {
        $this->shuffle = false;
        $this->seed = null;
    }

    /**
     * Builds schedule based on properties
     */
    public function build(): Schedule
    {
        return new Schedule(make_schedule($this->teams, $this->rounds, $this->shuffle, $this->seed));
    }
}
