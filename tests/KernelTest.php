<?php
namespace Footstones\Framework\Test;

use Footstones\Framework\Test\Example\Kernel;

class KernelTest extends \PHPUnit_Framework_TestCase
{
    public function testDao()
    {
        $dao1 = $this->kernel()->dao('ExampleDao');
        $dao2 = $this->kernel()->dao('ExampleDao');
        $this->assertSame($dao1, $dao2);
    }

    public function testService()
    {
        $service1 = $this->kernel()->service('ExampleService');
        $service2 = $this->kernel()->service('ExampleService');
        $this->assertSame($service1, $service2);
    }

    public function testConfig()
    {
        $config = $this->kernel()->config('database', []);
        $this->assertTrue(is_array($config));
    }

    public function testRpc()
    {
        $rpc1 = $this->kernel()->rpc('example', 'ExampleService');
        $rpc2 = $this->kernel()->rpc('example', 'ExampleService');

        $this->assertInstanceOf('Yar_Client', $rpc1);
        $this->assertSame($rpc1, $rpc2);
    }

    public function testDb()
    {
        $db1 = $this->kernel()->db();
        $db2 = $this->kernel()->db();

        $this->assertInstanceOf('Footstones\Framework\Dao\Connection', $db1);
        $this->assertSame($db1, $db2);
    }

    protected function kernel()
    {
        return Kernel::instance();
    }
}