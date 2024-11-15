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
}
