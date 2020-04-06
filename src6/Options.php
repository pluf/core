<?php
namespace Pluf;

use ArrayAccess;
use Iterator;
use Serializable;

class Options implements ArrayAccess, Iterator, Serializable
{

    private $position = 0;

    private array $values = [];

    private array $defaultValues = [];

    private string $prefix = '';

    private ?Options $parent = null;

    private bool $strip = false;

    function __construct($defaultValues = null)
    {
        if (isset($defaultValues)) {
            if ($defaultValues instanceof Options) {
                $this->defaultValues = $defaultValues->values;
            } elseif (is_array($defaultValues)) {
                $this->defaultValues = $defaultValues;
            } else {
                throw new Exception('Unsupported data type default values:' . get_class($defaultValues));
            }
        }
    }

    function __get($key)
    {
        // create key
        if ($this->strip) {
            $key = $this->prefix . $key;
        }

        // get value from parent
        if (isset($this->parent)) {
            return $this->parent->$key;
        }

        // get local value
        if (isset($this->values[$key])) {
            return $this->values[$key];
        }
        if (isset($this->defaultValues[$key])) {
            return $this->defaultValues[$key];
        }
        return null;
    }

    function __set($key, $value)
    {
        // create key
        if ($this->strip) {
            $key = $this->prefix . $key;
        }

        // set value to parent
        if (isset($this->parent)) {
            $this->parent->$key = $value;
            return;
        }

        // set local value
        $this->values[$key] = $value;
    }

    /**
     * Gets subset of options by prefix
     *
     * @param string $prefix
     *            A prefix key of all configs
     * @param boolean $strip
     *            To cut key or not
     * @return \Pluf\Options subset
     */
    public function startsWith(string $prefix, $strip = false)
    {
        $options = new Options();
        $options->parent = $this;
        $options->prefix = $prefix;
        $options->strip = $strip;
        return $options;
    }

    /**
     *
     * {@inheritdoc}
     * @see ArrayAccess::offsetSet()
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->values[] = $value;
        } else {
            $this->values[$offset] = $value;
        }
    }

    /**
     *
     * {@inheritdoc}
     * @see ArrayAccess::offsetExists()
     */
    public function offsetExists($offset)
    {
        return isset($this->values[$offset]);
    }

    /**
     *
     * {@inheritdoc}
     * @see ArrayAccess::offsetUnset()
     */
    public function offsetUnset($offset)
    {
        unset($this->values[$offset]);
    }

    /**
     *
     * {@inheritdoc}
     * @see ArrayAccess::offsetGet()
     */
    public function offsetGet($offset)
    {
        return isset($this->values[$offset]) ? $this->values[$offset] : null;
    }

    /**
     *
     * {@inheritdoc}
     * @see Iterator::rewind()
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     *
     * {@inheritdoc}
     * @see Iterator::current()
     */
    public function current()
    {
        return $this->values[$this->position];
    }

    /**
     *
     * {@inheritdoc}
     * @see Iterator::key()
     */
    public function key()
    {
        return $this->position;
    }

    /**
     *
     * {@inheritdoc}
     * @see Iterator::next()
     */
    public function next()
    {
        ++ $this->position;
    }

    /**
     *
     * {@inheritdoc}
     * @see Iterator::valid()
     */
    public function valid()
    {
        return isset($this->values[$this->position]);
    }

    /**
     *
     * {@inheritdoc}
     * @see Serializable::serialize()
     */
    public function serialize()
    {
        $data = [
            'values' => $this->values,
            'default' => $this->defaultValues
        ];
        return serialize($data);
    }

    /**
     *
     * {@inheritdoc}
     * @see Serializable::unserialize()
     */
    public function unserialize($data)
    {
        $data = unserialize($data);
        $this->defaultValues = $data['default'];
        $this->values = $data['values'];
    }
}

