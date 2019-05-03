<?php

use AlecRabbit\Accessories\Pretty;
use AlecRabbit\Timers\HRTimer;

require_once __DIR__ . '/../../vendor/autoload.php';


$count = 5;
const MICRO_SECONDS = 10000;

echo HRTimer::class . ' example', PHP_EOL;
echo 'Start...', PHP_EOL, 'wait ', Pretty::microseconds(MICRO_SECONDS * $count), PHP_EOL;

$timer = new HRTimer('new');
$timer->start();

for ($i = 0; $i < $count; $i++) {
    usleep(MICRO_SECONDS);
    echo '.';
    $timer->check();
}

echo PHP_EOL;
dump($timer->report()); // var_dump
// AlecRabbit\Reports\TimerReport {#4
//   #previous: 0.0
//   #creationTime: DateTimeImmutable @1556887510 {#2
//     date: 2019-05-03 15:45:10.849888 Europe/Kiev (+03:00)
//   }
//   #elapsed: DateInterval {#5
//     interval: - 00:00:00.053073
//     +"y": 0
//     +"m": 0
//     +"d": 0
//     +"h": 0
//     +"i": 0
//     +"s": 0
//     +"f": 0.053073
//     +"weekday": 0
//     +"weekday_behavior": 0
//     +"first_last_day_of": 0
//     +"invert": 1
//     +"days": 0
//     +"special_type": 0
//     +"special_amount": 0
//     +"have_weekday_relative": 0
//     +"have_special_relative": 0
//   }
//   #currentValue: 0.01035456
//   #avgValue: 0.0102885866
//   #minValue: 0.010178847
//   #maxValue: 0.010465863
//   #minValueIteration: 1
//   #maxValueIteration: 2
//   #count: 5
//   #name: "new"
//   #started: true
//   #stopped: false
// }
echo PHP_EOL;

echo (string)$timer->report();
// Timer[new]: Average: 10.3ms, Last: 10.3ms, Min(2): 10.1ms, Max(3): 10.4ms, Marks: 5, Elapsed: 73.9ms
echo PHP_EOL;
