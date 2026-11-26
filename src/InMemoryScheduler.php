<?php

declare(strict_types=1);

namespace ItalyStrap\Cron;

/**
 * Keeps the events in memory, for the tests and the code outside WordPress: nothing runs them.
 */
final class InMemoryScheduler implements SchedulerInterface
{
    /**
     * @var array<string, array{timestamp: int, recurrence: string|null}>
     */
    private array $events = [];

    public function scheduleRecurring(string $event, int $start, string $recurrence): void
    {
        $this->events[$event] = [
            'timestamp' => $start,
            'recurrence' => $recurrence,
        ];
    }

    public function scheduleOnce(string $event, int $timestamp): void
    {
        $this->events[$event] = [
            'timestamp' => $timestamp,
            'recurrence' => null,
        ];
    }

    public function nextRun(string $event): ?int
    {
        return $this->events[$event]['timestamp'] ?? null;
    }

    /**
     * The recurrence of the event, null when it runs once or it is not scheduled.
     */
    public function recurrence(string $event): ?string
    {
        return $this->events[$event]['recurrence'] ?? null;
    }

    public function clear(string $event): void
    {
        unset($this->events[$event]);
    }
}
