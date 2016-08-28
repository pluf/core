<?php

/**
 * ارسال لاگ به یک سرور دور با استفاده از متد POST.
 *
 * Fire a POST request agains a server with the payload being the
 * content of the log. The log is serialized as JSON. It is always
 * containing the current stack, so an array of log "lines".
 *
 * The configuration keys are:
 *
 * - 'log_remote_server' (localhost)
 * - 'log_remote_path' (/)
 * - 'log_remote_port' (8000)
 * - 'log_remote_headers' (array())
 *
 */
class Pluf_Log_Remote
{
    /**
     * Flush the stack to the remote server.
     *
     * @param $stack Array
     */
    public static function write($stack)
    {
        $payload = json_encode($stack);
        $out = 'POST '.Pluf::f('log_remote_path', '/').' HTTP/1.1'."\r\n";
        $out.= 'Host: '.Pluf::f('log_remote_server', 'localhost')."\r\n";
        $out.= 'Host: localhost'."\r\n";
        $out.= 'Content-Length: '.strlen($payload)."\r\n";
        foreach (Pluf::f('log_remote_headers', array()) as $key=>$val) {
            $out .= $key.': '.$val."\r\n";
        }
        $out.= 'Connection: Close'."\r\n\r\n";
        $out.= $payload;
        $fp = fsockopen(Pluf::f('log_remote_server', 'localhost'),
                        Pluf::f('log_remote_port', 8000),
                        $errno, $errstr, 5);
        fwrite($fp, $out);
        fclose($fp);
    }
}
