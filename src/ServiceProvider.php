<?php

namespace Mitoop\LaravelQueryLogger;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    public function boot()
    {
        if (! $this->app['config']->get('logging.query.enabled', false)) {
            return;
        }

        DB::listen(function ($query) {
            $bindings = $query->connection->prepareBindings($query->bindings);

            $sql = preg_replace_callback('/(?<!\?)\?(?!\?)/', static function () use ($query, &$bindings) {
                $value = array_shift($bindings);

                switch ($value) {
                    case null:
                        $value = 'null';

                        break;
                    case is_bool($value):
                        $value = $value ? 'true' : 'false';

                        break;
                    case is_numeric($value):
                        break;
                    default:
                        $value = $query->connection->getPdo()->quote((string) $value);

                        break;
                }

                return $value;
            }, $query->sql);

            Log::channel($this->app['config']->get('logging.query.channel'))->debug(
                vsprintf('[%s] [%s] %s',
                    [
                        $query->connection->getDatabaseName(),
                        self::formatDuration($query->time),
                        $sql,
                    ]
                )
            );
        });
    }

    protected static function formatDuration($seconds)
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
