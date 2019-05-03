<?php declare(strict_types=1);

namespace AlecRabbit\Reports\Contracts;

use AlecRabbit\Formatters\TimerReportFormatterInterface;
use AlecRabbit\Timers\Contracts\TimerValuesInterface;

interface TimerReportInterface extends TimerValuesInterface
{
    public static function getFormatter(): TimerReportFormatterInterface;

    public static function setFormatter(TimerReportFormatterInterface $formatter): void;
}
