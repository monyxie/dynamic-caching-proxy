<?php

namespace Monyxie\Dycap\Tests;

use Monyxie\Dycap\Psr16CachingProxy;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;

class Psr16CachingProxyTest extends TestCase
{
    public function test() {
        $arguments = [1, null, false, new \stdClass(), json_decode('["a":1,"b":{}]')];
        $object = $this->getMockBuilder("\stdClass")
            ->setMethods(["func"])
            ->getMock();
        $object->expects($this->exactly(2))
            ->method('func')
            ->willReturn(serialize($arguments));

        $cache = $this->getMockBuilder(CacheInterface::class)
            ->setMethods(['get', 'set', 'getMultiple', 'setMultiple', 'delete', 'deleteMultiple', 'clear', 'has'])
            ->getMock();

        $cache->expects($this->at(0))
            ->method('get')
            ->willReturnArgument(1);

        $cache->expects($this->at(1))
            ->method('set');

        $cache->expects($this->at(2))
            ->method('get')
            ->willReturn(serialize($arguments));

        $proxy = new Psr16CachingProxy($object, $cache);

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
