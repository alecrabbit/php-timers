<?php declare(strict_types=1);

namespace AlecRabbit\Formatters;

use AlecRabbit\Accessories\Pretty;
use AlecRabbit\Formatters\Core\Formattable;
use AlecRabbit\Formatters\Core\ReportFormatter;
use AlecRabbit\Reports\TimerReport;
use AlecRabbit\Timers\Contracts\TimerStrings;
use function AlecRabbit\typeOf;
use Carbon\CarbonInterval;
use const AlecRabbit\Traits\Constants\DEFAULT_NAME;

class TimerReportFormatter extends ReportFormatter implements TimerStrings
{
    protected const MILLISECONDS_THRESHOLD = 10000;

    /** {@inheritdoc} */
    public function format(Formattable $formattable): string
    {
        if ($formattable instanceof TimerReport) {
            if (0 === $formattable->getCount() && DEFAULT_NAME === $formattable->getName()) {
                return $this->simple($formattable);
            }
            return $this->full($formattable);
        }
        throw new \RuntimeException(
            'Instance of [' . TimerReport::class . '] expected, [' . typeOf($formattable) . '] given.'
        );
    }

    /**
     * @param TimerReport $report
     * @param bool $eol
     * @return string
     */
    protected function simple(TimerReport $report, bool $eol = true): string
    {
        return
            sprintf(
                self::ELAPSED . ': %s %s',
                $this->refineElapsed($report->getElapsed()),
                $eol ? PHP_EOL : ''
            );
    }

    /**
     * @param \DateInterval $elapsed
     * @return string
     */
    protected function refineElapsed(\DateInterval $elapsed): string
    {
        return static::formatElapsed($elapsed);
    }

    public static function formatElapsed(\DateInterval $elapsed): string
    {
        $c = CarbonInterval::instance($elapsed);
        if ($c->totalMilliseconds < self::MILLISECONDS_THRESHOLD) {
            return
                Pretty::milliseconds($c->totalMilliseconds);
        }
        // @codeCoverageIgnoreStart
        return (string)$c;
        // @codeCoverageIgnoreEnd
    }

    /**
     * @param TimerReport $report
     * @param bool $eol
     * @return string
     */
    protected function full(TimerReport $report, bool $eol = true): string
    {
        $r = $report;
        $count = $r->getCount();
        $values =
            0 < $count ?
                sprintf(
                    self::AVERAGE . ': %s, ' .
                    self::LAST . ': %s, ' .
                    self::MIN . '(%s): %s, ' .
                    self::MAX . '(%s): %s, ' .
                    self::MARKS . ': %s, ',
                    $this->refineSeconds($r->getAverageValue()),
                    $this->refineSeconds($r->getLastValue()),
                    $r->getMinValueIteration(),
                    $this->refineSeconds($r->getMinValue()),
                    $r->getMaxValueIteration(),
                    $this->refineSeconds($r->getMaxValue()),
                    $count
                ) :
                '';

        return
            sprintf(
                self::TIMER . '%s: %s' .
                self::ELAPSED . ': %s%s',
                $this->refineName($r->getName()),
                $values,
                $this->refineElapsed($r->getElapsed()),
                $eol ? PHP_EOL : ''
            );
    }

    protected function refineSeconds(?float $seconds): string
    {
        return
            $seconds ? Pretty::seconds($seconds) : 'NULL';
    }

    protected function refineName(string $name): string
    {
        if (DEFAULT_NAME === $name) {
            return '';
        }
        return '[' . $name . ']';
    }
}
