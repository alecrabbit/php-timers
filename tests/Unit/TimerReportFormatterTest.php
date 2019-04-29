<?php declare(strict_types=1);

namespace AlecRabbit\Tests\Timers;

use AlecRabbit\Accessories\Pretty;
use AlecRabbit\Formatters\TimerReportFormatter;
use AlecRabbit\Timers\Contracts\TimerStrings;
use AlecRabbit\Timers\HRTimer;
use AlecRabbit\Reports\TimerReport;
use AlecRabbit\Timers\Timer;
use PHPUnit\Framework\TestCase;

/**
 * @group time-sensitive
 */
class TimerReportFormatterTest extends TestCase
{
//    /**
//     * @test
//     * @throws \Exception
//     */
//    public function wrongReport(): void
//    {
//        $formatter = new TimerReportFormatter();
//        $profilerReport = new ProfilerReport();
//        $this->expectException(\RuntimeException::class);
//        $formatter->format($profilerReport);
//    }

    /**
     * @test
     * @throws \Exception
     */
    public function correctReportTimer(): void
    {
        $formatter = new TimerReportFormatter();
        $timer = new Timer();
        $timerReport = new TimerReport();
        $timerReport->buildOn($timer);
        $str = $formatter->format($timerReport);
        $this->assertStringContainsString(TimerStrings::ELAPSED, $str);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function correctReportHRTimer(): void
    {
        $formatter = new TimerReportFormatter();
        $timer = new HRTimer();
        $timerReport = new TimerReport();
        $timerReport->buildOn($timer);
        $str = $formatter->format($timerReport);
        $this->assertStringContainsString(TimerStrings::ELAPSED, $str);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function getReport(): void
    {
        $t = new Timer();
        /** @var TimerReport $report */
        $report = $t->report();
        $this->assertInstanceOf(TimerReport::class, $report);
        $str = (string)$report;
        $this->assertIsString($str);
        $this->assertStringContainsString(TimerStrings::ELAPSED, $str);
        $this->assertStringNotContainsString(TimerStrings::TIMER, $str);
        $this->assertStringNotContainsString(TimerStrings::AVERAGE, $str);
        $this->assertStringNotContainsString(TimerStrings::LAST, $str);
        $this->assertStringNotContainsString(TimerStrings::MIN, $str);
        $this->assertStringNotContainsString(TimerStrings::MAX, $str);
        $this->assertStringNotContainsString(TimerStrings::MARKS, $str);
        $this->assertStringMatchesFormat(
            '%f%ss',
            $t->elapsed()
        );
    }

    /**
     * @test
     * @throws \Exception
     */
    public function timerElapsed(): void
    {
        $t = new Timer('someName', false);
        $t->start();
        usleep(2000);
        $report = $t->report();
        $str = (string)$report;
        $this->assertIsString($str);
        $this->assertStringContainsString(TimerStrings::ELAPSED, $str);
        $this->assertStringContainsString(TimerStrings::TIMER, $str);
        $this->assertStringContainsString($t->getName(), $str);
        $this->assertStringMatchesFormat(
            '%f%ss',
            $t->elapsed()
        );
    }

    /**
     * @test
     * @throws \Exception
     */
    public function timerElapsedNotStarted(): void
    {
        $t = new Timer('someName', false);
        usleep(2000);
        $report = $t->report();
        $str = (string)$report;
        $this->assertIsString($str);
        $this->assertStringContainsString(TimerStrings::ELAPSED, $str);
        $this->assertStringContainsString(TimerStrings::TIMER, $str);
        $this->assertStringNotContainsString(TimerStrings::AVERAGE, $str);
        $this->assertStringNotContainsString(TimerStrings::LAST, $str);
        $this->assertStringNotContainsString(TimerStrings::MIN, $str);
        $this->assertStringNotContainsString(TimerStrings::MAX, $str);
        $this->assertStringNotContainsString(TimerStrings::MARKS, $str);
        $this->assertStringMatchesFormat(
            '%f%ss',
            $t->elapsed()
        );
    }

    /**
     * @test
     * @throws \Exception
     */
    public function timerElapsedStartedManuallyAndChecked(): void
    {
        $t = new Timer('someName', false);
        $t->start();
        usleep(2000);
        $report = $t->report();
        $str = (string)$report;
        $this->assertIsString($str);
        $this->assertStringContainsString(TimerStrings::ELAPSED, $str);
        $this->assertStringContainsString(TimerStrings::TIMER, $str);
        $this->assertStringNotContainsString(TimerStrings::AVERAGE, $str);
        $this->assertStringNotContainsString(TimerStrings::LAST, $str);
        $this->assertStringNotContainsString(TimerStrings::MIN, $str);
        $this->assertStringNotContainsString(TimerStrings::MAX, $str);
        $this->assertStringNotContainsString(TimerStrings::MARKS, $str);
        usleep(20);
        $t->check(1);
        $report = $t->report();
        $str = (string)$report;
        $this->assertStringContainsString(TimerStrings::ELAPSED, $str);
        $this->assertStringContainsString(TimerStrings::TIMER, $str);
        $this->assertStringContainsString(TimerStrings::AVERAGE, $str);
        $this->assertStringContainsString(TimerStrings::LAST, $str);
        $this->assertStringContainsString(TimerStrings::MIN, $str);
        $this->assertStringContainsString(TimerStrings::MAX, $str);
        $this->assertStringContainsString(TimerStrings::MARKS, $str);
        $this->assertStringMatchesFormat(
            '%f%ss',
            $t->elapsed()
        );
    }

    /**
     * @test
     * @throws \Exception
     */
    public function timerElapsedNotStartedTwo(): void
    {
        $timer = new Timer(null, false);
        $elapsed = $timer->elapsed();
        $this->assertStringContainsString('.', $elapsed);
        $this->assertStringContainsString('s', $elapsed);
        $this->assertStringNotContainsString('seconds', $elapsed);
        $this->assertStringNotContainsString(TimerStrings::TIMER, $elapsed);
        $this->assertStringNotContainsString($timer->getName(), $elapsed);
        $this->assertStringNotContainsString(TimerStrings::AVERAGE, $elapsed);
        $this->assertStringNotContainsString(TimerStrings::LAST, $elapsed);
        $this->assertStringNotContainsString(TimerStrings::MIN, $elapsed);
        $this->assertStringNotContainsString(TimerStrings::MAX, $elapsed);
        $this->assertStringNotContainsString(TimerStrings::MARKS, $elapsed);
    }

    /**
     * @test
     * @throws \Exception
     */
    public function timerValues(): void
    {
        $timer = new Timer();
        $timer->start();
        $count = 5;
        for ($i = 1; $i < $count; $i++) {
            sleep($i);
            $timer->check();
        }
        $this->assertEquals(2.5, $timer->getAverageValue());
        $this->assertEquals(1.0, $timer->getMinValue());
        $this->assertEquals(4.0, $timer->getMaxValue());
        $this->assertEquals(4.0, $timer->getLastValue());
        $this->assertEquals($count - 1, $timer->getCount());
        sleep(5);
        $timer->check();
        $this->assertEquals(5.0, $timer->getMaxValue());
        usleep(100000);
        $timer->check();
        $this->assertEqualsWithDelta(0.1, $timer->getMinValue(), 0.001);
        $report = $timer->report();
        $str = (string)$report;
        $avgStrValue = Pretty::time($timer->getAverageValue());
        $lastStrValue = Pretty::time($timer->getLastValue());
        $this->assertStringContainsString(TimerStrings::ELAPSED, $str);
        $this->assertStringContainsString(TimerStrings::TIMER, $str);
        $this->assertStringContainsString(TimerStrings::AVERAGE . ': ' . $avgStrValue, $str);
        $this->assertStringContainsString(TimerStrings::LAST . ': ' . $lastStrValue, $str);
        $this->assertStringContainsString(TimerStrings::MIN, $str);
        $this->assertStringContainsString(TimerStrings::MAX, $str);
        $this->assertStringContainsString(TimerStrings::MARKS . ': ' . $timer->getCount(), $str);
    }
}
