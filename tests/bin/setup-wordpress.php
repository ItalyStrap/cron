<?php

/**
 * Installs WordPress with SQLite in tests/_wordpress for the integration tests: it downloads
 * the core when the folder is empty, then configures and installs it when it has no database.
 */

declare(strict_types=1);

use lucatume\WPBrowser\WordPress\Database\SQLiteDatabase;
use lucatume\WPBrowser\WordPress\Installation;

require \dirname(__DIR__, 2) . '/vendor/autoload.php';

$wpRootDir = \dirname(__DIR__) . '/_wordpress';
$dataDir = $wpRootDir . '/data';

if (!\is_file($wpRootDir . '/wp-includes/version.php')) {
    if (!\is_dir($wpRootDir)) {
        \mkdir($wpRootDir, 0777, true);
    }

    Installation::scaffold($wpRootDir);
}

// WPLoader switches to a theme: a minimal one, the package needs no theme.
$themeDir = $wpRootDir . '/wp-content/themes/cron-test';
if (!\is_dir($themeDir)) {
    \mkdir($themeDir, 0777, true);
    \file_put_contents($themeDir . '/style.css', "/*\nTheme Name: Cron Test\n*/\n");
    \file_put_contents($themeDir . '/index.php', "<?php\n");
}

if (\is_file($dataDir . '/db.sqlite')) {
    echo "WordPress is installed in {$wpRootDir}\n";
    return;
}

if (!\is_dir($dataDir)) {
    \mkdir($dataDir, 0777, true);
}

$installation = new Installation($wpRootDir, false);
if (!\is_file($wpRootDir . '/wp-config.php')) {
    $installation->configure(new SQLiteDatabase($dataDir, 'db.sqlite'));
}

$installation->install('http://localhost', 'admin', 'password', 'admin@example.com', 'Cron Test');

echo "WordPress installed in {$wpRootDir}\n";
