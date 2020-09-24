<?php

namespace Src\Logger;

trait Logger
{
    public function writeLog($message, $fName = 'log.txt'): void
    {
        $logDir = __DIR__ . '/../../logs/';
        $fp = fopen($logDir . $fName, 'a+');
        if ($fp) {
            fwrite($fp, 'time: ' . date('d.m.Y H:i:s', time()) . ' message: ' . $message . PHP_EOL . PHP_EOL);
            fclose($fp);
        }
    }
}