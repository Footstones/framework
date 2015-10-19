<?php

namespace Test;

use Footstones\Framework\Kernel;

class KernelTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \RuntimeException
     */
    public function testDI()
    {
        $kernel = $this->getKernel();
        $kernel->DI('XXXX');
        $logger = $kernel->DI('logger');

        $this->assertEquals(get_class($logger), 'Logger');
    }

    public function testEnv()
    {
        $kernel = $this->getKernel();
        $kernel->setEnv(array('root_dir' => __DIR__));
        $this->assertEquals($kernel->getEnv('root_dir'), __DIR__);
    }

    protected function getKernel()
    {
        return Kernel::instance();
    }
}
