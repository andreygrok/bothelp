<?php

namespace Src\Redis;

class RedisDriver
{
    /**
     * @var \Redis
     */
    private static $instance = null;

    protected function __construct()
    {

    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new \Redis();
            self::$instance->connect(
                $_ENV['REDIS_HOST'],
                $_ENV['REDIS_PORT']
            );
            self::$instance->auth($_ENV['REDIS_PASS']);
        }

        return self::$instance;
    }
}