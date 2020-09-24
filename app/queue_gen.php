<?php
require_once "vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
/**
 * Генерирует фэйковые таски.
 */
$taskGenerator = new \Src\Queue\Generator();
$taskGenerator->run();