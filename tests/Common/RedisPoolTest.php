<?php

namespace Footstones\Framework\Test\Common;

use  Footstones\Framework\Common\RedisPool;
use \Redis;
use \RedisException;
use \Exception;

class RedisPoolTest extends \PHPUnit_Framework_TestCase
{

    public function testRedis()
    {
        $cnf = [
            'host' => '127.0.0.1',
            'port' => 6379,
            'timeout' => 5,
            'reserved' => null,
            'retry_interval' => 100,
        ];

        exec('service redis-server start', $output, $code);
        var_dump($output, $code);

        try {

            $redis = new Redis();
            $connected = $redis->connect($cnf['host'], $cnf['port'], $cnf['timeout'], $cnf['reserved'], $cnf['retry_interval']);

            sleep(1000);

            // exec('service redis-server stop', $output, $code);
// exit();
        // var_dump($output, $code);
        
            $rst = $redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);

// echo 'rst:';var_dump($rst);

        } catch (RedisException $e) {
            var_dump($e);
            
        }

        // var_dump($connected);

    }


    public function testX()
    {
        return ;
        $config = [
            'default' => [
                'host' => '127.0.0.1',
                'port' => 6379,
                'timeout' => 5,
                'reserved' => null,
                'retry_interval' => 100,
            ]
        ];

        $pool = RedisPool::init($config);

        $reids = $pool->getRedis();

        $rst = $pool->getRedis()->set('testunit_1', 'xxx');

        var_dump($rst);

        exec('service redis-server stop', $output, $code);
        var_dump($output, $code);

        try {
            $rst = $pool->getRedis()->set('testunit_1', 'xxx');
        } catch (\Exception $e) {
            var_dump($e->getMessage(), $e->getCode());
        }


    }
}
