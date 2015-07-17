<?php

/**
 * High performance logging infrastructure.
 *
 * Logging while keeping a high performance in production is hard, it
 * is even harder if we want to track the point in the code where the
 * log information is generated, for example the file name and line
 * number. PHP offers the assert statement which, used in a not so
 * conventional way can get everything in a very efficient way.
 *
 * Note that the messages do not need to be strings. You can log
 * whatever you want. How the message is then stored in your logs is
 * up to the writer you are using. This can be for example a JSON
 * fragment.
 *
 * The removal of constraints on the log message simplify the log
 * system as you can push into it categories or extra informations.
 *
 * In the log stack, each log message is microtimed together with the
 * log level as integer. You can convert the integer to string at
 * write time.
 *
 */
class Pluf_Log
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

    const DEBUG = 3;

    const INFO = 4;

    const PERF = 5;

    const EVENT = 6;

    const WARN = 7;

    const ERROR = 8;

    const FATAL = 9;

    const OFF = 10;

    /**
     * Used to reverse the log level to the string.
     */
    public static $reverse = array(
            1 => 'ALL',
            3 => 'DEBUG',
            4 => 'INFO',
            5 => 'PERF',
            6 => 'EVENT',
            7 => 'WARN',
            8 => 'ERROR',
            9 => 'FATAL'
    );

    /**
     * Current log level.
     *
     * By default, logging is not enabled.
     */
    public static $level = 10;

    /**
     * Current message in the assert log.
     */
    public static $assert_mess = null;

    /**
     * Current level of the message in the assert log.
     */
    public static $assert_level = 10;

    /**
     * Log the information in the stack.
     *
     * Flush the information if needed.
     *
     * @param $level Level
     *            to log
     * @param $message Message
     *            to log
     */
    private static function _log ($level, $message)
    {
        if (self::$level <= $level and self::$level != 10) {
            self::$stack[] = array(
                    microtime(true),
                    $level,
                    $message
            );
            if (! Pluf::f('log_delayed', false)) {
                self::flush();
            }
        }
    }

    /**
     * Base assert logger.
     *
     * The assert logging is a two step process as one need to go
     * through the assertion callback.
     *
     * @param $level Level
     *            to log
     * @param $message Message
     *            to log
     * @return bool false
     */
    private static function _alog ($level, $message)
    {
        self::$assert_level = $level;
        self::$assert_mess = $message;
        return false; // This will trigger the assert handler.
    }

    /**
     * Log at the ALL level.
     *
     * @param $message Message
     *            to log
     */
    public static function log ($message)
    {
        return self::_log(self::ALL, $message);
    }

    /**
     * Log at the DEBUG level.
     *
     * @param $message Message
     *            to log
     */
    public static function debug ($message)
    {
        self::_log(self::DEBUG, $message);
    }

    public static function info ($message)
    {
        self::_log(self::INFO, $message);
    }

    public static function perf ($message)
    {
        self::_log(self::PERF, $message);
    }

    public static function event ($message)
    {
        self::_log(self::EVENT, $message);
    }

    public static function warn ($message)
    {
        self::_log(self::WARN, $message);
    }

    public static function error ($message)
    {
        self::_log(self::ERROR, $message);
    }

    public static function fatal ($message)
    {
        self::_log(self::FATAL, $message);
    }

    /**
     * Assert log at the ALL level.
     *
     * @param $message Message
     *            to log
     */
    public static function alog ($message)
    {
        return self::_alog(self::ALL, $message);
    }

    /**
     * Assert log at the DEBUG level.
     *
     * @param $message Message
     *            to log
     */
    public static function adebug ($message)
    {
        self::_alog(self::DEBUG, $message);
    }

    public static function ainfo ($message)
    {
        self::_alog(self::INFO, $message);
    }

    public static function aperf ($message)
    {
        self::_alog(self::PERF, $message);
    }

    public static function aevent ($message)
    {
        self::_alog(self::EVENT, $message);
    }

    public static function awarn ($message)
    {
        self::_alog(self::WARN, $message);
    }

    public static function aerror ($message)
    {
        self::_alog(self::ERROR, $message);
    }

    public static function afatal ($message)
    {
        self::_alog(self::FATAL, $message);
    }

    /**
     * Flush the data to the writer.
     *
     * This reset the stack.
     */
    public static function flush ()
    {
        $writer = Pluf::f('log_handler', 'Pluf_Log_File');
        call_user_func(array(
                $writer,
                'write'
        ), self::$stack);
        self::$stack = array();
    }

    /**
     * Signal handler to flush the log.
     *
     * The name of the signal and the parameters are not used.
     *
     * @param $signal Name
     *            of the signal
     * @param
     *            &$params Parameters
     */
    public static function flushHandler ($signal, &$params)
    {
        self::flush();
    }

    /**
     * Activation of the low impact logging.
     *
     * When called, it enabled the assertions for debugging.
     */
    public static function activeAssert ()
    {
        assert_options(ASSERT_ACTIVE, 1);
        assert_options(ASSERT_WARNING, 0);
        assert_options(ASSERT_QUIET_EVAL, 1);
        assert_options(ASSERT_CALLBACK, 'Pluf_Log_assert');
    }

    /**
     * Increment a key in the store.
     *
     * It automatically creates the key as needed.
     *
     * @param $key Key
     *            to increment
     * @param $amount Amount
     *            to increase (1)
     */
    public static function inc ($key, $amount = 1)
    {
        if (! isset(Pluf_Log::$store[$key])) {
            Pluf_Log::$store[$key] = 0;
        }
        Pluf_Log::$store[$key] += $amount;
    }

    /**
     * Set a key in the store.
     *
     * @param $key Key
     *            to set
     * @param $value Value
     *            to set
     */
    public static function set ($key, $value)
    {
        Pluf_Log::$store[$key] = $value;
    }

    /**
     * Get a key from the store.
     *
     * @param $key Key
     *            to set
     * @param $value Default
     *            value (null)
     */
    public static function get ($key, $value = null)
    {
        return (isset(Pluf_Log::$store[$key])) ? Pluf_Log::$store[$key] : $value;
    }

    /**
     * Start the time to track.
     *
     * @param $key Tracker            
     */
    public static function stime ($key)
    {
        Pluf_Log::$store['time_tracker_' . $key] = microtime(true);
    }

    /**
     * End the time to track.
     *
     * @param $key Tracker            
     * @param $total Tracker
     *            to store the total (null)
     * @return float Time for this track
     */
    public static function etime ($key, $total = null)
    {
        $t = microtime(true) - Pluf_Log::$store['time_tracker_' . $key];
        if ($total) {
            Pluf_Log::inc('time_tracker_' . $total, $t);
        }
        return $t;
    }
}

/**
 * Assertion handler.
 *
 * @param $file Name
 *            of the file where the assert is called
 * @param $line Line
 *            number of the file where the assert is called
 * @param $code Code
 *            evaluated by the assert call
 */
function Pluf_Log_assert ($file, $line, $code)
{
    if (Pluf_Log::$level <= Pluf_Log::$assert_level and Pluf_Log::$level != 10) {
        Pluf_Log::$stack[] = array(
                microtime(true),
                Pluf_Log::$assert_level,
                Pluf_Log::$assert_mess,
                $file,
                $line,
                $code
        );
        if (! Pluf::f('log_delayed', false)) {
            Pluf_Log::flush();
        }
    }
    Pluf_Log::$assert_level = 6;
    Pluf_Log::$assert_mess = null;
}