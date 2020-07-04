<?php

namespace Pluf\HTTP;

use Pluf;

/**
 * ساختار خطای کلی کاربر را تعیین می‌کند
 * 
 * @author maso
 * @deprecated use Pluf\Exception\DoesNotExist
 */
class Error403 extends \Pluf\Exception
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
