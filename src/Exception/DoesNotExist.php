<?php

/**
 * Default not found exception
 * 
 * @author webpich.com
 *
 */
class Exception_DoesNotExist extends Exception
{

    public function __construct($message = null, $previous = null, $link = null, $developerMessage = null)
    {
        if (! isset($message) || is_null($message)) {
            $message = 'Resource does not exist.';
        }
        parent::__construct($message, 4102, $previous, 404, $link, $developerMessage);
    }
}