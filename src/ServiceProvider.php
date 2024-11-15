<?php

namespace Mitoop\LaravelQueryLogger;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    public function boot()
    {
        if (! $this->app['config']->get('logging.query.enabled') || ! Condition::evaluate()) {
            return;
        }

        DB::listen(function ($event) {
            Log::channel($this->app['config']->get('logging.query.channel'))->debug(
                vsprintf('[%s] [%s] %s',
                    [
                        $event->connection->getDatabaseName(),
                        $this->formatDuration($event->time),
                        $this->getSql($event, version_compare($this->app->version(), '10.15.0', '>=')),
                    ]
                )
            );
        });
    }

    private function getSql($event, $lge1015)
    {
        $bindings = $event->connection->prepareBindings($event->bindings);

        if ($lge1015) {
            return $event->connection
                ->getQueryGrammar()
                ->substituteBindingsIntoRawSql(
                    $event->sql,
                    $bindings
                );
        }

        return preg_replace_callback('/(?<!\?)\?(?!\?)/', static function () use ($event, &$bindings) {
            $value = array_shift($bindings);

            switch (true) {
                case $value === null:
                    $value = 'null';

                    break;
                case is_numeric($value):
                    break;
                default:
                    $value = $event->connection->getPdo()->quote((string) $value);

                    break;
            }

            return $value;
        }, $event->sql);
    }

    private function formatDuration($seconds)
    {
        if ($seconds < 1) {
            return round($seconds * 1000).'μs';
        }

        if ($seconds < 1000) {
            return $seconds.'ms';
        }

        return round($seconds / 1000, 2).'s';
    }
}
