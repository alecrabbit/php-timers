<?php declare(strict_types=1);

namespace AlecRabbit\Timers\Core\Traits;

use AlecRabbit\Timers\HRTimer;
use AlecRabbit\Traits\GettableName;
use AlecRabbit\Traits\StartableAndStoppable;

trait TimerFields
{
    use GettableName, StartableAndStoppable;

    /** @var int|float */
    protected $previous = 0.0;

    /** @var \DateTimeImmutable */
    protected $creationTime;

    /** @var \DateInterval */
    protected $elapsed;

    /** @var int|float */
    protected $currentValue = 0.0;

    /** @var float */
    protected $avgValue = 0.0;

    /** @var float|int|null */
    protected $minValue;

    /** @var float|int|null */
    protected $maxValue;

    /** @var int */
    protected $minValueIteration = 0;

    /** @var int */
    protected $maxValueIteration = 0;

    /** @var int */
    protected $count = 0;

    /**
     * @return float
     */
    public function getLastValue(): float
    {
        return
            $this->normalize($this->currentValue);
    }

    /**
     * @param int|float $value
     * @return float
     */
    protected function normalize($value): float
    {
        if ($this instanceof HRTimer) {
            return
                $value / HRTimer::VALUE_COEFFICIENT;
        }
        return $value;
    }

    /**
     * @return float
     */
    public function getAverageValue(): float
    {
        return
            $this->normalize($this->avgValue);
    }

    /**
     * @return null|float
     */
    public function getMinValue(): ?float
    {

        return
            $this->minValue ? $this->normalize($this->minValue) : null;
    }

    /**
     * @return null|float
     */
    public function getMaxValue(): ?float
    {
        return
            $this->maxValue ? $this->normalize($this->maxValue) : null;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @return int
     */
    public function getMinValueIteration(): int
    {
        return $this->minValueIteration;
    }

    /**
     * @return int
     */
    public function getMaxValueIteration(): int
    {
        return $this->maxValueIteration;
    }

    /**
     * @return \DateInterval
     */
    public function getElapsed(): \DateInterval
    {
        return $this->elapsed;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreation(): \DateTimeImmutable
    {
        return $this->creationTime;
    }
}
