<?php

/*
 * Copyright (c) Fusonic GmbH. All rights reserved.
 * Licensed under the MIT License. See LICENSE file in the project root for license information.
 */

declare(strict_types=1);

use Rector\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector;
use Rector\Config\RectorConfig;
use Rector\Php81\Rector\ClassMethod\NewInInitializerRector;
use Rector\Php81\Rector\Property\ReadOnlyPropertyRector;
use Rector\PHPUnit\CodeQuality\Rector\Class_\PreferPHPUnitThisCallRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/src',
        __DIR__.'/tests',
    ])
    ->withPHPStanConfigs([__DIR__.'/phpstan.neon'])
    ->withPhpSets(php82: true)
    ->withComposerBased(
        phpunit: true,
        symfony: true,
    )
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        typeDeclarations: true,
        privatization: true,
        instanceOf: true,
        phpunitCodeQuality: true,
        symfonyCodeQuality: true,
        symfonyConfigs: true,
    )
    ->withSkip([
        FlipTypeControlToUseExclusiveTypeRector::class,
        NewInInitializerRector::class => [
            // TODO Remove in v0.6.0 or v1.0.0
            // ↓ This would break backwards compatibility
            __DIR__.'/src/CsvReader.php',
        ],
        PreferPHPUnitThisCallRector::class,
        // ↓ This would break backwards compatibility
        ReadOnlyPropertyRector::class,
    ]);
