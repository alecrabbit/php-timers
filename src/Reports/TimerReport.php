<?php declare(strict_types=1);

namespace AlecRabbit\Reports;

use AlecRabbit\Formatters\Contracts\FormatterInterface;
use AlecRabbit\Formatters\TimerReportFormatter;
use AlecRabbit\Reports\Contracts\ReportableInterface;
use AlecRabbit\Reports\Contracts\ReportInterface;
use AlecRabbit\Reports\Contracts\TimerReportInterface;
use AlecRabbit\Reports\Core\AbstractReport;
use AlecRabbit\Timers\Core\AbstractTimer;
use AlecRabbit\Timers\Core\Traits\TimerFields;

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

    public static function getFormatter(): FormatterInterface
    {
        return new TimerReportFormatter();
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
            return $this;
        }
        throw new \InvalidArgumentException(
            AbstractTimer::class . ' expected, ' . get_class($reportable) . ' given.'
        );
    }

//    public function buildOn(ReportableInterface $reportable): ReportInterface
//    {
//        if ($reportable instanceof MemoryUsage) {
//            return $this;
//        }
//        throw new \InvalidArgumentException(
//            MemoryUsage::class . ' expected, ' . get_class($reportable) . ' given.'
//        );
//    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return static::getFormatter()->format($this) ;
    }
}
