<?php

namespace Mitoop\LaravelQueryLogger;

class ExcludedTables
{
    /**
     * @var array<string, string>
     */
    protected static $patterns = [];

    public static function matches(string $sql, array $tables): bool
    {
        $pattern = static::pattern($tables);

        if ($pattern === null) {
            return false;
        }

        $normalized = str_replace(['`', '"', '[', ']'], '', $sql);

        return preg_match($pattern, $normalized) === 1;
    }

    /**
     * @param  array<int, mixed>  $tables
     */
    protected static function pattern(array $tables): ?string
    {
        $names = [];

        foreach ($tables as $table) {
            if (! is_string($table) || $table === '') {
                continue;
            }

            $names[] = preg_quote($table, '/');
        }

        if ($names === []) {
            return null;
        }

        $key = implode("\0", $names);

        if (! isset(static::$patterns[$key])) {
            static::$patterns[$key] = '/(?:\bfrom|\bjoin|\binto|\bupdate)\s+(?:[A-Za-z0-9_]+\.)?('.implode('|', $names).')\w*/i';
        }

        return static::$patterns[$key];
    }
}
