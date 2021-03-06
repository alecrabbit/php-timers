<?php declare(strict_types=1);

namespace AlecRabbit\Timers;

use AlecRabbit\Formatters\TimerReportFormatter;
use AlecRabbit\Reports\TimerReport;
use AlecRabbit\Timers\Core\AbstractTimer;

/**
 * Class Timer
 * @package AlecRabbit\Timers
 */
class Timer extends AbstractTimer
{
    public function __construct(?string $name = null, bool $start = true)
    {
        parent::__construct($name, $start);
        $this->setBindings(
            TimerReport::class,
            TimerReportFormatter::class
        );
    }


    /**
     * @return float
     */
    public function current(): float
    {
        return
            microtime(true);
    }

    /**
     * @param float $start
     * @param float $stop
     */
    protected function assertStartAndStop($start, $stop): void
    {
        $this->assertStart($start);
        $this->assertStop($stop);
    }

    /**
     * @param float $start
     */
    protected function assertStart(/** @scrutinizer ignore-unused */ float $start): void
    {
        // Intentionally left blank
    }

    /**
     * @param float $stop
     */
    protected function assertStop(/** @scrutinizer ignore-unused */ float $stop): void
    {
        // Intentionally left blank
    }
}
