# ItalyStrap Cron

Schedule the recurring and the single events of a plugin or a theme. The contract does not depend on WordPress: `InMemoryScheduler` keeps the events in memory, `GlobalScheduler` schedules them on WP-Cron.

## Table Of Contents

* [Installation](#installation)
* [Basic Usage](#basic-usage)
* [Custom recurrences](#custom-recurrences)
* [Deactivation](#deactivation)
* [Development](#development)
* [License](#license)

## Installation

```bash
composer require italystrap/cron
```

It requires PHP 8.2. The time comes from a [PSR-20](https://www.php-fig.org/psr/psr-20/) clock: `SystemClock` gives the current time, a frozen clock makes the tests repeatable.

## Basic Usage

A `RecurringEvent` runs again after each interval of its recurrence. `keepScheduled()` schedules it when it is missing, like after a migration of the site, so call it on every request, on `init` for WordPress:

```php
use ItalyStrap\Cron\GlobalScheduler;
use ItalyStrap\Cron\RecurringEvent;
use ItalyStrap\Cron\SystemClock;

$cleanup = new RecurringEvent('my_plugin_cleanup', new GlobalScheduler(), new SystemClock());

add_action('init', static fn() => $cleanup->keepScheduled('daily'));
add_action('my_plugin_cleanup', static function (): void {
    // Delete the old records.
});
```

The recurrence can change with the settings: `reschedule()` replaces it, `RecurringEvent::OFF` stops the event. The first run comes after the delay given to the constructor:

```php
$scan = new RecurringEvent('my_plugin_scan', new GlobalScheduler(), new SystemClock(), firstRunDelay: 3600);

$scan->reschedule('weekly');
$scan->reschedule(RecurringEvent::OFF);
```

A `SingleEvent` runs once, like the next batch of a long job:

```php
use ItalyStrap\Cron\SingleEvent;

$batch = new SingleEvent('my_plugin_batch', new GlobalScheduler(), new SystemClock());
$batch->schedule();
```

Outside WordPress and in the tests, `InMemoryScheduler` implements the same `SchedulerInterface`: it keeps the events, nothing runs them.

## Custom recurrences

WP-Cron knows `hourly`, `twicedaily`, `daily` and `weekly`. `GlobalRecurrences` adds others, it is a subscriber of [italystrap/event](https://github.com/ItalyStrap/event):

```php
use ItalyStrap\Cron\GlobalRecurrences;
use ItalyStrap\Cron\Recurrence;

$subscriberRegister->addSubscriber(new GlobalRecurrences(
    new Recurrence('monthly', 30 * DAY_IN_SECONDS, __('Once a month', 'my-plugin')),
));
```

Without italystrap/event, add its `add()` method to the `cron_schedules` filter.

## Deactivation

The events stay in WP-Cron after the deactivation of the plugin. `ScheduledEvents` clears them together:

```php
use ItalyStrap\Cron\ScheduledEvents;

register_deactivation_hook(__FILE__, [
    new ScheduledEvents(new GlobalScheduler(), 'my_plugin_cleanup', 'my_plugin_scan', 'my_plugin_batch'),
    'clearAll',
]);
```

## Development

The integration tests run on WordPress with SQLite, PHP needs the `pdo_sqlite` extension:

```bash
composer install
composer wp:setup   # downloads WordPress in tests/_wordpress and installs it
composer qa         # ECS, PHPStan, the unit and the integration tests
```

## License

GPL-2.0-or-later, see [LICENSE](LICENSE).
