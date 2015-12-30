<?php

namespace Footstones\Framework\Test\Example\Service;

use Footstones\Framework\Service\BaseService;

class ExampleService extends BaseService
{
    public function getUser()
    {
        return array(
            'name' => 'James',
            'age' => '20',
            'sex' => 'male'
        );
    }

    public function createUser()
    {
        return array();
    }

}
