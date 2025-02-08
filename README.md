
<h1 align="center">Laravel Query Logger</h1>
<p align="center">🍎 记录 SQL 执行日志</p>

## 安装
```shell
composer require mitoop/laravel-query-logger
```

## 配置
在 `config/logging.php` 中添加一下日志频道配置
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

    // 新增日志记录配置
    'query' => [
        'enabled' => env('ENABLE_QUERY_LOG', false), // [总开关] 是否启用 SQL 查询日志
        'channel' => 'sql' // 配置日志记录频道
    ]
];
```
## 使用
#### 默认行为
当启用 SQL 查询日志（query.enabled 为 true）时，包默认会记录所有的 SQL 查询日志。你无需做额外配置。
#### 自定义触发条件
在 `AppServiceProvider` 的 `boot` 方法中，你可以设置自定义的 SQL 查询日志记录触发条件。

自定义触发条件后，SQL 日志将仅在 **总开关** 和 **自定义触发条件** 都为 `true` 时才会被记录。

示例：自定义触发条件
```php
public function boot()
{
     // 设置自定义触发条件
     Condition::using(function () {
         return is_local() || is_dev() || request()->hasCookie('debug_sql');
     });
}
```
#### 排除特定表
你可以使用 Condition::excludeTables() 方法设置不需要记录日志的表，支持表名前缀匹配。

示例：排除特定表
示例：自定义触发条件
```php
public function boot()
{
     // 排除以 `telescope_` 和 `logs_` 开头的表
     Condition::excludeTables(['telescope_', 'logs_']);
}
```

