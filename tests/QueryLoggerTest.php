<?php

namespace Mitoop\LaravelQueryLogger\Tests;

use Mitoop\LaravelQueryLogger\QueryLogger;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

class QueryLoggerTest extends TestCase
{
    protected function tearDown(): void
    {
        $property = new ReflectionProperty(QueryLogger::class, 'evaluator');
        $property->setAccessible(true);
        $property->setValue(null, null);

        parent::tearDown();
    }

    public function test_logging_is_enabled_when_no_condition_is_set(): void
    {
        $this->assertTrue(QueryLogger::shouldLog());
    }

    public function test_the_condition_is_evaluated_every_time(): void
    {
        $enabled = false;

        QueryLogger::logWhen(function () use (&$enabled) {
            return $enabled;
        });

        $this->assertFalse(QueryLogger::shouldLog());

        $enabled = true;

        $this->assertTrue(QueryLogger::shouldLog());
    }
}
