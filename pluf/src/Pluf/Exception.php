<?php

/**
 * ساختار استاندارد مدیریت خطا را ایجاد می‌کند.
 * 
 * تمام خطاهایی که در سیستم ایجاد می‌شوند همگی توسعه یافته از این کلاس هستند. نکته ای
 * که باید به آن توجه کرد مدیریت خودکار این نوع خطا‌ها است. در صورتی که خطای تولید
 * شده از این نوع خطا باشد، به صورت مناسب برای برنامه‌های کاربری ارسال می‌شود.
 * 
 * @author maso<mostafa.barmshory@dpq.co.ir>
 *
 */
class Pluf_Exception extends Exception implements JsonSerializable
{

    protected $status;

    protected $link;

    protected $developerMessage;

    /**
     * یک نمونه از این کلاس ایجاد می‌کند.
     *
     * @param string $message            
     * @param string $code            
     * @param string $previous            
     */
    public function __construct ($message = "Unknown exception", $code = 5000, $previous = null, 
            $status = 500, $link = null, $developerMessage = null)
    {
        parent::__construct($message, $code, $previous);
        $this->status = $status;
        $this->link = $link;
        $this->developerMessage = $developerMessage;
    }

    public function getDeveloperMessage ()
    {
        return $this->developerMessage;
    }

    public function setDeveloperMessage ($message)
    {
        $this->developerMessage = $message;
    }

    public function getStatus ()
    {
        return $this->status;
    }

    public function setStatus ($status)
    {
        $this->status = $status;
    }

    public function jsonSerialize ()
    {
        if (Pluf::f('debug', false)) {
            return [
                    'code' => $this->code,
                    'status' => $this->status,
                    'link' => $this->link,
                    'message' => $this->message,
                    'developerMessage' => $this->developerMessage,
                    'stack' => $this->getTrace()
            ];
        } else {
            return [
                    'code' => $this->code,
                    'status' => $this->status,
                    'link' => $this->link,
                    'message' => $this->message
            ];
        }
    }
}



