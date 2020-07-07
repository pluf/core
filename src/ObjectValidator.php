<?php
namespace Pluf;

class ObjectValidator
{

    private static ?ObjectValidator $instance;

    public static function getInstance(): ObjectValidator
    {
        if (isset(self::$instance)) {
            return self::$instance;
        }
    }

    /**
     * Checks object and throw exception if the object is not valid
     *
     * @param Object $object
     */
    public function check($object)
    {}
}

