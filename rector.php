<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersion;
use Rector\Config\Level\CodeQualityLevel;
use Rector\Config\Level\DeadCodeLevel;
use Rector\Config\Level\TypeDeclarationLevel;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/etc',
        __DIR__.'/src',
        __DIR__.'/tests',
    ])
    // uncomment to reach your current PHP version
    ->withPhpSets(php84: true)
    ->withPhpVersion(PhpVersion::PHP_84)
    ->withAttributesSets(all: true)
    ->withTypeCoverageLevel(count(TypeDeclarationLevel::RULES))
    ->withDeadCodeLevel(count(DeadCodeLevel::RULES))
    ->withCodeQualityLevel(count(CodeQualityLevel::RULES));
