<?php

declare(strict_types=1);

namespace ItalyStrap\Cron;

/**
 * The events of a plugin or a theme, cleared together, like when it is deactivated.
 */
final readonly class ScheduledEvents
{
    /**
     * @var list<string>
     */
    private array $names;

    public function __construct(
        private SchedulerInterface $scheduler,
        string ...$names,
    ) {
        $this->names = \array_values($names);
    }

    public function clearAll(): void
    {
        foreach ($this->names as $name) {
            $this->scheduler->clear($name);
        }
    }
}
