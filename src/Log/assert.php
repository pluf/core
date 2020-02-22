<?php
namespace Pluf\Log;

use Pluf\Bootstrap;

/**
 * Assertion handler.
 *
 * @param string $file
 *            Name
 *            of the file where the assert is called
 * @param string $line
 *            Line
 *            number of the file where the assert is called
 * @param string $code
 *            Code
 *            evaluated by the assert call
 */
function assert($file, $line, $code)
{
    if (! isset(\Pluf\Log::$level)) {
        \Pluf\Log::$level = Bootstrap::f('log_level', 10);
    }
    if (\Pluf\Log::$level <= \Pluf\Log::$assert_level and \Pluf\Log::$level != 10) {
        \Pluf\Log::$stack[] = array(
            microtime(true),
            \Pluf\Log::$assert_level,
            \Pluf\Log::$assert_mess,
            $file,
            $line,
            $code
        );
        if (! Bootstrap::f('log_delayed', false)) {
            \Pluf\Log::flush();
        }
    }
    \Pluf\Log::$assert_level = 6;
    \Pluf\Log::$assert_mess = null;
}