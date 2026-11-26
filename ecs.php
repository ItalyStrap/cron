<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\Operator\NotOperatorWithSuccessorSpaceFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return ECSConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests/bin',
        __DIR__ . '/tests/src',
        __DIR__ . '/tests/unit',
        __DIR__ . '/tests/integration',
        __DIR__ . '/tests/params.php',
        __DIR__ . '/ecs.php',
        __DIR__ . '/rector.php',
    ])
    ->withSkip([
        NotOperatorWithSuccessorSpaceFixer::class,
    ])
    ->withSets([
        SetList::COMMON,
        SetList::CLEAN_CODE,
        SetList::PSR_12,
    ]);
