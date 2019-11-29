#!/usr/bin/php
<?php
/**
 * Round-robin schedule generation implementation
 *
 * @author Michael P. Nitowski <mpnitowski@gmail.com>
 *
 * MIT License
 *
 * Copyright (c) 2017 Michael P. Nitowski
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

/*
 *  rr.php
 *  Command line script to generate round-robin schedules
 */

require_once('src/round-robin.php');

/* Setup */
$script_name = basename($argv[0]);
$usage = $script_name
  . ' [-h] -t teams [-r rounds] [-s] [-i seed] [-n name] [-d description] [-g]';
$help_text = <<<'TEXT'
    -t --teams t1, t2, t3... Teams to include in schedule.
    -r --rounds r The amount of rounds [default: number of teams].
    -s --shuffle Whether to shuffle teams or not.
    -i --seed s The random number generator seed to use.
    -n --name n Name of schedule.
    -d --description d Description of schedule.
    -g --include-generation-details Include timestamp and platform info.
    -h --help Display help.
TEXT;

const EOL = PHP_EOL;

/* Option parsing */
$options = getopt(
    't:r:si:n:d:gh', // Corresponds to long options
    [ // See help_text for descriptions
        'teams:',
        'rounds:',
        'shuffle',
        'seed:',
        'name:',
        'description:',
        'include-generation-details',
        'help'
    ]
);

$help = array_key_exists('h', $options) || array_key_exists('help', $options);

if($help) {
    echo 'Usage: ' . PHP_EOL . '  ' . $usage . PHP_EOL;
    echo 'Options: ' . PHP_EOL . $help_text . PHP_EOL;
    exit(0);
}

if(!(array_key_exists('t', $options) || array_key_exists('teams', $options))) {
    echo 'Usage: ' . PHP_EOL . '  ' . $usage . PHP_EOL;
    exit(0);
}

$teams = array_map('trim', explode(',', $options['teams'] ?? $options['t']));
$rounds = $options['rounds'] ?? $options['r'] ?? NULL;
if($rounds != NULL) {
    $rounds = (int) $rounds;
}

$shuffle = array_key_exists('s', $options)
    || array_key_exists('shuffle', $options);
$seed = $options['i'] ?? $options['seed'] ?? NULL;

$name = $options['name'] ?? $options['n'] ?? 'Schedule';
$description = $options['description'] ?? $options['d'] ?? NULL;
$include_generation_details =
    array_key_exists('include-generation-details', $options)
    || array_key_exists('g', $options);


/* Generate schedule */
$builder = new ScheduleBuilder($teams, $rounds);
$shuffle ? $builder->shuffle($seed) : $builder->doNotShuffle();
$schedule = $builder->build();

/* Output in markdown format */
echo '# ' . $name . EOL . EOL;

if($description) {
    echo wordwrap($description . EOL . EOL, 75, EOL);
}

echo '## Master Schedule' . EOL . EOL;

foreach($schedule as $round => $matchups) {
    echo '### Round ' . $round  . EOL;
    foreach($matchups as $matchup) {
        echo '  * '
            . ($matchup[0] ?? '_BYE_') . ' vs. ' . ($matchup[1] ?? '_BYE_')
            . EOL;
    }
    echo EOL;
}

echo '## Individual Schedules' . EOL . EOL;

foreach($teams as $team) {
    echo '### ' . $team . EOL;
    foreach($schedule($team) as $round => $contest) {
        echo '  ' . $round . '. '
            . ($contest['home'] ? '' : '@') . ($contest['team'] ?? '_BYE_')
            . EOL;
    }
    echo EOL;
}

if($include_generation_details) {
    echo '## Generation Details' . EOL . EOL;
    echo 'Datetime: ' . date('l, F j, Y g:i A', time())
      . ' (' . gmdate('Y-m-d\TH:i:s\Z', time()) . ')' . EOL . EOL;
    echo 'Platform: '
      . 'PHP ' . phpversion() . ' on '
      . implode(
            ' ',
            // OS details
            [php_uname('s'), php_uname('r'), php_uname('v'), php_uname('m')]
        )
      . EOL;

    if(!is_null($seed)) {
      echo EOL . 'Seed: ' . $seed . EOL;
    }
}
