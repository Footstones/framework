<?php

namespace Footstones\Dao;

use Footstones\Kernel;
use Footstones\Common\DynamicQueryBuilder;

abstract class BaseDao
{
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
        return new \PDOException($message, $code);
    }
}
