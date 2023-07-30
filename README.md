
<h1 align="center">Laravel Query Logger</h1>
<p align="center">🍎 记录 SQL 执行日志</p>

## 安装
```shell
composer require --dev mitoop/laravel-query-logger
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

    // 新增 query
    'query' => [
         // 是否开启记录
        'enabled' => env('ENABLE_QUERY_LOG', false),
         // 记录的频道
        'channel' => 'sql'
    ]
];
```
## 
