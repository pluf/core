<?php
namespace Pluf;

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
     *
     * @return string
     */
    public function getObjectName()
    {
        return $this->objectName;
    }

    /**
     *
     * @param string $objectName
     */
    public function setObjectName($objectName)
    {
        $this->objectName = $objectName;
    }

    /**
     * Fetchs and maps next object
     *
     * @return Object
     */
    public abstract function mapNext(): Object;

    /**
     * Checks if there is more item
     *
     * @return bool
     */
    public abstract function hasMore(): bool;
}

