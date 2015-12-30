<?php

$loader = include dirname(__DIR__).'/vendor/autoload.php';


use Footstones\Framework\Test\Example\Kernel;

$config = include __DIR__.'/config.php';

$kernel = new Kernel($config);
$kernel->boot();

return $kernel;
