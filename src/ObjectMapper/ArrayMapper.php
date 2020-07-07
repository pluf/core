<?php
namespace Pluf\ObjectMapper;

use Pluf\HTTP\Error500;

/**
 * Maps Array of arrayes to object
 *
 * Supported mime types:
 *
 *
 * @author maso
 *        
 */
class ArrayMapper extends \Pluf\ObjectMapper
{

    private int $pointer = 0;

    protected array $values = [];

    public function __construct(array $data)
    {
        $isList = true;
        foreach ($data as $item) {
            if (! is_array($item)) {
                $isList = false;
            }
        }
        if ($isList) {
            $this->values = $data;
        } else {
            $this->values[] = $data;
        }
        $this->pointer = 0;
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\ObjectMapper::next()
     */
    public function next($object): Object
    {
        if (! $this->hasMore()) {
            throw new Error500('No more item exist in the array mapper.');
        }
        $values = $this->values[$this->pointer ++];
        return $this->fillObject($object, $values);
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\ObjectMapper::hasMore()
     */
    public function hasMore(): bool
    {
        return $this->pointer < count($this->values);
    }
}

