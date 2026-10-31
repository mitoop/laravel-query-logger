<?php

namespace Mitoop\LaravelQueryLogger;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    public function boot(): void
    {
        if (! $this->app['config']->get('logging.query.enabled')) {
            return;
        }

        DB::listen(function ($event) {
            if (! QueryLogger::shouldLog()) {
                return;
            }

            $tables = $this->app['config']->get('logging.query.excluded_tables', []);

            if (ExcludedTables::matches($event->sql, is_array($tables) ? $tables : [])) {
                return;
            }

            Log::channel($this->app['config']->get('logging.query.channel'))->debug(
                QueryFormatter::line($event)
            );
        });
    }
}
