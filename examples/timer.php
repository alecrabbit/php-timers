<?php

use AlecRabbit\Accessories\Pretty;
use AlecRabbit\Timers\Timer;

require_once __DIR__ . '/../vendor/autoload.php';

const MICRO_SECONDS = 1000000;
$count = 5;

echo Timer::class . ' example', PHP_EOL;
echo 'Start...', PHP_EOL, 'wait ', Pretty::microseconds(MICRO_SECONDS * $count), PHP_EOL;

$timer = new Timer('new');
$elapsed = new Timer('elapsed');
$timer->start();
for ($i = 0; $i < $count; $i++) {
    usleep(MICRO_SECONDS);
    echo '.';
    $timer->check();
}
echo "\n";
dump($timer->report()); // use var_dump
// AlecRabbit\Reports\TimerReport {#4
//   #previous: 0.0
//   #creationTime: DateTimeImmutable @1556887456 {#2
//     date: 2019-05-03 15:44:16.245626 Europe/Kiev (+03:00)
//   }
//   #elapsed: DateInterval {#5
//     interval: - 00:00:05.00787
//     +"y": 0
//     +"m": 0
//     +"d": 0
//     +"h": 0
//     +"i": 0
//     +"s": 5
//     +"f": 0.00787
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
//   #currentValue: 1.00056791305542
//   #avgValue: 1.000547361373901
//   #minValue: 1.000500917434692
//   #maxValue: 1.000637054443359
//   #minValueIteration: 2
//   #maxValueIteration: 3
//   #count: 5
//   #name: "new"
//   #started: true
//   #stopped: false
// }
echo PHP_EOL;
echo 'Pause 2 sec...', PHP_EOL;
sleep(2);

echo (string)$timer->report(false); // don't rebuild report
// Timer[new]: Average: 1.0s, Last: 1.0s, Min(2): 1.0s, Max(3): 1.0s, Marks: 5, Elapsed: 5.0s
echo (string)$timer->report(); // rebuild report
// Note: elapsed time increased by 2 sec
// Timer[new]: Average: 1.0s, Last: 1.0s, Min(2): 1.0s, Max(3): 1.0s, Marks: 5, Elapsed: 7.1s
echo PHP_EOL;
echo (string)$elapsed->report();
// Timer[elapsed]: Elapsed: 7.1s
echo PHP_EOL;
