<?php

namespace Mitoop\LaravelQueryLogger;

class Condition
{
    protected static $evaluator;

    public static function using(callable $evaluator)
    {
        static::$evaluator = $evaluator;
    }

    public static function evaluate(): bool
    {
        return isset(static::$evaluator) ? (static::$evaluator)() : true;
    }

    public static function shouldExclude(string $sql, array $tables): bool
    {
        if (empty($tables)) {
            return false;
        }

        $pattern = '/\b(?:FROM|JOIN|INTO|UPDATE|DELETE FROM)\s+["`]?('.implode('|', array_map('preg_quote', $tables)).')\w*["`]?\b/i';

        return preg_match($pattern, $sql) === 1;
    }
}
