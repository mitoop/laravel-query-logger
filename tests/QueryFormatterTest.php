<?php

namespace Mitoop\LaravelQueryLogger\Tests;

use Mitoop\LaravelQueryLogger\QueryFormatter;
use PHPUnit\Framework\TestCase;

class QueryFormatterTest extends TestCase
{
    public function test_duration_uses_the_query_time_in_milliseconds(): void
    {
        $this->assertSame('500μs', QueryFormatter::duration(0.5));
        $this->assertSame('12.5ms', QueryFormatter::duration(12.5));
        $this->assertSame('1.5s', QueryFormatter::duration(1500));
    }

    public function test_it_prefers_the_event_raw_sql(): void
    {
        $event = new class
        {
            public function toRawSql(): string
            {
                return 'select 1';
            }
        };

        $this->assertSame('select 1', QueryFormatter::sql($event));
    }

    public function test_it_formats_a_log_line(): void
    {
        $event = new class
        {
            public $time = 12.5;

            public $connection;

            public function toRawSql(): string
            {
                return 'select 1';
            }
        };
        $event->connection = new class
        {
            public function getDatabaseName(): string
            {
                return 'report';
            }
        };

        $this->assertSame('[report] [12.5ms] select 1', QueryFormatter::line($event));
    }
}
