<?php
namespace Pluf\Data;

use ArrayObject;
use Pluf_Model;

class ModelDescription extends ArrayObject
{

    use \Pluf\DiContainerTrait;

    public bool $mapped = false;

    public bool $multitinant = true;

    public ?string $table = null;

    public ?string $model = null;

    public ?string $type = null;

    public ?ModelProperty $identifier = null;

    public array $views = [];

    public function __construct($properties = [])
    {
        parent::__construct($properties, ArrayObject::STD_PROP_LIST | ArrayObject::ARRAY_AS_PROPS);
    }

    /**
     * Checks if this is a mapped model
     *
     * A mapped model uses others data and defines a new model type.
     *
     * @return bool true if the model is mapped otherwize false
     */
    public function isMapped(): bool
    {
        return $this->mapped;
    }

    /**
     * Creates new instance of the model
     *
     * @return mixed new created model
     */
    public function newInstance()
    {
        return new $this->type();
    }

    private function loadFromOldModel(Pluf_Model $model)
    {
        // load properties
        foreach ($model->_a['cols'] as $col => $description) {
            if (! array_key_exists('name', $description)) {
                $description['name'] = $col;
            }
            $this->$col = new ModelProperty($description);
        }

        // load descriptions
        $this->setDefaults($model->_a);
        $this->model = get_class($model);
        $this->type = get_class($model);
        $this->views = $model->loadViews();

        // Set identifier
        $identifier = $this->id;
        $this->identifier = $identifier;
    }

    public static function getInstance($model): ModelDescription
    {
        // XXX: maso, 2020: use pluf cache to improve performance
        if (is_string($model)) {
            $model = new $model();
        }
        /*
         * Support old
         */
        if (is_a($model, '\Pluf_Model', true)) {
            $modelDescription = new ModelDescription();
            if (is_string($model)) {
                $model = new $model();
            }
            $modelDescription->loadFromOldModel($model);
        } else {
            // XXX: maso, 2020: create model description based on generic object
            throw new Exception('Generic PHP Object is not supported');
        }

        return $modelDescription;
    }

    /**
     * Checks if the model is anonymous or not
     *
     * @param ModelDescription $md
     * @param mixed $model
     * @return bool
     */
    public function isAnonymous($model): bool
    {
        if ($model instanceof \Pluf_Model) {
            return $model->isAnonymous();
        }

        $idProp = $this->getIdentifier();
        $id = $model->$idProp;
        return isset($id);
    }

    /**
     * Gets identifier property
     *
     * @return ModelProperty identifier
     */
    public function getIdentifier(): ModelProperty
    {
        return $this->identifier;
    }

    /**
     * Checks if the view with $name defines
     *
     * @param string $name
     * @return bool
     */
    public function hasView(string $name): bool
    {
        return array_key_exists($name, $this->views);
    }

    /**
     * Gets named view from the model
     *
     * @param string $name
     * @return array
     */
    public function getView(string $name): array
    {
        if (! $this->hasView($name)) {
            throw new Exception([
                'message' => 'View [name] does not exist in [model].',
                'model' => get_class($this),
                'name' => $name
            ]);
        }
        return $this->views[$name];
    }

    /**
     * Adds new view to the model description
     *
     * @param string $name
     *            Name of the view
     * @param array $view
     *            Definition of the view
     */
    public function setView(string $name, array $view = [])
    {
        $this->views[$name] = $view;
        // XXX: maso, 2020: update cache
    }
}

