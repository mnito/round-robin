<?php

use \PHPUnit\Framework\TestCase;

class ToMarkdownTest extends TestCase
{
    private function getSchedule(): Schedule
    {
        $teams = ['Team 1', 'Team 2', 'Team 3', 'Team 4'];
        $builder = new ScheduleBuilder($teams, 2);
        $builder->doNotShuffle();
        $schedule = $builder->build();
        return $schedule;
    }

    public function testFormatsToMarkdown()
    {
        $schedule_markdown = schedule_to_markdown($this->getSchedule());

        $expected = <<<EOD
## Full Schedule

### Round 1
  * Team 3 vs. Team 1
  * Team 4 vs. Team 2

### Round 2
  * Team 1 vs. Team 4
  * Team 3 vs. Team 2

## Individual Schedules

### Team 3
  1. @Team 1
  2. @Team 2

### Team 1
  1. Team 3
  2. @Team 4

### Team 4
  1. @Team 2
  2. Team 1

### Team 2
  1. Team 4
  2. Team 3


EOD;
        echo $expected;

        $this->assertEquals($schedule_markdown, $expected);
    }

    public function testFormatsToMarkdownWithNameOnly()
    {
        $schedule_markdown = schedule_to_markdown($this->getSchedule(), 'A Schedule');

        $expected = <<<EOD
# A Schedule

## Full Schedule

### Round 1
  * Team 3 vs. Team 1
  * Team 4 vs. Team 2

### Round 2
  * Team 1 vs. Team 4
  * Team 3 vs. Team 2

## Individual Schedules

### Team 3
  1. @Team 1
  2. @Team 2

### Team 1
  1. Team 3
  2. @Team 4

### Team 4
  1. @Team 2
  2. Team 1

### Team 2
  1. Team 4
  2. Team 3


EOD;
        echo $expected;

        $this->assertEquals($schedule_markdown, $expected);
    }

    public function testFormatsToMarkdownWithNameAndDescription()
    {
        $schedule_markdown = schedule_to_markdown($this->getSchedule(), $name = 'A Schedule', $description='A description');

        $expected = <<<EOD
# A Schedule

A description

## Full Schedule

### Round 1
  * Team 3 vs. Team 1
  * Team 4 vs. Team 2

### Round 2
  * Team 1 vs. Team 4
  * Team 3 vs. Team 2

## Individual Schedules

### Team 3
  1. @Team 1
  2. @Team 2

### Team 1
  1. Team 3
  2. @Team 4

### Team 4
  1. @Team 2
  2. Team 1

### Team 2
  1. Team 4
  2. Team 3


EOD;
        echo $expected;

        $this->assertEquals($schedule_markdown, $expected);
    }
}
