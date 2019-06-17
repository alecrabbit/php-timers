<?php declare(strict_types=1);

namespace AlecRabbit\Reports;

use AlecRabbit\Reports\Contracts\TimerReportInterface;
use AlecRabbit\Reports\Core\AbstractReport;
use AlecRabbit\Reports\Core\AbstractReportable;
use AlecRabbit\Timers\Core\AbstractTimer;
use AlecRabbit\Timers\Core\Traits\TimerFields;

/**
 * Class TimerReport
 * @psalm-suppress MissingConstructor
 * @psalm-suppress PropertyNotSetInConstructor
 */
class TimerReport extends AbstractReport implements TimerReportInterface
{
    use TimerFields;

    /** {@inheritDoc}
     * @throws \Exception
     */
    protected function extractDataFrom(AbstractReportable $reportable = null): void
    {
        if ($reportable instanceof AbstractTimer) {
            $this->name = $reportable->getName();
            $this->creationTime = $reportable->getCreation();
            $this->count = $count = $reportable->getCount();
            $this->minValue = ($count === 1) ? $reportable->getLastValue() : $reportable->getMinValue();
            $this->maxValue = $reportable->getMaxValue();
            $this->maxValueIteration = $reportable->getMaxValueIteration();
            $this->minValueIteration = $reportable->getMinValueIteration();
            $this->started = $reportable->isStarted();
            $this->stopped = $reportable->isStopped();
            $this->avgValue = $reportable->getAverageValue();
            $this->currentValue = $reportable->getLastValue();
            $this->elapsed = $reportable->getElapsed();
        }
    }
}
