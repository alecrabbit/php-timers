<?php declare(strict_types=1);

namespace AlecRabbit\Reports;

use AlecRabbit\Formatters\Contracts\FormatterInterface;
use AlecRabbit\Reports\Contracts\ReportableInterface;
use AlecRabbit\Reports\Contracts\ReportInterface;
use AlecRabbit\Reports\Core\AbstractReport;
use AlecRabbit\Timers\Core\AbstractTimer;
use AlecRabbit\Timers\Core\Traits\TimerFields;
use AlecRabbit\Timers\Factory;
use AlecRabbit\Reports\Contracts\TimerReportInterface;

class TimerReport extends AbstractReport implements TimerReportInterface
{
    use TimerFields;

    /**
     * TimerReport constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        // TODO fix this?
        // This lines here are to keep vimeo/psalm quiet
        $this->creationTime = new \DateTimeImmutable();
        $this->elapsed = (new \DateTimeImmutable())->diff($this->creationTime);
    }

    protected static function getFormatter(): FormatterInterface
    {
        return Factory::getTimerReportFormatter();
    }

    /**
     * @param ReportableInterface $reportable
     * @return ReportInterface
     * @throws \RuntimeException
     * @throws \Exception
     */
    public function buildOn(ReportableInterface $reportable): ReportInterface
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
        } else {
            $this->wrongReportable(AbstractTimer::class, $reportable);
        }
        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return '';
    }
}
