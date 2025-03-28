<?php

include __DIR__ . '/../src/round-robin.php';

function debug_rotate_print(array $array): void
{
    $count = count($array);
    $even = $count % 2 === 0;
    $arrays = array_chunk($array, $even ?  $count / 2 : ($count / 2) + 1);
    if(!$even) {
        array_unshift($arrays[1], ' ');
    }
    foreach($arrays as $half) {
        $string = implode(' ', $half).PHP_EOL;
        echo $string;
    }
}

function debug_rotate_print_full_rotation(array $array, $headers = true): void
{
    echo $headers ? 'Initial'.PHP_EOL : PHP_EOL;
    debug_rotate_print($array);
    $counter = count($array);
    for($i = 1; $i < $counter; $i++) {
        echo $headers ? 'Rotation '.$i.PHP_EOL : PHP_EOL;
        rotate($array);
        debug_rotate_print($array);
    }
    echo PHP_EOL;
}

$headers = isset($argv[1]) ? strtolower($argv[1]) !== 'false' : true;
$mode = php_sapi_name();
if($mode !== 'cli') {
    $headers = ($temp = filter_input(INPUT_GET, 'headers')) ? $temp !== 'false' : true;
    echo '<pre>'.PHP_EOL;
}
echo 'Even: '.PHP_EOL;
$even = [1, 2, 3, 4, 5, 6, 7, 8];
debug_rotate_print_full_rotation($even, $headers);
echo 'Odd: '.PHP_EOL;
$odd = [1, 2, 3, 4, 5, 6, 7];
debug_rotate_print_full_rotation($odd, $headers);
if($mode !== 'cli') {
    echo '</pre>';
}
