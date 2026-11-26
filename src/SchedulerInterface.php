<?php

declare(strict_types=1);

namespace ItalyStrap\Cron;

/**
 * Schedules the events by their name, the listeners of the name run when the event is due.
 */
interface SchedulerInterface
{
    /**
     * Runs the event from the start, then again after each interval of the recurrence.
     *
     * @param string $recurrence The key of the recurrence, like `daily` or a custom one.
     */
    public function scheduleRecurring(string $event, int $start, string $recurrence): void;

    /**
     * Runs the event once, at the timestamp.
     */
    public function scheduleOnce(string $event, int $timestamp): void;

    /**
     * The timestamp of the next run of the event, null when it is not scheduled.
     */
    public function nextRun(string $event): ?int;

    /**
     * Removes every run of the event.
     */
    public function clear(string $event): void;
}
