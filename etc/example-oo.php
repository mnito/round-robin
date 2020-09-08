<?php
include_once '../src/round-robin.php';

$teams = ['The 1st', '2 Good', 'We 3', '4ward', '5thTeam'];

$scheduleBuilder = new ScheduleBuilder();
$scheduleBuilder->setTeams($teams);
$scheduleBuilder->setRounds((($count = count($teams)) % 2 === 0 ? $count - 1 : $count) * 2);
$scheduleBuilder->shuffle(18);
$schedule = $scheduleBuilder->build();

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Schedule Example</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <h1>Example</h1>
        <p>
            This example generates a determinate schedule where each contestant meets each 
            other twice, once home and once away.
        </p>
        <h2>Sample Full Schedule</h2>
        <?php foreach($schedule as $round => $matchups){ ?>
        <h3>Round <?=$round?></h3>
        <ul>
        <?php foreach($matchups as $matchup) { ?>
            <li><?=$matchup[0] ?? '*BYE*'?> vs. <?=$matchup[1] ?? '*BYE*'?></li>
        <?php } ?>
        </ul>
        <?php } ?>
        <h2>Sample Team Schedules</h2>
        <?php foreach($teams as $team) { ?>
        <h3><?=$team?></h3>
        <ol>
        <?php foreach($schedule($team) as $contest) { ?>
            <li><?=(($contest['home'] ? '' : '@').($contest['team'] ?? '*BYE*'))?></li>
        <?php } ?>
        </ol>
        <?php } ?>
    </body>
</html>
