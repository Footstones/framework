<?php

namespace Footstones\Framework\Service;

class ExampleService
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
