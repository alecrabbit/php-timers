<?php declare(strict_types=1);

namespace AlecRabbit\Formatters;

use AlecRabbit\Formatters\Core\Formattable;

interface TimerReportFormatterInterface
{
    /**
     * @param Formattable $formattable
     * @return string
     */
    public function format(Formattable $formattable): string;

    /**
     * @param \DateInterval $elapsed
     * @return string
     */
    public static function formatElapsed(\DateInterval $elapsed): string;
}
