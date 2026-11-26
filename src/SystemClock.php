<?php

declare(strict_types=1);

namespace ItalyStrap\Cron;

use Psr\Clock\ClockInterface;

/**
 * The current time, in UTC like the timestamps of WP-Cron.
 */
final class SystemClock implements ClockInterface
{
    public function now(): \DateTimeImmutable
    {
        return new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
    }
}
