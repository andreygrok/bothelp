<?php

namespace Src;

use Src\Logger\Logger;
use Src\Redis\RedisDriver;

class QueueListener
{
    use Logger;

    /**
     * @var RedisDriver
     */
    private $DB;

    /**
     * Будет эмулировать сохраненные состояния работы с конкретным аккаунтом.
     * @var array
     */
    private $accountsLock = [];

    /**
     * Worker constructor.
     */
    public function __construct()
    {
        $this->DB = RedisDriver::getInstance();
    }

    /**
     * Может работать как слушатель (раскомментировать while)
     * Для демонстрации времени одиночный проход.
     */
    public function run()
    {
        //while (true) {
            $this->writeLog('START WATCH');
            foreach ($this->DB->keys("*") as $key) {
                $account = $this->getAccountByKey($key);
                if (!isset($this->accountsLock[$account])) {
                    $this->generateSubProcess($account);
                }
            }
        //}
        echo "See log /app/logs/log.txt" . PHP_EOL;
    }

    private function getAccountByKey($key): int
    {
        preg_match('/^account_([0-9]+)_sort_([0-9]+)$/', $key, $matches);

        return $matches[1];
    }

    /**
     * Отправляем парсить конкретный аккаунт подпроцессом.
     * Т.к. по условиям задачи нужно использоваться sleep(1)
     * то делаем exec и гасим вывод в дев нулл, что бы не вешать все приложение.
     * @param int $account
     */
    private function generateSubProcess(int $account): void
    {
        $this->accountsLock[$account] = true;
        $command = "(php /var/www/app/account.php " . $account .") >/dev/null 2>&1 &";
        exec($command);
    }
}