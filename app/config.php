<?php

return [
    'database' => [
        'driver' => 'pdo_mysql',
        'host' => '127.0.0.1',
        'port' => 3306,
        'name' => 'example',
        'user' => 'root',
        'password' => 'root',
        'charset' => 'utf8',
    ],
    'namespace' => 'Footstones\Framework',
    'kernel.DI' => [
        'logger' => 'Footstones\Framework\Logger'
    ],
    'log_dir' => __DIR__,
    'rpc' => [
        'timeout' => 5000, // 单位：微秒
        'connect_timeout' => 2000,
        'entry_points' => [
            'node' => 'http://localhost:7000/rpc-server.php'
        ],
    ]
];
