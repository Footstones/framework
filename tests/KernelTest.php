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

    protected function getKernel()
    {
        return Kernel::instance();
    }
}
