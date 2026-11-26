<?php

declare(strict_types=1);

namespace ItalyStrap\Cron\Tests\Integration;

use ItalyStrap\Cron\GlobalRecurrences;
use ItalyStrap\Cron\GlobalScheduler;
use ItalyStrap\Cron\Recurrence;
use ItalyStrap\Cron\RecurringEvent;
use ItalyStrap\Cron\SystemClock;
use lucatume\WPBrowser\TestCase\WPTestCase;

final class GlobalSchedulerTest extends WPTestCase
{
    public function testItSchedulesARecurringEventOnWpCron(): void
    {
        $sut = new GlobalScheduler();

        $sut->scheduleRecurring('my_event', 1_700_000_000, 'daily');

        $this->assertSame(1_700_000_000, $sut->nextRun('my_event'));
        $this->assertSame('daily', \wp_get_schedule('my_event'));
    }

    public function testItSchedulesASingleEventOnWpCron(): void
    {
        $sut = new GlobalScheduler();

        $sut->scheduleOnce('my_batch', 1_700_000_000);

        $this->assertSame(1_700_000_000, $sut->nextRun('my_batch'));
        $this->assertFalse(\wp_get_schedule('my_batch'));
    }

    public function testItClearsEveryRunOfTheEvent(): void
    {
        $sut = new GlobalScheduler();
        $sut->scheduleOnce('my_batch', 1_700_000_000);
        $sut->scheduleOnce('my_batch', 1_800_000_000);

        $sut->clear('my_batch');

        $this->assertNull($sut->nextRun('my_batch'));
    }

    public function testTheRecurringEventUsesACustomRecurrence(): void
    {
        $recurrences = new GlobalRecurrences(new Recurrence('monthly_test', 2_592_000, 'Once a month'));
        \add_filter(GlobalRecurrences::EVENT_NAME, $recurrences->add(...));
        $sut = new RecurringEvent('my_event', new GlobalScheduler(), new SystemClock());

        $sut->keepScheduled('monthly_test');

        $this->assertSame(
            [
                'interval' => 2_592_000,
                'display' => 'Once a month',
            ],
            \wp_get_schedules()['monthly_test'] ?? null
        );
        $this->assertSame('monthly_test', \wp_get_schedule('my_event'));
        $this->assertSame([
            GlobalRecurrences::EVENT_NAME => 'add',
        ], \iterator_to_array(
            $recurrences->getSubscribedEvents()
        ));
    }
}
