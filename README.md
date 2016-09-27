# round-robin
[![Build Status](https://travis-ci.org/mnito/round-robin.svg?branch=master)](https://travis-ci.org/mnito/round-robin)

Round-robin schedule generation reference implementation for PHP 7 licensed under the MIT license

## Features

- Efficient schedule generation enabled by an efficient round-robin rotation function
- Ability to generate an arbitrary number of rounds
- Support for any number of teams by adding a bye for odd-numbered team counts
- Simple, concise implementation for easy analysis of the algorithm
- Unit tested
- Documented
- Modern PHP 7 code
- Object-oriented and procedural APIs

## Basic Usage


### Generating Common Schedules

#### Generate a random schedule where each player meets every other player once:

```php
$teams = ['The 1st', '2 Good', 'We 3', '4ward'];
$scheduleBuilder = new ScheduleBuilder($teams);
$schedule = $scheduleBuilder->build();
```

or

```php
$teams = ['The 1st', '2 Good', 'We 3', '4ward'];
$schedule = schedule($teams);
```


#### Generate a random home-away schedule where each player meets every other player twice, once at home and once away, using the $rounds integer parameter:

```php
$teams = ['The 1st', '2 Good', 'We 3', '4ward'];
$scheduleBuilder = new ScheduleBuilder($teams, $rounds);
$schedule = $scheduleBuilder->build();
```

or

```php
$teams = ['The 1st', '2 Good', 'We 3', '4ward'];
$rounds = (($count = count($teams)) % 2 === 0 ? $count - 1 : $count) * 2;
$schedule = schedule($teams, $rounds);
```

#### Generate a schedule without randomly shuffling the teams using the $shuffle boolean parameter:

```php
$teams = ['The 1st', '2 Good', 'We 3', '4ward'];
$scheduleBuilder = new ScheduleBuilder($teams);
$scheduleBuilder->doNotShuffle();
$schedule = $scheduleBuilder->build();
```

or

```php
$teams = ['The 1st', '2 Good', 'We 3', '4ward'];
$schedule = schedule($teams, null, false);
```

#### Use your own seed with the $seed integer parameter for predetermined shuffling:
```php
$teams = ['The 1st', '2 Good', 'We 3', '4ward'];
$scheduleBuilder = new ScheduleBuilder($teams);
$scheduleBuilder->shuffle(89);
$schedule = $scheduleBuilder->build();
```
or

```php
$teams = ['The 1st', '2 Good', 'We 3', '4ward'];
$schedule = schedule($teams, null, true, 89);
```

### Looping Through A Schedule

#### Looping Through the Master Schedule

Setup:

```php
<?php
$teams = ['The 1st', '2 Good', 'We 3', '4ward'];
$schedule = schedule($teams, null, true, 89);
```

or

```php
$scheduleBuilder = new ScheduleBuilder();
$scheduleBuilder->setTeams($teams);
$scheduleBuilder->setRounds(10);
$scheduleBuilder->doNotShuffle();
$schedule = $scheduleBuilder->build();
```
?>

```php
<?php foreach($schedule as $round => $matchups){ ?>
    <h3>Round <?=$round?></h3>
    <ul>
    <?php foreach($matchups as $matchup) { ?>
        <li><?=$matchup[0] ?? '*BYE*'?> vs. <?=$matchup[1] ?? '*BYE*'?></li>
    <?php } ?>
    </ul>
<?php } ?>
```

#### Looping Through Team Schedules
```php
<?php

$scheduleBuilder = new ScheduleBuilder($teams, 10);
$scheduleBuilder->shuffle(18);
$schedule = $scheduleBuilder->build();
?>


<?php foreach($teams as $team) { ?>
    <h3><?=$team?></h3>
    <ol>
    <?php foreach($schedule($team) as $contest) { ?>
        <li><?=(($contest['home'] ? '' : '@').($contest['team'] ?? '*BYE*'))?></li>
    <?php } ?>
    </ol>
<?php } ?>
```

###

## License

MIT License

## Author

Michael P. Nitowski <[mpnitowski@gmail.com](mailto:mpnitowski@gmail.com)>
    (Twitter: [@mikenitowski](https://twitter.com/mikenitowski),
     Web: [mnito.co](http://mnito.co))
