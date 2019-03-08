<?php


namespace Monyxie\Dycap;


use Psr\SimpleCache\CacheInterface;

/**
 * @package Monyxie\Dycap
 */
class Psr16CachingProxy
{
    /**
     * @var object
     */
    private $object;
    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @param object $object The "upstream" object.
     * @param CacheInterface $cache The cache implementation.
     */
    public function __construct($object, CacheInterface $cache)
    {
        $this->object = $object;
        $this->cache = $cache;
    }

    /**
     * Proxy method calls to the upstream object.
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function __call($name, $arguments)
    {
        $key = 'dycap:' . md5($name . ':' . serialize($arguments));
        $value = $this->cache->get($key, $this);
        if ($value === $this) {
            $value = call_user_func_array([$this->object, $name], $arguments);
            $this->cache->set($key, $value);
        }
        return $value;
    }
}