<?php
namespace Pluf\LoggerFormatter;

use Pluf\LoggerManager;

class Plain implements \Pluf\LoggerFormatter
{

    public function format(LoggerManager $loggerManager, string $level, string $message, array $context = []): string
    {
        $payload = '[' . microtime(false) . '] [' . $loggerManager->getKey() . '] [' . $level . '] ' . $message;
        foreach ($context as $key => $value) {
            $payload .= $key . ':' . serialize($value);
        }
        return $payload;
    }
}

