<?php

namespace Mitoop\LaravelQueryLogger;

class TriggerManager
{
    protected $app;

    public const BIND_KEY = 'mitoop.query.trigger';

    public function __construct($app)
    {
        $this->app = $app;

        if (! $this->app->bound(self::BIND_KEY)) {
            $this->app->instance(self::BIND_KEY, true);
        }
    }

    public function hasTriggerConditionMet(): bool
    {
        return $this->app->bound(self::BIND_KEY) && $this->app->make(self::BIND_KEY) === true;
    }
}
