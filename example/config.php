<?php

return [
    'database' => [
        'driver' => 'pdo_mysql',
        'host' => '127.0.0.1',
        'port' => 3306,
        'name' => 'your_database',
        'user' => 'database_user',
        'password' => 'database_password',
        'charset' => 'utf8',
    ],
    'namespace' => 'Footstones\Framework',
    'rpc' => [
        'timeout' => 5000, // 单位：微秒
        'connect_timeout' => 2000,
        'entry_points' => [
            'node' => 'http://localhost:7000/rpc-server.php'
        ],
    ]
];
