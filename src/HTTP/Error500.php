<?php
namespace Pluf\HTTP;

use Pluf;

/**
 * خطای داخلی سیستم را به صورت کلی تعیین می‌کند
 *
 * @author maso
 *        
 */
class Error500 extends \Pluf\Exception
{

    public function __construct($message = null, $code = 5000, $previous = null)
    {
        $status = 500;
        $link = Pluf::f('exception_5000_link', '/wiki/page/en/internal-error');
        $developerMessage = 'Unknown exception happend.';
        parent::__construct($message, $code, $previous, $status, $link, $developerMessage);
    }
}
