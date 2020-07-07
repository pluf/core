<?php
namespace Pluf\Data;

abstract class ModelEncoder
{
    public static function getInstance($type) {
    }
    
    
    
    public abstract function encode(Model $model);
    public abstract function decode(Model $model);
}

