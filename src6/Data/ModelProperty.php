<?php
namespace Pluf\Data;

use Pluf\Options;

class ModelProperty
{
    use \Pluf\DiContainerTrait;

//     "unit" => null,
//     "defaultValue" => null,
//     "required" => false,
//     "visible" => false,
//     "priority" => 0,
//     "validators" => [],
//     "tags" => [],
    
    public string $name = 'noname';

    public $type = Schema::TEXT;

    public ?string $title = null;

    public ?string $description = null;

    public bool $editable = false;

    public bool $nullable = true;

    public bool $readable = false;

    public int $decimal_places = 8;

    public int $max_digits = 32;

    public ?string $model = null;

    public ?int $size = 256;

    public bool $unique = false;

    /**
     * Creates new instance of model property
     *
     * @param array|Options $options
     */
    public function __construct($options)
    {
        $this->setDefaults($options);
    }
}

