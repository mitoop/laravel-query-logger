
<h1 align="center">Laravel Query Logger</h1>
<p align="center">🍎 记录 SQL 执行日志</p>

## 安装
```shell
composer require mitoop/laravel-query-logger
```

## 配置
在 `config/logging.php` 新增配置
```php
<?php

return [
    'channels' => [
        ...        
        'sql' => [
            'driver' => 'daily',
            'path' => storage_path('logs/sql.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'permission' => 0664,
        ],
    ],

    // 增加配置
    'query' => [
        'enabled' => env('ENABLE_QUERY_LOG', false), // [总开关] 是否开启 SQL 查询日志记录
        'channel' => 'sql' // 选择日志记录的频道
    ]
];
```

可以在 `AppServiceProvider` 的 `register` 方法中绑定自定义触发条件
- **默认行为**：如果没有绑定触发条件，默认情况下，触发条件为 `true`，日志记录完全依赖于总开关 `query.enabled` 配置。
- **自定义触发条件**：绑定触发条件后，SQL 查询日志将仅在 **总开关** 和 **触发条件** 都为 `true` 时才会记录。

```php
public function register()
{
    $this->app->bind(TriggerManager::BIND_KEY, function() {
        return true; // 根据自定义逻辑返回 true 或 false
    });
}
```

