<?php

declare(strict_types=1);

namespace ItalyStrap\Cron;

use ItalyStrap\Event\SubscriberInterface;

/**
 * Adds the custom recurrences to WP-Cron.
 */
final readonly class GlobalRecurrences implements SubscriberInterface
{
    public const EVENT_NAME = 'cron_schedules';

    /**
     * @var list<Recurrence>
     */
    private array $recurrences;

    public function __construct(Recurrence ...$recurrences)
    {
        $this->recurrences = \array_values($recurrences);
    }

    public function getSubscribedEvents(): iterable
    {
        yield self::EVENT_NAME => 'add';
    }

    /**
     * @return array<array-key, mixed>
     */
    public function add(mixed $schedules): array
    {
        $schedules = \is_array($schedules) ? $schedules : [];
        foreach ($this->recurrences as $recurrence) {
            $schedules[$recurrence->key()] = [
                'interval' => $recurrence->interval(),
                'display' => $recurrence->label(),
            ];
        }

        return $schedules;
    }
}
