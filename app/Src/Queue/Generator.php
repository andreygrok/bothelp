<?php

namespace Src\Queue;

use Src\Redis\RedisDriver;

class Generator
{
    /**
     * @var RedisDriver
     */
    private $DB;

    /**
     * @var array
     */
    private $iterators = [];

    /**
     * @var array
     */
    private $accounts = [];

    /**
     * @var int
     */
    private $accountCount;

    /**
     * @var int
     */
    private $taskCount;

    /**
     * Generator constructor.
     */
    public function __construct()
    {
        $this->DB = RedisDriver::getInstance();
        $this->accountCount = $_ENV['ACCOUNT_COUNT'];
        $this->taskCount = $_ENV['TASK_COUNT'];
        $this->initAccount();
        $this->cleanOldTasks();
    }


    public function run(): void
    {

        $createdTaskCount = 0;
        while (true) {
            $account = $this->accounts[rand(0, ($this->accountCount - 1))];
            // генерирует рандомную пачку тасков для аккаунта
            $accountPackCount = rand(1, 10);
            while ($accountPackCount--) {
                $task = $this->makeFakeTask($account);
                $this->DB->set($task->key, serialize($task->toArray()));
                echo 'Task for account ' . $account . ' created' . PHP_EOL;
                $createdTaskCount++;
                if ($this->taskCount != 0 && $createdTaskCount > $this->taskCount) {
                    exit('Done' . PHP_EOL);
                }
            }
        }
    }

    /**
     * @param int $account
     * @return Task
     */
    private function makeFakeTask(int $account): Task
    {
        $this->iterators[$account]++;
        $task = new Task();
        $task->key = 'account_' . $account . '_sort_' . $this->iterators[$account];
        $task->accountId = $account;
        $task->dateInsert = new \DateTimeImmutable();

        return $task;
    }

    /**
     * Инициализирует аккаунты
     */
    private function initAccount(): void
    {
        $iter = $this->accountCount;
        while ($iter--) {
            $this->accounts[] = $iter;
            $this->iterators[$iter] = 0;
        }
    }

    /**
     * Чистит базу
     */
    private function cleanOldTasks(): void
    {
        foreach ($this->DB->keys('*') as $key) {
            $this->DB->del($key);
        }
    }
}