<?php
namespace Pluf;

class Options
{

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

    public function startsWith($pfx, $strip = false)
    {
        $options = new Options();
        $options->parent = $this;
        $options->prefix = $pfx;
        $options->strip = $strip;
        return $options;
    }
}

