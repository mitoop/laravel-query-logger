<?php

namespace Mitoop\LaravelQueryLogger;

class TriggerManager
{
    protected $app;

    public const TRIGGER_BIND_KEY = self::class.'@trigger_key';

    public function __construct($app)
    {
        $this->app = $app;

        if (! $this->app->bound(self::TRIGGER_BIND_KEY)) {
            $this->app->instance(self::TRIGGER_BIND_KEY, true);
        }
    }

    public function hasTriggerConditionMet(): bool
    {
        return $this->app->bound(self::TRIGGER_BIND_KEY) && $this->app->make(self::TRIGGER_BIND_KEY) === true;
    }
}
