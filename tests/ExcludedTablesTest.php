<?php

namespace Mitoop\LaravelQueryLogger\Tests;

use Mitoop\LaravelQueryLogger\ExcludedTables;
use PHPUnit\Framework\TestCase;

class ExcludedTablesTest extends TestCase
{
    public function test_an_empty_list_excludes_nothing(): void
    {
        $this->assertFalse(ExcludedTables::matches('select * from users', []));
        $this->assertFalse(ExcludedTables::matches('select * from users', ['']));
    }

    public function test_it_matches_table_prefixes_in_common_statements(): void
    {
        $tables = ['telescope_'];

        $this->assertTrue(ExcludedTables::matches('select * from telescope_entries', $tables));
        $this->assertTrue(ExcludedTables::matches('select * from `telescope_entries`', $tables));
        $this->assertTrue(ExcludedTables::matches('select * from report.telescope_entries', $tables));
        $this->assertTrue(ExcludedTables::matches('insert into telescope_entries values (1)', $tables));
        $this->assertTrue(ExcludedTables::matches('update telescope_entries set name = ?', $tables));
        $this->assertTrue(ExcludedTables::matches('delete from telescope_entries where id = 1', $tables));
        $this->assertTrue(ExcludedTables::matches('select * from orders join telescope_entries on orders.id = telescope_entries.id', $tables));
    }

    public function test_it_does_not_match_a_prefix_inside_another_identifier(): void
    {
        $this->assertFalse(ExcludedTables::matches('select * from other_telescope_entries', ['telescope_']));
        $this->assertFalse(ExcludedTables::matches('select * from users', ['telescope_']));
    }
}
