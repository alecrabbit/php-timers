<?php declare(strict_types=1);

namespace AlecRabbit\Timers\Contracts;

interface TimerValuesInterface
{
    /**
     * @return float
     */
    public function getLastValue(): float;

    /**
     * @return float
     */
    public function getAverageValue(): float;

    /**
     * @return null|float
     */
    public function getMinValue(): ?float;

    /**
     * @return null|float
     */
    public function getMaxValue(): ?float;

    /**
     * @return int
     */
    public function getCount(): int;

    /**
     * @return int
     */
    public function getMinValueIteration(): int;

    /**
     * @return int
     */
    public function getMaxValueIteration(): int;

    /**
     * @return \DateInterval
     */
    public function getElapsed(): \DateInterval;

    /**
     * @return \DateTimeImmutable
     */
    public function getCreation(): \DateTimeImmutable;
}
