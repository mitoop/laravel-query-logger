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
            'level' => 'debug',
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
当 `query.enabled` 设置为 `true` 时，包会记录 SQL。日志形如：

```text
[report] [12.5ms] select * from `users` where `id` = 1
```

时间来自查询耗时，单位按区间显示为 `μs`、`ms` 或 `s`。

#### 自定义触发条件
在 `AppServiceProvider` 的 `boot()` 方法中调用 `QueryLogger::logWhen()`。总开关关闭时不会记录。总开关开启后，闭包在**每条 SQL 执行时**判断，不会在启动时缓存结果，因此可以读取当前请求：

```php
use Mitoop\LaravelQueryLogger\QueryLogger;

public function boot(): void
{
    QueryLogger::logWhen(function () {
        return app()->isLocal() || request()->cookie('debug_sql') === '9527';
    });
}
```

