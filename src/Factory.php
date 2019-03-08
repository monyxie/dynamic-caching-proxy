<?php


namespace Monyxie\Dycap;


use Psr\SimpleCache\CacheInterface;

/**
 * Class Factory
 * @package Monyxie\Dycap
 */
abstract class Factory
{
    /**
     * Create a InMemoryCachingProxy instance.
     * @param object $object The "upstream" object.
     * @return InMemoryCachingProxy
     */
    public static function inMemory($object)
    {
        return new InMemoryCachingProxy($object);
    }

    /**
     * Create a Psr16CachingProxy instance.
     * @param object $object The "upstream" object.
     * @param CacheInterface $cache The cache implementation.
     * @return Psr16CachingProxy
     */
    public static function psr16($object, CacheInterface $cache)
    {
        return new Psr16CachingProxy($object, $cache);
    }
}