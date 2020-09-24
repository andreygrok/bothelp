<?php
require_once "vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
/**
 * Обрабатывает таски конкретного аккаунта.
 */
$worker = new \Src\Worker\AccountWorker();
$worker->run($argv[1]);