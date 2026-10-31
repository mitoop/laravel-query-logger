<?php

namespace Mitoop\LaravelQueryLogger;

class QueryFormatter
{
    /**
     * @param  object  $event
     */
    public static function line($event): string
    {
        return sprintf(
            '[%s] [%s] %s',
            $event->connection->getDatabaseName(),
            static::duration($event->time),
            static::sql($event)
        );
    }

    /**
     * @param  float|int|null  $milliseconds
     */
    public static function duration($milliseconds): string
    {
        $milliseconds = (float) $milliseconds;

        if ($milliseconds < 1) {
            return round($milliseconds * 1000).'μs';
        }

        if ($milliseconds < 1000) {
            return $milliseconds.'ms';
        }

        return round($milliseconds / 1000, 2).'s';
    }

    /**
     * @param  object  $event
     */
    public static function sql($event): string
    {
        return $event->toRawSql();
    }
}
