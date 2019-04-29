<?php

use AlecRabbit\Timers\Timer;

require_once __DIR__ . '/../../vendor/autoload.php';

echo 'Start...', PHP_EOL, 'wait 5 sec', PHP_EOL;
$timer = new Timer('new');
$count = 5;
$timer->start();
for ($i = 0; $i < $count; $i++) {
    sleep(1);
    echo '.';
    $timer->check();
}
echo "\n";
dump($timer->report()); // use var_dump
echo PHP_EOL;

echo (string)$timer->report();
// Timer:[new] Average: 1s, Last: 1s, Min(~): 1s, Max(~): 1s, Count: 5
echo PHP_EOL;
