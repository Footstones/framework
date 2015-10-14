<?php

use Footstones\Framework\Kernel;

class ExampleServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Footstones\Framework\Exception\ServiceException
     * @expectedExceptionCode 51002
     */
    public function testDemo()
    {
        $parmaters = array(
            'name' => 'hello'
        );
        $this->getExampleService()->demo($parmaters);
        $parmaters['id'] = 1;
        $this->getExampleService()->demo($parmaters);
        $logfile = Kernel::instance()->config('log_dir').'/services.log';

        $this->assertStringEqualsFile($logfile, 'This is test log');
    }

    protected function getExampleService()
    {
        return Kernel::instance()->service('ExampleService');
    }
}
