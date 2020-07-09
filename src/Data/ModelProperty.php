<?php
namespace Pluf\Data;

use Pluf\Options;

class ModelProperty
{
    use \Pluf\DiContainerTrait;

    public ?bool $mapped = false;

    public $type = Schema::TEXT;

    public string $name = 'noname';

    public ?string $title = null;

    public ?string $description = null;

    // "unit" => null,
    // "defaultValue" => null,
    // "required" => false,
    // "visible" => false,
    // "priority" => 0,
    public bool $graphql_field = true;

    public ?string $graphql_name = null;

    // public array $validators" => ['NotNull', 'MaxSize:20', 'MinSize:2'],
    // public array $tags => [],
    public bool $editable = true;

    public bool $nullable = true;

    public bool $readable = true;

    public int $decimal_places = 8;

    public int $max_digits = 32;

    public ?int $size = 256;

    public bool $unique = false;

    public ?string $columne = null;

    /**
     * Relation properties
     *
     * These are used to define a relation property for a model
     * {
     */
    public ?string $joinProperty = null;

    /**
     * Defines a model which is related to the current one
     *
     * @var string
     */
    public ?string $inverseJoinModel = null;

    /**
     * Defines the property of the related model.
     *
     * NOTE: property must be defined, and the inverse of the property must be current one.
     *
     * @var string
     */
    public ?string $inverseJoinProperty = null;

    /**
     * }
     */

    /**
     * Relation DB field
     * {
     */
    public ?string $joinTable = null;

    public ?string $joinColumne = null;

    public ?string $inverseJoinColumne = null;

    /**
     * }
     */
    /**
     * Creates new instance of model property
     *
     * @param array|Options $options
     */
    public function __construct($options)
    {
        $this->setDefaults($options);
    }

    /**
     * Checks if the property is mapped one.
     *
     * @return bool true if the property is mapped.
     */
    public function isMapped(): bool
    {
        return isset($this->mapped) && $this->mapped;
    }

    /**
     * Check if the property is a relation
     *
     * @return bool true if the property is relation
     */
    public function isRelation(): bool
    {
        return in_array($this->type, [
            Schema::ONE_TO_MANY,
            Schema::MANY_TO_MANY,
            Schema::MANY_TO_ONE
        ]);
    }
}

