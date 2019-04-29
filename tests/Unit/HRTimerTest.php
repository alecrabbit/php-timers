<?php declare(strict_types=1);

namespace AlecRabbit\Tests\Timers;

use AlecRabbit\Timers\HRTimer;
use PHPUnit\Framework\TestCase;
use const AlecRabbit\Timers\HRTIMER_VALUE_COEFFICIENT;
use const AlecRabbit\Traits\Constants\DEFAULT_NAME;

/**
 * @group time-sensitive
 */
class HRTimerTest extends TestCase
{
    /**
     * @test
     * @throws \Exception
     */
    public function instance(): void
    {
        $this->assertEquals(HRTimer::VALUE_COEFFICIENT, HRTIMER_VALUE_COEFFICIENT);
        $this->assertEnvironment();
        $timer = new HRTimer();
        $this->assertInstanceOf(HRTimer::class, $timer);
    }

    protected function assertEnvironment(): void
    {
        if (PHP_VERSION_ID < 70300 && false === HRTimer::$ignoreVersionRestrictions) {
            $this->expectException(\RuntimeException::class);
        }
    }

    /**
     * @test
     * @throws \Exception
     */
    public function timerBoundsStart(): void
    {
        $this->assertEnvironment();
        $timer = new HRTimer();
        $this->expectException(\TypeError::class);
        $timer->bounds(null, 1);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function timerBoundsStop(): void
    {
        $this->assertEnvironment();
        $timer = new HRTimer();
        $this->expectException(\TypeError::class);
        $timer->bounds(1, null);
    }


    /**
     * @test
     * @throws \Exception
     */
    public function timerAvgValueBounds(): void
    {
        $this->assertEnvironment();
        $timer = new HRTimer();
        $count = 15;
        for ($i = 0; $i < $count; $i++) {
            $start = hrtime(true);
            $stop = $start + 1000000000;
            $timer->bounds($start, $stop);
        }
        $this->assertEqualsWithDelta(1.0, $timer->getAverageValue(), 0.001);
        $this->assertEqualsWithDelta(1.0, $timer->getMinValue(), 0.001);
        $this->assertEqualsWithDelta(1.0, $timer->getMaxValue(), 0.001);
        $this->assertEqualsWithDelta(1.0, $timer->getLastValue(), 0.001);
        $this->assertEquals($count, $timer->getCount());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function timerAvgValueBoundsUSleep(): void
    {
        $this->assertEnvironment();
        $timer = new HRTimer();
        $count = 15;
        for ($i = 0; $i < $count; $i++) {
            $start = hrtime(true);
            $stop = $start + 10000000;
            $timer->bounds($start, $stop);
        }
        $this->assertEqualsWithDelta(0.01, $timer->getAverageValue(), 0.001);
        $this->assertEqualsWithDelta(0.01, $timer->getMinValue(), 0.001);
        $this->assertEqualsWithDelta(0.01, $timer->getMaxValue(), 0.001);
        $this->assertEqualsWithDelta(0.01, $timer->getLastValue(), 0.001);
        $this->assertEquals($count, $timer->getCount());
        $start = hrtime(true);
        $stop = $start + 50000000;
        $timer->bounds($start, $stop);
        $this->assertEqualsWithDelta(0.05, $timer->getMaxValue(), 0.001);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function timerAvgValueBoundsUSleepBelow73(): void
    {
        HRTimer::$ignoreVersionRestrictions = true;
        $this->assertEnvironment();
        $timer = new HRTimer();
        $count = 5;
        for ($i = 0; $i < $count; $i++) {
            $start = hrtime(true);
            $stop = $start + 10000000;
            $timer->bounds($start, $stop);
        }
        $this->assertEqualsWithDelta(0.01, $timer->getAverageValue(), 0.001);
        $this->assertEqualsWithDelta(0.01, $timer->getMinValue(), 0.001);
        $this->assertEqualsWithDelta(0.01, $timer->getMaxValue(), 0.001);
        $this->assertEqualsWithDelta(0.01, $timer->getLastValue(), 0.001);
        $this->assertEquals($count, $timer->getCount());
        $start = hrtime(true);
        $stop = $start + 50000000;
        $timer->bounds($start, $stop);
        $this->assertEqualsWithDelta(0.05, $timer->getMaxValue(), 0.001);
    }

    /**
     * @test
     * @dataProvider parametersHRTimer
     * @param array $expected
     * @param array $params
     * @throws \Exception
     */
    public function instanceParams(array $expected, array $params): void
    {
        $this->assertEnvironment();
        $timer = new HRTimer(...$params);
        [$name, $isStarted, $isNotStarted, $isStopped, $isNotStopped] = $expected;
        $this->assertEquals($name, $timer->getName());
        $this->assertEquals($isStarted, $timer->isStarted());
        $this->assertEquals($isNotStarted, $timer->isNotStarted());
        $this->assertEquals($isStopped, $timer->isStopped());
        $this->assertEquals($isNotStopped, $timer->isNotStopped());
    }

    /**
     * @test
     * @dataProvider parametersHRTimer
     * @param array $expected
     * @param array $params
     * @throws \Exception
     */
    public function instanceParamsBelow73(array $expected, array $params): void
    {
        HRTimer::$ignoreVersionRestrictions = true;
        $this->assertEnvironment();
        $timer = new HRTimer(...$params);
        [$name, $isStarted, $isNotStarted, $isStopped, $isNotStopped] = $expected;
        $this->assertEquals($name, $timer->getName());
        $this->assertEquals($isStarted, $timer->isStarted());
        $this->assertEquals($isNotStarted, $timer->isNotStarted());
        $this->assertEquals($isStopped, $timer->isStopped());
        $this->assertEquals($isNotStopped, $timer->isNotStopped());
    }

    /**
     * @return array
     */
    public function parametersHRTimer(): array
    {
        $name = 'name';
        return [
            [[DEFAULT_NAME, true, false, false, true], []],
            [[DEFAULT_NAME, true, false, false, true], [null, true]],
            [[DEFAULT_NAME, true, false, false, true], [DEFAULT_NAME]],
            [[$name, true, false, false, true], [$name]],
            [[$name, true, false, false, true], [$name, true]],
            [[$name, false, true, false, true], [$name, false]],
            [[DEFAULT_NAME, false, true, false, true], [null, false]],
        ];
    }
}
