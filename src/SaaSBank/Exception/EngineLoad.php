<?php

/**
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class SaaSBank_Exception_EngineLoad extends Pluf_Exception
{

    /**
     * یک نمونه از این کلاس ایجاد می‌کند.
     *
     * @param string $message            
     * @param Pluf_Exception $previous            
     * @param string $link            
     * @param string $developerMessage            
     */
    public function __construct ($message = "Impossible to load engine.", $previous = null, $link = null, 
            $developerMessage = null)
    {
        // XXX: maso, 1395: تعیین کد خطا
        parent::__construct($message, 4401, $previous, 500, $link, 
                $developerMessage);
    }
}