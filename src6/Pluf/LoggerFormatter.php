<?php
namespace Pluf;

/**
 * Formats a log
 *
 * @author maso
 *        
 */
interface LoggerFormatter
{

    public function format(LoggerManager $loggerManager, string $level, string $message, array $context = []): string;
}