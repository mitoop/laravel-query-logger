<?php

namespace Mitoop\LaravelQueryLogger;

class Condition
{
    protected static $evaluator;

    protected static $excludedTables = [];

    public static function using(callable $evaluator)
    {
        static::$evaluator = $evaluator;
    }

    public static function evaluate(): bool
    {
        return isset(static::$evaluator) ? (static::$evaluator)() : true;
    }

    public static function excludeTables(array $tables): void
    {
        static::$excludedTables = $tables;
    }

    public static function shouldExclude(string $sql): bool
    {
        if (empty(static::$excludedTables)) {
            return false;
        }

        $pattern = '/\b(?:FROM|JOIN|INTO|UPDATE|DELETE FROM)\s+["`]?('.implode('|', array_map('preg_quote', static::$excludedTables)).')\w*["`]?\b/i';

        return preg_match($pattern, $sql) === 1;
    }
}
