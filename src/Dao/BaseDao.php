<?php

namespace Footstones\Framework\Dao;

use Footstones\Framework\Kernel;
use Footstones\Framework\Common\DynamicQueryBuilder;
use Footstones\Framework\Common\DaoException;
use Footstones\Framework\Common\FieldSerializer;

abstract class BaseDao
{
    private static $cachedSerializer = [];

    protected $kernel;

    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
    }

    abstract function table($args = null);

    public function db()
    {
        return $this->kernel->db();
    }

    protected function kernel()
    {
        return $this->kernel;
    }

    protected function cache($key, $callback, $ttl = 864000)
    {
        if (is_array($key)) {
            $group = $key[0];
            $key = $key[1];
        } else {
            $group = 'default';
        }

        $redis = $this->kernel()->redis($group, true);

        $data = $redis->get($key);
        if ($data) {
            return $data;
        }

        $data = $callback();

        $redis = $this->kernel()->redis($group);
        if ($ttl && $ttl > 0) {
            $redis->setex($key, $ttl, $data);
        } else {
            $redis->set($key, $data);
        }

        return $data;
    }

    protected function builder($conditions)
    {
        return new DynamicQueryBuilder($this->db(), $conditions);
    }

    protected function limitation(&$start, &$limit)
    {
        $start = (int) $start;
        $limit = (int) $limit;
    }

    protected function exception($message = null, $code = 0)
    {
        return new DaoException($message, $code);
    }

    protected function serializer()
    {
        if (!isset(self::$cachedSerializer['field_serializer'])) {
            self::$cachedSerializer['field_serializer'] = new FieldSerializer();
        }
        return self::$cachedSerializer['field_serializer'];
    }
}
