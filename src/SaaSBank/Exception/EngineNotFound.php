<?php

/**
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class SaaSBank_Exception_EngineNotFound extends Pluf_Exception
{

    /**
     * یک نمونه از این کلاس ایجاد می‌کند.
     *
     * @param string $message            
     * @param Pluf_Exception $previous            
     * @param string $link            
     * @param string $developerMessage            
     */
    public function __construct ($message = "Engine not found.", $previous = null, $link = null, 
            $developerMessage = null)
    {
        // XXX: maso, 1395: تعیین کد خطا
        parent::__construct($message, 4401, $previous, 404, $link, 
                $developerMessage);
    }
}