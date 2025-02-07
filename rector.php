<?php

declare(strict_types=1);

use Rector\CodingStyle\Enum\PreferenceSelfThis;
use Rector\CodingStyle\Rector\ClassMethod\ReturnArrayClassMethodToYieldRector;
use Rector\CodingStyle\Rector\MethodCall\PreferThisOrSelfMethodCallRector;
use Rector\CodingStyle\ValueObject\ReturnArrayClassMethodToYield;
use Rector\Config\RectorConfig;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->importNames();

    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_81,
        PHPUnitSetList::PHPUNIT_100,
        SetList::CODE_QUALITY,
        SetList::DEAD_CODE,
        SetList::PRIVATIZATION,
        SetList::NAMING,
        SetList::TYPE_DECLARATION,
        SetList::EARLY_RETURN,
        PHPUnitSetList::PHPUNIT_CODE_QUALITY,
        SetList::CODING_STYLE,
    ]);

    $rectorConfig->ruleWithConfiguration(
        PreferThisOrSelfMethodCallRector::class,
        [
            'PHPUnit\Framework\TestCase' => PreferenceSelfThis::PREFER_THIS,
        ]
    );

    $rectorConfig->ruleWithConfiguration(ReturnArrayClassMethodToYieldRector::class, [
        new ReturnArrayClassMethodToYield('PHPUnit\Framework\TestCase', '*provide*'),
    ]);

    // $rectorConfig->rule(RemoveJustPropertyFetchRector::class);

    $rectorConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/rules',
        __DIR__ . '/tests',
        __DIR__ . '/rules-tests',
        __DIR__ . '/config',
    ]);

    $rectorConfig->skip([
        StringClassNameToClassConstantRector::class,

        \Rector\CodingStyle\Rector\String_\UseClassKeywordForClassNameResolutionRector::class => [
            // not useful short class cases, possibly skip in the rule
            __DIR__ . '/rules/DowngradePhp70/Rector/MethodCall/DowngradeClosureCallRector.php',
            __DIR__ . '/rules/DowngradePhp71/Rector/StaticCall/DowngradeClosureFromCallableRector.php',
            __DIR__ . '/rules/DowngradePhp80/Rector/MethodCall/DowngradeReflectionClassGetConstantsFilterRector.php',
            __DIR__ . '/rules/DowngradePhp81/Rector/FuncCall/DowngradeFirstClassCallableSyntaxRector.php',
        ],

        // test paths
        '**/Fixture/*',
        '**/Source/*',
        '**/Expected/*',
    ]);


    $rectorConfig->import(__DIR__ . '/config/config.php');
    $rectorConfig->phpstanConfig(__DIR__ . '/phpstan-for-rector.neon');
};
