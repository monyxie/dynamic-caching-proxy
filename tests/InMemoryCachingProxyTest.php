<?php

namespace Monyxie\Dycap\Tests;

use Monyxie\Dycap\InMemoryCachingProxy;
use PHPUnit\Framework\TestCase;

class InMemoryCachingProxyTest extends TestCase
{
    public function test() {
        $arguments = [1, null, false, new \stdClass(), json_decode('["a":1,"b":{}]')];
        $object = $this->getMockBuilder("\stdClass")
            ->setMethods(["func"])
            ->getMock();
        $object->expects($this->exactly(2))
            ->method('func')
            ->willReturn(serialize($arguments));

        $proxy = new InMemoryCachingProxy($object);

        $expected = call_user_func_array([$object, 'func'], $arguments);
        $this->assertEquals(
            $expected,
            call_user_func_array([$proxy, 'func'], $arguments)
        );
        $this->assertEquals(
            $expected,
            call_user_func_array([$proxy, 'func'], $arguments)
        );
    }
}
