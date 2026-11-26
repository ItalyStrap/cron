<?php

declare(strict_types=1);

namespace ItalyStrap\Cron;

use Psr\Clock\ClockInterface;

/**
 * An event that runs again after each interval of its recurrence, and schedules itself again when it is missing,
 * like after a migration of the site.
 */
final readonly class RecurringEvent
{
    /**
     * The recurrence of an event that does not run.
     */
    public const OFF = '';

    /**
     * @param string $name The name of the event, its listeners run when it is due.
     * @param int $firstRunDelay The seconds before the first run, after the event is scheduled.
     */
    public function __construct(
        private string $name,
        private SchedulerInterface $scheduler,
        private ClockInterface $clock,
        private int $firstRunDelay = 0,
    ) {
    }

    public function name(): string
    {
        return $this->name;
    }

    public function isScheduled(): bool
    {
        return $this->scheduler->nextRun($this->name) !== null;
    }

    /**
     * Schedules the event when it is missing, it does nothing when the recurrence is off.
     */
    public function keepScheduled(string $recurrence): void
    {
        if ($recurrence === self::OFF || $this->isScheduled()) {
            return;
        }

        $this->schedule($recurrence);
    }

    /**
     * Schedules the event with a new recurrence, it stops when the recurrence is off.
     */
    public function reschedule(string $recurrence): void
    {
        $this->stop();
        if ($recurrence === self::OFF) {
            return;
        }

        $this->schedule($recurrence);
    }

    public function stop(): void
    {
        $this->scheduler->clear($this->name);
    }

    private function schedule(string $recurrence): void
    {
        $start = $this->clock->now()->getTimestamp() + $this->firstRunDelay;
        $this->scheduler->scheduleRecurring($this->name, $start, $recurrence);
    }
}
