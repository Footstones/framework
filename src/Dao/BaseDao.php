<?php

namespace Footstones\Framework\Dao;

use Footstones\Framework\Kernel;
use Footstones\Framework\Common\DynamicQueryBuilder;
use Footstones\Framework\Common\DaoException;
use Footstones\Framework\Common\FieldSerializer;

abstract class BaseDao
{
    private static $cachedSerializer = array();

    public function db()
    {
        return Kernel::instance()->database();
    }

    protected function kernel()
    {
        return Kernel::instance();
    }

    protected function createDynamicQueryBuilder($conditions)
    {
        return new DynamicQueryBuilder($this->db(), $conditions);
    }

    protected function filterStartLimit(&$start, &$limit)
    {
        $start = (int) $start;
        $limit = (int) $limit;
    }

    protected function createDaoException($message = null, $code = 0)
    {
        return new DaoException($message, $code);
    }

    protected function createSerializer()
    {
        if (!isset(self::$cachedSerializer['field_serializer'])) {
            self::$cachedSerializer['field_serializer'] = new FieldSerializer();
        }
        return self::$cachedSerializer['field_serializer'];
    }
}
