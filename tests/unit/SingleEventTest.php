<?php

declare(strict_types=1);

namespace ItalyStrap\Cron\Tests\Unit;

use Codeception\Test\Unit;
use ItalyStrap\Cron\InMemoryScheduler;
use ItalyStrap\Cron\SingleEvent;
use ItalyStrap\Cron\Tests\FrozenClock;

final class SingleEventTest extends Unit
{
    private const NOW = 1_700_000_000;

    public function testItSchedulesTheEventOnceAfterTheDelay(): void
    {
        $scheduler = new InMemoryScheduler();
        $sut = new SingleEvent('my_batch', $scheduler, new FrozenClock(self::NOW));

        $sut->schedule();
        $now = $scheduler->nextRun('my_batch');
        $sut->schedule(60);

        $this->assertSame(self::NOW, $now);
        $this->assertSame(self::NOW + 60, $scheduler->nextRun('my_batch'));
        $this->assertNull($scheduler->recurrence('my_batch'));
        $this->assertSame('my_batch', $sut->name());
    }

    public function testItCancelsTheEvent(): void
    {
        $scheduler = new InMemoryScheduler();
        $sut = new SingleEvent('my_batch', $scheduler, new FrozenClock(self::NOW));
        $sut->schedule();

        $sut->cancel();

        $this->assertNull($scheduler->nextRun('my_batch'));
    }
}
