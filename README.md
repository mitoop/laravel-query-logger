# Laravel Query Logger

记录 SQL 执行日志

## 安装
```shell
$ composer require --dev mitoop/laravel-query-logger
```

## 配置
在 `config/logging.php` 新增配置
```php
<?php

return [
    'channels' => [
 
        ...
        
        // sql channel          
        'sql' => [
            'driver' => 'single',
            'path' => storage_path('logs/sql.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'permission' => 0664,
        ],
    ],

    // 配置
    'query' => [
        'enabled' => true, // 是否开启记录 SQL 日志
        'channel' => 'sql' // SQL 日志 channel
    ]
];
```
## 
