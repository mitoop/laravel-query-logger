<?php

namespace Mitoop\LaravelQueryLogger;

class TriggerManager
{
    protected $app;

    private $key;

    public function __construct($app)
    {
        $this->app = $app;
        $this->key = self::class.'@binding_key';

        if (! $this->app->bound($this->key)) {
            $this->app->instance($this->key, true);
        }
    }

    public function bindTriggerCondition(callable $condition): self
    {
        $this->app->instance($this->key, (bool) $condition());

        return $this;
    }

    public function hasTriggerConditionMet(): bool
    {
        return $this->app->bound($this->key) && $this->app->make($this->key) === true;
    }
}
