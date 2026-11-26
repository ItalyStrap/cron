<?php

declare(strict_types=1);

namespace ItalyStrap\Cron;

/**
 * A custom recurrence, like once a month: its key, its interval and the label shown to the users.
 */
final readonly class Recurrence
{
    public function __construct(
        private string $key,
        private int $interval,
        private string $label,
    ) {
        if ($key === '') {
            throw new \InvalidArgumentException('The recurrence needs a key');
        }

        if ($interval < 1) {
            throw new \InvalidArgumentException(\sprintf('The interval of "%s" is not a positive number', $key));
        }
    }

    public function key(): string
    {
        return $this->key;
    }

    /**
     * The seconds between two runs.
     */
    public function interval(): int
    {
        return $this->interval;
    }

    public function label(): string
    {
        return $this->label;
    }
}
