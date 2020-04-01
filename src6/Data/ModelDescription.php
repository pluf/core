<?php
namespace Pluf\Data;

use ArrayAccess;
use Iterator;

class ModelDescription implements Iterator, ArrayAccess
{
    private array $properties = [];
    
    public function getProperty(string $name): ModelProperty {}
    public function setProperty(string $name, ModelProperty $property): ModelProperty {}
    
    
    public function getView(string $name): array {}
    public function setView(string $name, array $view): void {}
    public function hasView(?string $name = null): bool {}
    public function getIndexes(): array {}

    public function next(){}
    public function valid(){}
    public function current(){}
    public function rewind(){}
    public function key(){}
    
    public function offsetExists ($offset) {}
    public function offsetGet ($offset) {}
    public function offsetSet ($offset, $value) {}
    public function offsetUnset ($offset) {}

}

