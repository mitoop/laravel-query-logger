<h1 align="center">Laravel Query Logger</h1>

<p align="center">🔮 轻松记录 SQL 执行日志，助力调试与分析</p>


## 安装
```shell
composer require mitoop/laravel-query-logger
```

## 配置
在 config/logging.php 中添加日志频道配置：
```php
<?php

return [
    'channels' => [
        // 其他日志频道配置...
        'sql' => [
            'driver' => 'daily',
            'path' => storage_path('logs/sql.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'permission' => 0664,
        ],
    ],

    // 查询日志相关配置
    'query' => [
        'enabled' => env('ENABLE_QUERY_LOG', false), // 总开关，是否启用 SQL 查询日志
        'channel' => 'sql', // 日志记录频道
        'excluded_tables' => ['telescope_'], // 排除表名，支持前缀匹配
    ],
];
```
## 使用
#### 默认行为
当 `query.enabled` 设置为 `true` 时，包会自动记录所有的 SQL 查询日志，无需额外配置。

#### 自定义触发条件
如果你想根据自定义条件控制 SQL 日志的记录，可以在 `AppServiceProvider` 的 `boot()` 方法中，调用 `QueryDebugger::enableWhen()` 设置触发条件。

日志仅在 **总开关开启** 且 **自定义条件为真** 时，才会被记录。

```php
<?php
use Mitoop\LaravelQueryLogger\SqlDebug;

public function boot()
{
    QueryDebugger::enableWhen(function () {
        return is_local() || is_dev() || request()->hasCookie('debug_sql');
    });
}
```

