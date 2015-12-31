<?php

namespace Footstones\Framework\Test\Example\Dao;

use Footstones\Framework\Dao\BaseDao;

class ExampleDao extends BaseDao
{

    public function getExample($id)
    {
        return $this->cache("{$this->table()}:{$id}", function () use ($id) {
            $sql = "SELECT * FROM {$this->table} WHERE id = ? LIMIT 1";
            return $this->db()->fetchAssoc($sql, array($id)) ?: null;
        });
    }

    public function table($args = null)
    {
        return 'example';
    }
}
