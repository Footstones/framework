<?php
namespace Footstones\Framework\Test;

use Footstones\Framework\Test\Example\Kernel;

class ExampleDaoTest extends \PHPUnit_Framework_TestCase
{

    public function testGetExample()
    {
        $example = $this->kernel()->dao('ExampleDao')->getExample(1);
    }

    protected function kernel()
    {
        return Kernel::instance();
    }
}