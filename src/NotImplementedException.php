<?php
namespace Pluf;

/**
 * عدم پیاده سازی فراخوانی در سیستم را تعیین می‌کند
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class NotImplementedException extends Exception
{

    /**
     * یک نمونه از این کلاس ایجاد می‌کند.
     *
     * @param string $message
     * @param string $code
     * @param string $previous
     */
    public function __construct($message = null, $previous = null, $link = null, $developerMessage = null)
    {
        if (! isset($message) || is_null($message)) {
            $message = 'Requested method is not implemented yet.';
        }
        parent::__construct($message, 5051, $previous, 500, $link, $developerMessage);
    }
}