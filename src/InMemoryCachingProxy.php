<?php


namespace Monyxie\Dycap;


class InMemoryCachingProxy
{
    /**
     * @var object
     */
    private $object;
    /**
     * @var array
     */
    private $cache;

    /**
     * @param object $object The "upstream" object.
     */
    public function __construct($object)
    {
        $this->object = $object;
        $this->cache = [];
    }

    /**
     * Proxy method calls to the upstream object.
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        $key = md5($name . ':' . serialize($arguments), true);
        if (!isset($this->cache[$key])) {
            $this->cache[$key] = call_user_func_array([$this->object, $name], $arguments);
        }
        return $this->cache[$key];
    }
}