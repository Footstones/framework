<?php
include dirname(__DIR__).'/vendor/autoload.php';

use Footstones\Framework\Kernel;

$config = include __DIR__.'/config.php';

$kernel = new Kernel($config);
$kernel->boot();

$exampleService = $kernel->rpc('node', 'ExampleService');
$user = $exampleService->getUser();

echo (!empty($user['name']) ? 'success' : 'fail') . "\n";
