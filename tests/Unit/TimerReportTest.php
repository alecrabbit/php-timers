<?php declare(strict_types=1);

namespace AlecRabbit\Tests\Timers;

use AlecRabbit\Reports\TimerReport;
use AlecRabbit\Timers\Timer;
use PHPUnit\Framework\TestCase;

/**
 * @group time-sensitive
 */
class TimerReportTest extends TestCase
{

    /**
     * @test
     * @throws \Exception
     */
    public function timerValuesStarted(): void
    {
        $t = new Timer();
        $count = 6;
        for ($i = 1; $i <= $count; $i++) {
            usleep(2000 + $i * 1000);
            $t->check($i);
        }
        usleep(1000);
        $t->check($i + 1);
        /** @var TimerReport $report */
        $report = $t->report();
        $this->assertEqualsWithDelta(0.001, $report->getLastValue(), 0.0001);
        $this->assertEqualsWithDelta(0.005, $report->getAverageValue(), 0.0005);
        $this->assertEqualsWithDelta(0.001, $report->getMinValue(), 0.0001);
        $this->assertEqualsWithDelta(0.008, $report->getMaxValue(), 0.0001);
        $this->assertEquals(8, $report->getMinValueIteration());
        $this->assertEquals(6, $report->getMaxValueIteration());
        $this->assertEquals(7, $report->getCount());
        $this->assertInstanceOf(\DateInterval::class, $report->getElapsed());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function timerValuesNotStarted(): void
    {
        $t = new Timer(null, false);
        $count = 6;
        for ($i = 1; $i <= $count; $i++) {
            sleep(2 + $i * 1);
            $t->check($i);
        }
        sleep(1);
        $t->check($i + 1);
        /** @var TimerReport $report */
        $report = $t->report();
        $this->assertEqualsWithDelta(1, $report->getLastValue(), 0.0001);
        $this->assertEqualsWithDelta(5, $report->getAverageValue(), 0.167);
        $this->assertEqualsWithDelta(1, $report->getMinValue(), 0.0001);
        $this->assertEqualsWithDelta(8, $report->getMaxValue(), 0.0001);
        $this->assertEquals(8, $report->getMinValueIteration());
        $this->assertEquals(6, $report->getMaxValueIteration());
        $this->assertEquals(6, $report->getCount());
        $this->assertInstanceOf(\DateInterval::class, $report->getElapsed());
    }
}
