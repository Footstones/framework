<?php

$kernel = include dirname(__DIR__).'/app/bootstrap.php';

$exampleService = $kernel->rpc('node', 'ExampleService');
$user = $exampleService->getUser();

echo (!empty($user['name']) ? 'success' : 'fail') . "\n";
