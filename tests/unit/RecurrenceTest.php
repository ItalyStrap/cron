<?php

declare(strict_types=1);

namespace ItalyStrap\Cron\Tests\Unit;

use Codeception\Test\Unit;
use ItalyStrap\Cron\InMemoryScheduler;
use ItalyStrap\Cron\Recurrence;
use ItalyStrap\Cron\ScheduledEvents;

final class RecurrenceTest extends Unit
{
    public function testItKeepsItsValues(): void
    {
        $sut = new Recurrence('monthly', 2_592_000, 'Once a month');

        $this->assertSame('monthly', $sut->key());
        $this->assertSame(2_592_000, $sut->interval());
        $this->assertSame('Once a month', $sut->label());
    }

    public function testItNeedsAKey(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Recurrence('', 60, 'Every minute');
    }

    public function testItNeedsAPositiveInterval(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Recurrence('never', 0, 'Never');
    }

    public function testTheScheduledEventsAreClearedTogether(): void
    {
        $scheduler = new InMemoryScheduler();
        $scheduler->scheduleRecurring('first', 1, 'daily');
        $scheduler->scheduleOnce('second', 2);
        $scheduler->scheduleOnce('other', 3);

        (new ScheduledEvents($scheduler, 'first', 'second'))->clearAll();

        $this->assertNull($scheduler->nextRun('first'));
        $this->assertNull($scheduler->nextRun('second'));
        $this->assertSame(3, $scheduler->nextRun('other'));
    }
}
