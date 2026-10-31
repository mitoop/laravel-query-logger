<?php

namespace Mitoop\LaravelQueryLogger;

class QueryLogger
{
    /**
     * @var callable|null
     */
    protected static $evaluator;

    public static function logWhen(callable $evaluator): void
    {
        static::$evaluator = $evaluator;
    }

    public static function shouldLog(): bool
    {
        if (! is_callable(static::$evaluator)) {
            return true;
        }

        return (bool) call_user_func(static::$evaluator);
    }
}
