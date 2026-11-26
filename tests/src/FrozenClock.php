<?php

declare(strict_types=1);

namespace ItalyStrap\Cron\Tests;

use Psr\Clock\ClockInterface;

final readonly class FrozenClock implements ClockInterface
{
    public function __construct(
        private int $timestamp,
    ) {
    }

    public function now(): \DateTimeImmutable
    {
        return (new \DateTimeImmutable('now', new \DateTimeZone('UTC')))->setTimestamp($this->timestamp);
    }
}
