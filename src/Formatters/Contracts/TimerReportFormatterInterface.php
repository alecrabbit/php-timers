<?php declare(strict_types=1);

namespace AlecRabbit\Formatters\Contracts;

interface TimerReportFormatterInterface extends FormatterInterface
{
    /**
     * @param \DateInterval $elapsed
     * @return string
     */
    public function formatElapsed(\DateInterval $elapsed): string;
}
