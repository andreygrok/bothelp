<?php
require_once "vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
/**
 * Выполняет парсинг очереди.
 */
$worker = new \Src\QueueListener();
$worker->run();