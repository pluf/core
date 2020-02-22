<?php
namespace Pluf;

class FormException extends Exception
{

    /**
     * یک نمونه از این کلاس ایجاد می‌کند.
     *
     * @param string $message
     * @param Form $form
     * @param string $previous
     */
    public function __construct($message, $form, $link = null, $developerMessage = null)
    {
        parent::__construct($message, 4000, null, 400, $link, $developerMessage);
        $this->data = $form->errors;
    }
}


