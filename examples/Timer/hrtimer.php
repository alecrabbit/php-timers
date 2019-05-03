<?php

use AlecRabbit\Accessories\Pretty;
use AlecRabbit\Timers\HRTimer;
use AlecRabbit\Timers\Timer;

const MICRO_SECONDS = 10000;
require_once __DIR__ . '/../../vendor/autoload.php';

echo HRTimer::class . ' example', PHP_EOL;

$count = 5;
echo 'Start...', PHP_EOL, 'wait ', Pretty::microseconds(MICRO_SECONDS * $count), PHP_EOL;
$timer = new HRTimer('new');
$timer->start();
for ($i = 0; $i < $count; $i++) {
    usleep(MICRO_SECONDS);
    echo '.';
    $timer->check();
}
echo "\n";
dump($timer->report()); // use var_dump
echo PHP_EOL;

echo (string)$timer->report();
// Timer:[new] Average: 1s, Last: 1s, Min(~): 1s, Max(~): 1s, Count: 5
echo PHP_EOL;
