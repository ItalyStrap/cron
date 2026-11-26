<?php

declare(strict_types=1);

namespace ItalyStrap\Cron\Tests\Unit;

use Codeception\Test\Unit;
use ItalyStrap\Cron\InMemoryScheduler;
use ItalyStrap\Cron\RecurringEvent;
use ItalyStrap\Cron\Tests\FrozenClock;

final class RecurringEventTest extends Unit
{
    private const NOW = 1_700_000_000;

    private InMemoryScheduler $scheduler;

    public function testItSchedulesTheMissingEventAfterTheFirstRunDelay(): void
    {
        $sut = $this->makeInstance(3600);

        $sut->scheduleIfMissing('daily');

        $this->assertTrue($sut->isScheduled());
        $this->assertSame(self::NOW + 3600, $this->scheduler->nextRun('my_event'));
        $this->assertSame('daily', $this->scheduler->recurrence('my_event'));
    }

    public function testItKeepsTheEventAlreadyScheduled(): void
    {
        $this->scheduler->scheduleRecurring('my_event', 42, 'hourly');

        $this->makeInstance()->scheduleIfMissing('daily');

        $this->assertSame(42, $this->scheduler->nextRun('my_event'));
        $this->assertSame('hourly', $this->scheduler->recurrence('my_event'));
    }

    public function testItDoesNotScheduleTheEventTurnedOff(): void
    {
        $sut = $this->makeInstance();

        $sut->scheduleIfMissing(RecurringEvent::OFF);

        $this->assertFalse($sut->isScheduled());
    }

    public function testItReschedulesTheEventWithTheNewRecurrence(): void
    {
        $this->scheduler->scheduleRecurring('my_event', 42, 'hourly');

        $this->makeInstance()->reschedule('weekly');

        $this->assertSame(self::NOW, $this->scheduler->nextRun('my_event'));
        $this->assertSame('weekly', $this->scheduler->recurrence('my_event'));
    }

    public function testItStopsTheEventRescheduledOff(): void
    {
        $this->scheduler->scheduleRecurring('my_event', 42, 'hourly');
        $sut = $this->makeInstance();

        $sut->reschedule(RecurringEvent::OFF);

        $this->assertFalse($sut->isScheduled());
    }

    public function testItStops(): void
    {
        $this->scheduler->scheduleRecurring('my_event', 42, 'hourly');
        $sut = $this->makeInstance();

        $sut->stop();

        $this->assertFalse($sut->isScheduled());
        $this->assertSame('my_event', $sut->name());
    }

    protected function _before(): void
    {
        $this->scheduler = new InMemoryScheduler();
    }

    private function makeInstance(int $firstRunDelay = 0): RecurringEvent
    {
        return new RecurringEvent('my_event', $this->scheduler, new FrozenClock(self::NOW), $firstRunDelay);
    }
}
