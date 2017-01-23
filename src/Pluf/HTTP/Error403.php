<?php

/**
 * ساختار خطای کلی کاربر را تعیین می‌کند
 * 
 * @author maso
 *
 */
class Pluf_HTTP_Error403 extends Pluf_Exception
{

    public function __construct ($message = 'Resource not found.', $previous = null)
    {
        $status = 403;
        $link = Pluf::f('exception_404_link', '/wiki/page/en/404');
        $developerMessage = 'requested resource not found on the server.';
        parent::__construct($message, 403, $previous, $status, $link, 
                $developerMessage);
    }
}
