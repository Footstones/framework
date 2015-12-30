<?php

return [
    'database' => [
        'driver' => 'pdo_mysql',
        'host' => '127.0.0.1',
        'port' => 3306,
        'name' => 'example',
        'user' => 'root',
        'password' => '',
        'charset' => 'utf8',
    ],
    'log_dir' => __DIR__,
    'rpc' => [
        'timeout' => 5000, // 单位：微秒
        'connect_timeout' => 2000,
        'entry_points' => [
            'example' => 'http://localhost:7000/rpc-server.php'
        ],
    ]
];
