<?php
namespace Pluf;

use Pluf\Data\ModelDescription;
use Pluf\HTTP\Error500;
use Pluf\HTTP\RequestMapper;

abstract class ObjectMapper
{

    var ?string $objectName;

    /**
     * Creates new instance of the mapper
     *
     * @param string $objectName
     */
    public function __construct(string $objectName)
    {
        $this->objectName = $objectName;
    }

    /**
     * Fetchs and maps next object
     *
     * @return Object
     */
    public abstract function next($object): Object;

    /**
     * Checks if there is more item
     *
     * @return bool
     */
    public abstract function hasMore(): bool;

    public static function getInstance($input): ObjectMapper
    {
        if ($input instanceof HTTP\Request) {
            return new RequestMapper($input);
        }
        throw new Error500('No suitable object mapper fount for input: ' . get_class($input));
    }

    protected function fillObject($object, $values)
    {
        $md = ModelDescription::getInstance($object);
        if (! is_object($object)) {
            $object = $md->newInstance();
        }
        foreach ($md as $name => $property) {
            if ($property->editable && array_key_exists($name, $values)) {
                $object->$name = $values[$name];
            }
        }
        return $object;
    }
}

