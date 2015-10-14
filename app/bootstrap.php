<?php

include dirname(__DIR__).'/vendor/autoload.php';

use Footstones\Framework\Kernel;

$config = include __DIR__.'/config.php';

$kernel = new Kernel($config);
$kernel->boot();

return $kernel;
