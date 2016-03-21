<?php
namespace Footstones\Framework\Test;

use Footstones\Framework\Test\Example\Kernel;

use Footstones\Framework\Toolkit\M3U8Modifier;

class M3U8ModifierTest extends \PHPUnit_Framework_TestCase
{

    public function test()
    {
        $m3u8 = file_get_contents(__DIR__ . '/fixtures/test-1.m3u8');

        $modifier = new M3U8Modifier($m3u8);

        // $modifier->replaceSegmentDomain('http://unittest.com');
        // $modifier->replaceKeyUrl('http://unittest.com/this.a.real.key.url');

        // $modifier->resetSegmentMaxDuration(20.01);
        // $duration = $modifier->parseSegmentMaxDuration();

        // $this->assertEquals(21, $duration);

        // $modifier->cutSegmentBody();

        $modifier->merge($m3u8);


        var_dump($modifier->getContent());

    }

}