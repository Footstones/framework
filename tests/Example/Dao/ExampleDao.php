<?php

namespace Footstones\Framework\Test\Example\Dao;

use Footstones\Framework\Dao\BaseDao;

class ExampleDao extends BaseDao
{
    public function table($args = null)
    {
        return 'example';
    }
}
