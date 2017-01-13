<?php

/**
 * خطای پیدا نشدن یک صفحه از ویکی
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class Book_Exception_PageNotFound extends Pluf_Exception
{

    /**
     * یک نمونه از این کلاس ایجاد می‌کند.
     *
     * @param string $message            
     * @param Pluf_Exception $previous            
     * @param string $link            
     * @param string $developerMessage            
     */
    public function __construct ($message = "requested wiki page not found.", $previous = null, $link = null, 
            $developerMessage = null)
    {
        parent::__construct($message, 4301, $previous, 404, $link, 
                $developerMessage);
    }
}