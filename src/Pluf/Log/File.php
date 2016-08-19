<?php

/**
 * ذخیره کردن لاگ‌ها در فایل
 *
 * This is the simplest logger. You can use it as a base to create
 * more complex loggers. The logger interface is really simple and use
 * some helper functions from the main <code>Pluf_Log</code> class.
 *
 * The only required static method of a log writer is
 * <code>write</code>, which takes the stack to write as parameter.
 *
 * The only configuration variable of the file writer is the path to
 * the log file 'pluf_log_file'. By default it creates a
 * <code>pluf.log</code> in the configured tmp folder.
 *
 */
class Pluf_Log_File
{
    /**
     * Flush the stack to the disk.
     *
     * @param $stack Array
     */
    public static function write($stack)
    {
        $file = Pluf::f('pluf_log_file', 
                        Pluf::f('tmp_folder', '/tmp').'/pluf.log');
        $out = array();
        foreach ($stack as $elt) {
            $out[] = date(DATE_ISO8601, (int) $elt[0]).' '.
                Pluf_Log::$reverse[$elt[1]].': '.
                json_encode($elt[2]);
        }
        file_put_contents($file, implode(PHP_EOL, $out).PHP_EOL, FILE_APPEND);
    }
}
