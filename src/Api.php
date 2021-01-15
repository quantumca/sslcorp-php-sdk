<?php

namespace SslCorp;

use SslCorp\Exception\MethodNotFoundException;

/**
 * @mixin Order
 */
class Api
{
    /**
     * @var Order[] $instances
     */
    protected $instances = [];

    public function __construct()
    {
        array_push($this->instances, new Order());
    }

    public function __call($name, $arguments)
    {
        foreach ($this->instances as $instance) {
            if (method_exists($instance, $name)) {
                return call_user_func_array([$instance, $name], $arguments);
            }
        }

        throw new MethodNotFoundException($name . '() ' . MethodNotFoundException::MESSAGE_PREFIX);
    }
}
