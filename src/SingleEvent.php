<?php

declare(strict_types=1);

namespace ItalyStrap\Cron;

use Psr\Clock\ClockInterface;

/**
 * An event that runs once, like the next batch of a long job.
 */
final readonly class SingleEvent
{
    /**
     * @param string $name The name of the event, its listeners run when it is due.
     */
    public function __construct(
        private string $name,
        private SchedulerInterface $scheduler,
        private ClockInterface $clock,
    ) {
    }

    public function name(): string
    {
        return $this->name;
    }

    /**
     * Runs the event after the seconds of the delay, on the next run of the scheduler without one.
     */
    public function schedule(int $delay = 0): void
    {
        $this->scheduler->scheduleOnce($this->name, $this->clock->now()->getTimestamp() + $delay);
    }

    public function cancel(): void
    {
        $this->scheduler->clear($this->name);
    }
}
