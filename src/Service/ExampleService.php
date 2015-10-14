<?php

namespace Footstones\Framework\Service;

use Footstones\Framework\Common\ArrayToolkit;

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

    public function demo($paramters)
    {
        if (ArrayToolkit::requireds($paramters, array('id', 'name'))) {
            throw $this->createServiceException('Missing reuqired paramters', self::MISSING_PARAMETER);
        }

        $this->addLog('This is test log', 'warning');
    }
}
