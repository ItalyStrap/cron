<?php

declare(strict_types=1);

namespace ItalyStrap\Cron;

/**
 * Schedules the events on WP-Cron.
 */
final class GlobalScheduler implements SchedulerInterface
{
    public function scheduleRecurring(string $event, int $start, string $recurrence): void
    {
        \wp_schedule_event($start, $recurrence, $event);
    }

    public function scheduleOnce(string $event, int $timestamp): void
    {
        \wp_schedule_single_event($timestamp, $event);
    }

    public function nextRun(string $event): ?int
    {
        $timestamp = \wp_next_scheduled($event);

        return \is_int($timestamp) ? $timestamp : null;
    }

    public function clear(string $event): void
    {
        \wp_clear_scheduled_hook($event);
    }
}
