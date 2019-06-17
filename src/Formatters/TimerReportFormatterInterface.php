<?php declare(strict_types=1);

namespace AlecRabbit\Formatters;

use AlecRabbit\Formatters\Contracts\FormatterInterface;

interface TimerReportFormatterInterface extends FormatterInterface
{
    /**
     * @param \DateInterval $elapsed
     * @return string
     */
    public static function formatElapsed(\DateInterval $elapsed): string;
}
