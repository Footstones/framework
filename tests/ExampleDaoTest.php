<?php
namespace Test;

use Footstones\Framework\Kernel;
use Footstones\Framework\Dao\ExampleDao;

class ExampleDaoTest extends \PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $example = $this->testCreate();
        $example2 = $this->getExampleDao()->get($example['id']);
        $this->assertEquals($example['id'], $example2['id']);
    }

    public function testSearch()
    {
        $fields = array(
            'name' => 'zhangsan',
            'age' => 21,
            'sex' =>1,
            'createdTime' => '222',
            'updatedTime' => '333'
        );
        $this->getExampleDao()->create($fields);

        $fields = array(
            'name' => 'wangwu',
            'age' => 21,
            'sex' =>1,
            'createdTime' => '444',
            'updatedTime' => '555'
        );
        $this->getExampleDao()->create($fields);

        $examples = $this->getExampleDao()->search(array('namelike' => 'wang'));
        $this->assertGreaterThan(0, count($examples));

        $examples = $this->getExampleDao()->searchCount(array('namelike' => 'wang'));
        $this->assertGreaterThan(0, count($examples));

        $examples = $this->getExampleDao()->search(array('createdTimeeqorgt' => '222'));
        $this->assertGreaterThan(1, count($examples));

        $examples = $this->getExampleDao()->search(array('createdTimegt' => '222'));
        $this->assertGreaterThan(0, count($examples));

        $examples = $this->getExampleDao()->search(array('createdTimeeqorlt' => '444'));
        $this->assertGreaterThan(1, count($examples));

        $examples = $this->getExampleDao()->search(array('createdTimelt' => '222'));
        $this->assertGreaterThan(0, count($examples));
    }

    public function testCreate()
    {
        $fields = array(
            'name' => 'lisi',
            'age' => 21,
            'sex' =>1
        );

        $example = $this->getExampleDao()->create($fields);
        $this->assertEquals($example['name'], $fields['name']);
        return $example;
    }

    protected function getExampleDao()
    {
        return $this->getKernel()->dao('ExampleDao');
    }

    protected function getKernel()
    {
        return Kernel::instance();
    }
}
