<?php

namespace Mitoop\LaravelQueryLogger;

class SqlDebug
{
    protected static $evaluator = null;

    protected static $cached = null;

    public static function enableWhen(callable $evaluator): void
    {
        static::$evaluator = $evaluator;
        static::$cached = null;
    }

    public static function isEnabled(): bool
    {
        if (static::$cached !== null) {
            return static::$cached;
        }

        if (! static::$evaluator) {
            return true;
        }

        return static::$cached = (bool) call_user_func(static::$evaluator);
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
