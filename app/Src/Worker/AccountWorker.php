<?php

namespace Src\Worker;

use Src\Logger\Logger;
use Src\Redis\RedisDriver;

class AccountWorker
{
    use Logger;

    /**
     * @var RedisDriver
     */
    private $DB;

    /**
     * Worker constructor.
     */
    public function __construct()
    {
        $this->DB = RedisDriver::getInstance();
    }

    /**
     * @param int $account
     */
    public function run(int $account)
    {
        foreach ($this->getKeys($account) as $key) {
            $this->executeTask();
            $this->writeLog('execute task: ' . $key);
            $this->DB->del($key);
        }
    }

    private function executeTask(): void
    {
        sleep(1);
    }

    /**
     * Т.к. по условиям задачи генерируются пачки 1-10 тасков на аккаунт
     * то можем использовать обычный цикл без yield.
     * @param int $account
     * @return array
     */
    private function getKeys(int $account): array
    {
        $keys = [];
        foreach ($this->DB->keys('account_' . $account . '*') as $redisKey) {
            $sort = (int)str_replace('account_' . $account . '_sort_', '', $redisKey);
            $keys[$sort] = $redisKey;
        }
        ksort($keys);

        return $keys;
    }
}