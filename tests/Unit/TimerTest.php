<?php declare(strict_types=1);

namespace AlecRabbit\Tests\Tools;

use AlecRabbit\Timers\Reports\TimerReport;
use AlecRabbit\Timers\Timer;
use PHPUnit\Framework\TestCase;
use const AlecRabbit\Traits\Constants\DEFAULT_NAME;

/**
 * @group time-sensitive
 */
class TimerTest extends TestCase
{

    /**
     * @test
     * @throws \Exception
     */
    public function classCreation(): void
    {
        $timer = new Timer(null, false);
        $this->assertInstanceOf(Timer::class, $timer);
        $this->assertInstanceOf(TimerReport::class, $timer->report());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function timerCreationWithParameters(): void
    {
        $name = 'name';
        $timer = new Timer($name);
        $this->assertEquals($name, $timer->getName());
        $timer = new Timer($name, false);
        $this->assertEquals($name, $timer->getName());
        $this->assertEquals(0.0, $timer->getLastValue());
        $this->assertEquals(0.0, $timer->getAverageValue());
        $this->assertEquals(null, $timer->getMinValue());
        $this->assertEquals(null, $timer->getMaxValue());
        $this->assertEquals(0, $timer->getCount());
        $this->assertEquals(0, $timer->getMinValueIteration());
        $this->assertEquals(0, $timer->getMaxValueIteration());
        $this->assertEquals(false, $timer->isStarted());
        $this->assertEquals(true, $timer->isNotStarted());
        $this->assertEquals(false, $timer->isStopped());
        $this->assertEquals(true, $timer->isNotStopped());
        $this->assertInstanceOf(\DateInterval::class, $timer->getElapsed());
        $this->assertInstanceOf(\DateTimeImmutable::class, $timer->getCreation());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function timerDefaults(): void
    {
        $timer = new Timer();
        $this->assertEquals(DEFAULT_NAME, $timer->getName());
        $this->assertEquals(0.0, $timer->getLastValue(), 'getLastValue');
        $this->assertEquals(0.0, $timer->getAverageValue(), 'getAvgValue');
        $this->assertEquals(null, $timer->getMinValue(), 'getMinValue');
        $this->assertEquals(null, $timer->getMaxValue(), 'getMaxValue');
        $this->assertEquals(0, $timer->getCount(), 'getCount');
        $this->assertEquals(0, $timer->getMinValueIteration(), 'getMinValueIteration');
        $this->assertEquals(0, $timer->getMaxValueIteration(), 'getMaxValueIteration');
        $this->assertInstanceOf(\DateInterval::class, $timer->getElapsed(), 'getElapsed');
//        $this->assertEquals($timer->getCreation(), $timer->getPrevious(), 'getCreation equals getPrevious');
        $this->assertEquals(true, $timer->isStarted());
        $this->assertEquals(false, $timer->isNotStarted());
        $this->assertEquals(false, $timer->isStopped());
        $this->assertEquals(true, $timer->isNotStopped());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function timerBoundsStart(): void
    {
        $timer = new Timer();
        $this->expectException(\TypeError::class);
        $timer->bounds(null, 1);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function timerBoundsStop(): void
    {
        $timer = new Timer();
        $this->expectException(\TypeError::class);
        $timer->bounds(1.0, null);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function timerAvgValue(): void
    {
        $timer = new Timer();
        $timer->start();
        $count = 5;
        for ($i = 0; $i < $count; $i++) {
            sleep(1);
            $timer->check();
        }
        $this->assertEquals(1.0, $timer->getAverageValue());
        $this->assertEquals(1.0, $timer->getMinValue());
        $this->assertEquals(1.0, $timer->getMaxValue());
        $this->assertEquals(1.0, $timer->getLastValue());
        $this->assertEquals($count, $timer->getCount());
        $dateInterval = $timer->getElapsed();
        $this->assertInstanceOf(\DateInterval::class, $dateInterval);
        sleep(5);
        $timer->check();
        $this->assertEquals(5.0, $timer->getMaxValue());
        usleep(100000);
        $timer->check();
        $this->assertEqualsWithDelta(0.1, $timer->getMinValue(), 0.001);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function timerAvgValueVariant(): void
    {
        $timer = new Timer(null, false);
        $count = 5;
        for ($i = 0; $i < $count; $i++) {
            sleep(1);
            $timer->check();
        }
        $this->assertEquals(1.0, $timer->getAverageValue());
        $this->assertEquals(1.0, $timer->getMinValue());
        $this->assertEquals(1.0, $timer->getMaxValue());
        $this->assertEquals(1.0, $timer->getLastValue());
        $this->assertEquals($count - 1, $timer->getCount());
        $this->assertInstanceOf(\DateInterval::class, $timer->getElapsed());
        sleep(5);
        $timer->check();
        $this->assertEquals(5.0, $timer->getMaxValue());
        usleep(100000);
        $timer->check();
        $this->assertEqualsWithDelta(0.1, $timer->getMinValue(), 0.001);
        $timer->stop();
        $this->expectException(\RuntimeException::class);
        $timer->check();
    }

    /**
     * @test
     * @throws \Exception
     */
    public function timerAvgValueUSleep(): void
    {
        $timer = new Timer();
        $timer->start();
        $count = 5;
        for ($i = 0; $i < $count; $i++) {
            usleep(10000);
            $timer->check();
        }
        $this->assertEqualsWithDelta(0.01, $timer->getAverageValue(), 0.001);
        $this->assertEqualsWithDelta(0.01, $timer->getMinValue(), 0.001);
        $this->assertEqualsWithDelta(0.01, $timer->getMaxValue(), 0.001);
        $this->assertEqualsWithDelta(0.01, $timer->getLastValue(), 0.001);
        $this->assertEquals($count, $timer->getCount());
        $dateInterval = $timer->getElapsed();
        $this->assertInstanceOf(\DateInterval::class, $dateInterval);
        usleep(50000);
        $timer->check();
        $this->assertEqualsWithDelta(0.05, $timer->getMaxValue(), 0.001);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function timerAvgValueBounds(): void
    {
        $timer = new Timer();
        $count = 5;
        for ($i = 0; $i < $count; $i++) {
            $start = microtime(true);
            $stop = $start + 1;
            $timer->bounds($start, $stop);
        }
        $this->assertEquals(1.0, $timer->getAverageValue(), 'getAvgValue');
        $this->assertEquals(1.0, $timer->getMinValue(), 'getMinValue');
        $this->assertEquals(1.0, $timer->getMaxValue(), 'getMaxValue');
        $this->assertEquals(1.0, $timer->getLastValue(), 'getCurrentValue');
        $this->assertEquals($count, $timer->getCount());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function timerAvgValueBoundsNotStarted(): void
    {
        $timer = new Timer(null, false);
        $count = 5;
        for ($i = 0; $i < $count; $i++) {
            $start = microtime(true);
            sleep(1);
            $stop = microtime(true);
            $timer->bounds($start, $stop);
        }
        $this->assertEquals(1.0, $timer->getAverageValue(), 'getAvgValue');
        $this->assertEquals(1.0, $timer->getMinValue(), 'getMinValue');
        $this->assertEquals(1.0, $timer->getMaxValue(), 'getMaxValue');
        $this->assertEquals(1.0, $timer->getLastValue(), 'getCurrentValue');
        $this->assertEquals($count, $timer->getCount());
    }
}
