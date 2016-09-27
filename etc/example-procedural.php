<?php
include_once '../src/round-robin.php';

$teams = ['The 1st', '2 Good', 'We 3', '4ward'];

$schedule = schedule($teams, (($count = count($teams)) % 2 === 0 ? $count - 1 : $count) * 2);

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
            This example generates a schedule where each contestant meets each 
            other twice, once home and once away.
        </p>
        <h2>Sample Schedule</h2>
        <?php foreach($schedule as $round => $matchups){ ?>
        <h3>Round <?=$round?></h3>
        <ul>
        <?php foreach($matchups as $matchup) { ?>
            <li><?=$matchup[0] ?? '*BYE*'?> vs. <?=$matchup[1] ?? '*BYE*'?></li>
        <?php } ?>
        </ul>
        <?php } ?>
    </body>
</html>
