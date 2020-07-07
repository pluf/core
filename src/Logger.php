<?php
/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. (http://dpq.co.ir)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
namespace Pluf;

use Psr\Log\LoggerInterface;
use Pluf;

/**
 * Internal Pluf Logger
 *
 * @author maso
 *        
 */
class Logger
{

    /**
     * The log stack.
     *
     * A logger function is just pushing the data in the log stack,
     * the writers are then called to write the data later.
     */
    public static $stack = array();

    /**
     * A simple storage to track stats.
     *
     * A good example is to store stats and at the end of the request,
     * push the info back in the log. You can for example store the
     * total time doing SQL or other things like that.
     */
    public static $store = array();

    /**
     * Different log levels.
     */
    const ALL = 1;

    const DEBUG = 1;

    const INFO = 2;

    const PERF = 3;

    const EVENT = 3;

    const WARN = 4;

    const ERROR = 5;

    const FATAL = 6;

    const ALERT = 7;

    const EMERGENCY = 8;

    const OFF = 0;

    /**
     * Used to reverse the log level to the string.
     */
    private static $reverse = array(
        0 => 'all',
        1 => 'debug',
        2 => 'info',
        3 => 'notice',
        4 => 'warning',
        5 => 'error',
        6 => 'critical',
        7 => 'alert',
        8 => 'emergency',
        9 => 'off'
    );

    private static $direct = array(
        'all' => 0,
        'debug' => 1,
        'info' => 2,
        'notice' => 3,
        'warning' => 4,
        'error' => 5,
        'critical' => 6,
        'alert' => 7,
        'emergency' => 8,
        'off' => 9
    );

    private static array $loggerManagers = [];

    private static ?LoggerFormatter $loggerFormater = null;

    private static ?LoggerAppender $loggerAppender = null;

    private static function _log(int $level, $message)
    {
        $loggerManager = self::getLogger('default');
        $loggerManager->log(self::$reverse[$level], $message);
    }

    /**
     * Log at the ALL level.
     */
    public static function log($message)
    {
        return self::_log(self::ALL, $message);
    }

    /**
     * Log at the DEBUG level.
     */
    public static function debug($message)
    {
        self::_log(self::DEBUG, $message);
    }

    public static function info($message)
    {
        self::_log(self::INFO, $message);
    }

    public static function perf($message)
    {
        self::_log(self::PERF, $message);
    }

    public static function event($message)
    {
        self::_log(self::EVENT, $message);
    }

    public static function warn($message)
    {
        self::_log(self::WARN, $message);
    }

    public static function error($message)
    {
        self::_log(self::ERROR, $message);
    }

    public static function fatal($message)
    {
        self::_log(self::FATAL, $message);
    }

    /**
     * Creates and return an instance of the logger with the given key
     *
     * @param string $key
     * @return LoggerInterface
     */
    public static function getLogger(string $key): LoggerInterface
    {
        if (array_key_exists($key, self::$loggerManagers)) {
            return self::$loggerManagers[$key];
        }
        if (! isset(self::$loggerFormater)) {
            $className = Pluf::getConfig('log_formater', LoggerFormatter\Plain::class);
            self::$loggerFormater = new $className();
        }
        if (! isset(self::$loggerAppender)) {
            $className = Pluf::getConfig('log_appender', LoggerAppender\Console::class);
            self::$loggerAppender = new $className();
        }
        $loggerManager = new LoggerManager($key, self::$loggerFormater, self::$loggerAppender);
        self::$loggerManagers[$key] = $loggerManager;
        return $loggerManager;
    }

    /**
     * Signal handler to flush the log.
     *
     * The name of the signal and the parameters are not used.
     */
    public static function flush()
    {
        foreach (self::$loggerManagers as $loggerManager) {
            $loggerManager->flush();
        }
    }

    public static function toLevelMarker(string $level): int
    {
        return self::$direct[$level];
    }

    /**
     * Signal handler to flush the log.
     *
     * The name of the signal and the parameters are not used.
     */
    public static function flushHandler($signal, &$params)
    {
        self::flush();
    }
}



