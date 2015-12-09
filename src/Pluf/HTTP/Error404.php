<?php

/**
 * ساختار خطای کلی کاربر را تعیین می‌کند
 * 
 * @author maso
 *
 */
class Pluf_HTTP_Error404 extends Pluf_Exception
{

    public function __construct ($message = 'Resource not found.', $code = 404, $previous = null)
    {
        $status = 404;
        $link = Pluf::f('exception_404_link', '/wiki/page/en/404');
        $developerMessage = 'requested resource not found on the server.';
        parent::__construct($message, $code, $previous, $status, $link, 
                $developerMessage);
    }
}
