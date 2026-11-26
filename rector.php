<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests/src',
        __DIR__ . '/tests/unit',
        __DIR__ . '/tests/integration',
    ])
    ->withPhpSets()
    ->withPreparedSets(
        codeQuality: true,
        typeDeclarations: true,
    )
    ->withAttributesSets(phpunit: true)
    ->withImportNames(
        importShortClasses: false,
        removeUnusedImports: true
    );
