<?php
/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. (http://dpq.co.ir)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
namespace Pluf\Data;

use JsonSerializable;
use Pluf;

class Model implements JsonSerializable
{

    // function __construct($pk = null, $values = array()){}

    // //-----------------------------------------------------------
    // // Old API
    // //-----------------------------------------------------------
    // public function init(): void {}
    // public function getRelationKeysToModel($model, $type): array {}
    // public function getData(): array {}
    // public function setAssoc(Model $model, ?string $assocName = null) {}
    // public function delAssoc(Model $model, ?string $assocName = null) {}
    // public function batchAssoc($model_name, $ids) {}
    // public function getOne($p = array()): ?Model {}
    // public function getList($p = array()) : array{}
    // public function getCount($p = array()) {}
    // public function getRelated($model, $method = null, $p = array()) {}
    // public function update($where = '') {}
    // public function create($raw = false) {}
    // public function delete() {}
    // public function setFromFormData($cleaned_values) {}
    // public function isAnonymous() {}
    // public function getSchema() {}

    // public function getView(string $name): array {}
    // public function setView(string $name, array $view): void {}
    // public function hasView(?string $name = null): bool {}
    // public function getIndexes(): array {}

    /**
     * Tenant field
     *
     * This field is added to model in the multi-tenancy mode automaticlly.
     *
     * @var array
     */
    protected $tenant_field = array(
        'type' => Schema::FOREIGNKEY,
        'model' => '\\Pluf\\Pluf\\Tenant',
        'blank' => false,
        'relate_name' => 'tenant',
        'editable' => false,
        'readable' => false,
        'graphql_field' => false
    );

    public $_model = __CLASS__;

    /**
     * Store the attributes of the model.
     * To minimize pollution of the
     * property space, all the attributes are stored in this array.
     *
     * Description of the keys:
     * 'multitenant: Determines possibility of define the model in each tenant separately
     * 'mapped': Determines that the model is a mapped model of another model. A mapped model have not a separate table.
     * 'table': The table in which the model is stored.
     * 'model': The name of the model.
     * 'cols': The definition of the columns.
     * 'idx': The definition of the indexes.
     * 'views': The definition of the views.
     * 'verbose': The verbose name of the model.
     */
    public $_a = array(
        'multitenant' => true,
        'mapped' => false,
        'table' => 'model',
        'model' => '\Pluf\Data\Model',
        'cols' => array(),
        'idx' => array(),
        'views' => array()
    );

    /**
     * Storage of the data.
     *
     * The object data are stored in an associative array. Each key
     * corresponds to a column and stores a Pluf_DB_Field_* variable.
     */
    public $_data = array();

    /**
     * Storage cached data for methods_get
     */
    public $_cache = array();

    // We should use a global cache.

    /**
     * List of the foreign keys.
     *
     * Set by the init() method from the definition of the columns.
     */
    public $_fk = array();

    /**
     * Methods available, this array is dynamically populated by init
     * method.
     */
    public $_m = array(
        'list' => array(), // get_*_list methods
        'many' => array(), // many to many
        'get' => array(), // foreign keys
        'extra' => array()
    );

    // added by some fields
    function __construct($pk = null, $values = array())
    {
        // -->
        $this->_model = get_class($this);
        $this->_a['model'] = $this->_model;

        $this->_a['multitenant'] = true;
        $this->_init((int) $pk > 0);
        if ((int) $pk > 0) {
            $this->get($pk); // Should not have a side effect
        }
    }

    /**
     * Load and init the model
     */
    function _init()
    {
        // $engine = $this->getEngine();
        if (ModelUtils::loadFromCache($this)) {
            return;
        }

        // put in catch temprory
        $this->init();
        $this->_setupMultitenantFields();

        foreach ($this->_a['cols'] as $col => $description) {

            // $field = new $val['type']('', $col);
            // $col_lower = strtolower($col);

            // if ($description['type'] === Engine::FOREIGNKEY) {
            // $this->_m['get']['get_' . $col_lower] = array(
            // $description['model'],
            // $col
            // );
            // $this->_cache['fk'][$col] = Engine::FOREIGNKEY;
            // $this->_fk[$col] = Engine::FOREIGNKEY;
            // /*
            // * TODO: maso, 2018: this model will replace the old one in the
            // * next major version
            // */
            // if (array_key_exists('name', $description)) {
            // $this->_m['get']['get_' . $description['name']] = $this->_m['get']['get_' . $col_lower];
            // }
            // }

            // if ($description['type'] === Engine::MANY_TO_MANY) {
            // $this->_m['list']['get_' . $col_lower . '_list'] = $description['model'];
            // $this->_m['many'][$description['model']] = Engine::MANY_TO_MANY;
            // /*
            // * TODO: maso, 2018: this model will replace the old one in the
            // * next major version
            // */
            // if (array_key_exists('name', $description)) {
            // $this->_m['list']['get_' . $description['name'] . '_list'] = $description['model'];
            // }
            // }

            if (array_key_exists('defaultValue', $description)) {
                $this->_data[$col] = $description['defaultValue'];
            }
        }

        // $this->_setupAutomaticListMethods(Engine::FOREIGNKEY);
        // $this->_setupAutomaticListMethods(Engine::MANY_TO_MANY);

        ModelUtils::putModelToCache($this);
    }

    // /**
    // * Retrieve key relationships of a given model.
    // *
    // * @param string $model
    // * @param string $type
    // * Relation Engine::FOREIGNKEY or Engine::MANY_TO_MANY
    // * @return array Key relationships.
    // */
    // public function getRelationKeysToModel($model, $type)
    // {
    // $keys = array();
    // foreach ($this->_a['cols'] as $col => $description) {
    // if (isset($description['model']) && $model === $description['model']) {
    // // $field = new $val['type']();
    // if ($type === $description['type']) {
    // $keys[$col] = $description;
    // }
    // }
    // }
    // return $keys;
    // }

    // /**
    // * Get the foreign keys relating to a given model.
    // *
    // * @deprecated Use {@link self::getRelationKeysToModel()} instead.
    // * @param
    // * string Model
    // * @return array Foreign keys
    // */
    // function getForeignKeysToModel($model)
    // {
    // return $this->getRelationKeysToModel($model, Engine::FOREIGNKEY);
    // }

    /**
     * Get the raw data of the object.
     *
     * For the many to many relations, the value is an array of ids.
     *
     * @return array Associative array of the data.
     */
    function getData()
    {
        foreach ($this->_a['cols'] as $col => $description) {
            // $field = new $val['type']();
            if ($description['type'] == Schema::MANY_TO_MANY) {
                $this->_data[$col] = [];
                // XXX: maso, 2018: do not load many to many relation if is not required
                $method = 'get_' . strtolower($col) . '_list';
                foreach ($this->$method() as $item) {
                    $this->_data[$col][] = $item->id;
                }
            }
        }
        return $this->_data;
    }

    // /**
    // * Bulk association of models to the current one.
    // *
    // * @param
    // * string Model name
    // * @param
    // * array Ids of Model name
    // * @return bool Success
    // */
    // function batchAssoc($model_name, $ids)
    // {
    // $currents = $this->getRelated($model_name);
    // foreach ($currents as $cur) {
    // $this->delAssoc($cur);
    // }
    // foreach ($ids as $id) {
    // $m = new $model_name($id);
    // if ($m->id == $id) {
    // $this->setAssoc($m);
    // }
    // }
    // return true;
    // }

    // /**
    // * Get the table of the model.
    // *
    // * Avoid doing the concatenation of the prefix and the table
    // * manually.
    // *
    // * @deprecated
    // */
    // function getSqlTable()
    // {
    // return $this->getEngine()
    // ->getSchema()
    // ->getTableName($this);
    // }

    /**
     * Overloading of the get method.
     *
     * @param
     *            string Property to get
     */
    function __get($prop)
    {
        if (array_key_exists($prop, $this->_a['cols'])) {
            if (isset($this->_data[$prop])) {
                return $this->_data[$prop];
            }
            return null;
        }

        return $this->__call($prop, array());
    }

    /**
     * Overloading of the set method.
     *
     * @param
     *            string Property to set
     * @param
     *            mixed Value to set
     */
    function __set($prop, $val)
    {
        $md = ModelDescription::getInstance($this);
        $property = $md->$prop;
        if (isset($property)) {
            $this->_data[$prop] = $val;
            // Set reference attribute
            if ($property->type == Schema::MANY_TO_ONE) {
                if (isset($property->joinProperty)) {
                    $jp = $property->joinProperty;
                    $this->_data[$jp] = $val;
                }
            }
            return;
        }
        throw new Exception([
            'message' => 'Property {name} not found in model {model}.',
            'name' => $prop,
            'model' => get_class($this)
        ]);
    }

    /**
     * Overloading of the method call.
     *
     * @param
     *            string Method
     * @param
     *            array Arguments
     */
    function __call($method, $args)
    {
        $match = [];
        // Schema::MANY_TO_MANY
        // SCHEMA::ONE_TO_MANY
        if (preg_match('#^get_(?P<property>.+)_list$#', $method, $match) || //
        preg_match('#^get_(?P<property>.+)$#', $method, $match)) {
            $propertyName = $match['property'];
            $md = ModelDescription::getInstance($this);
            $property = $md->$propertyName;

            // create query from args
            if (isset($args[0])) {
                $queryValue = $args[0];
            } else {
                $queryValue = [];
            }
            if ($queryValue instanceof \Pluf\Db\Query) {
                $query = $queryValue;
            } else {
                $query = new Query($queryValue);
            }

            if (isset($property)) {
                switch ($property->type) {
                    case Schema::MANY_TO_ONE:
                        // FK Value
                        $fkvName = $property->joinProperty;
                        if (! isset($fkvName)) {
                            $fkvName = $property->name;
                        }
                        $fkValue = $this->$fkvName;

                        // FK
                        $fkModel = ModelDescription::getInstance($property->inverseJoinModel);
                        $fkName = $property->inverseJoinProperty;
                        if (! isset($fkName)) {
                            $fkName = 'id';
                        }
                        $fk = $fkModel->$fkName;

                        $query->addFilter([
                            $fk->name,
                            '=',
                            $fkValue
                        ]);
                        $repo = Pluf::getDataRepository([
                            'model' => $property->inverseJoinModel
                        ]);
                        return $repo->getOne($query);
                    case Schema::ONE_TO_MANY:
                        // FK Value
                        $fkvName = $property->joinProperty;
                        if (! isset($fkvName)) {
                            $fkvName = 'id';
                        }
                        $fkValue = $this->$fkvName;

                        // FK
                        $fkModel = ModelDescription::getInstance($property->inverseJoinModel);
                        $fkName = $property->inverseJoinProperty;
                        if (! isset($fkName)) {
                            $fkName = 'id';
                        }
                        $fk = $fkModel->$fkName;

                        $query->addFilter([
                            $fk->name,
                            '=',
                            $fkValue
                        ]);
                        $repo = Pluf::getDataRepository([
                            'model' => $property->inverseJoinModel
                        ]);
                        return $repo->get($query);
                    case Schema::MANY_TO_MANY:

                        $smd = ModelDescription::getInstance($this);
                        $tmd = ModelDescription::getInstance($property->inverseJoinModel);
                        $relation = $property;

                        // FK Value
                        $fkvName = $relation->joinProperty;
                        if (! isset($fkvName)) {
                            $fkvName = 'id';
                        }
                        $fkValue = $this->$fkvName;
                        $tmdFk = $relation->inverseJoinProperty;
                        if (! isset($tmdFk)) {
                            $tmdFk = 'id';
                        }

                        // Repository
                        $repo = Pluf::getDataRepository([
                            'model' => $property->inverseJoinModel
                        ]);
                        $schmea = $repo->getSchema();
                        $query->setView([
                            'filter' => [
                                [
                                    '__px_relation__.' . $schmea->getRelationSourceField($smd, $tmd, $relation),
                                    '=',
                                    $fkValue
                                ]
                            ],
                            'join' => [
                                [
                                    'joinTable' => $schmea->getRelationTable($smd, $tmd, $relation),
                                    'joinColumne' => $schmea->getFieldName($tmd, $tmd->$tmdFk),
                                    'inverseJoinColumne' => $schmea->getRelationTargetField($smd, $tmd, $relation, false),
                                    'alias' => '__px_relation__'
                                ]
                            ]
                        ]);

                        return $repo->get($query);
                    default:
                        throw new Exception([
                            'message' => 'the property {name} in model {model} is not relation',
                            'name' => $property->name,
                            'model' => $md->type
                        ]);
                }
            }
        }
        // no slousion found
        throw new \Pluf\Exception([
            'message' => 'Method "{method}" not available in model "{model}".',
            'method' => $method,
            'model' => get_class($this)
        ]);
    }

    /**
     * Get a given item.
     *
     * @param
     *            int Id of the item.
     * @return mixed Item or false if not found.
     * @deprecated use \Pluf\Model\Repository::getList
     */
    function get($id)
    {
        $model = Pluf::getDataRepository($this)->getById($id);
        $this->_data = $model->_data;
    }

    /**
     * Get one item.
     *
     * The parameters are the same as the ones of the getList method,
     * but, the return value is either:
     *
     * - The object
     * - null if no match
     * - Exception if the match results in more than one item.
     *
     * Usage:
     *
     * <pre>
     * $m = Pluf::factory(My_Model::class)->getOne(array('filter' => 'id=1'));
     * </pre>
     * <pre>
     * $m = Pluf::factory(My_Model::class)->getOne('id=1');
     * </pre>
     *
     * @param array|string $pFilter
     *            string or array given to getList
     * @see self::getList
     * @return \Pluf\Data\Model|null find model
     */
    public function getOne($p = array())
    {
        if (! is_array($p)) {
            $p = array(
                'filter' => $p
            );
        }
        $items = $this->getList($p);
        if (count($items) == 1) {
            return $items[0];
        }
        if (count($items) == 0) {
            return null;
        }
        throw new Exception([
            'message' => 'More than one matching item found.'
        ]);
    }

    /**
     * Gets list of model with the parameters
     *
     * The filter should be used only for simple filtering. If you want
     * a complex query, you should create a new view.
     * Both filter and order accept an array or a string in case of multiple
     * parameters:
     * Filter:
     * array('col1=toto', 'col2=titi') will be used in a AND query
     * or simply 'col1=toto'
     * Order:
     * array('col1 ASC', 'col2 DESC') or 'col1 ASC'
     *
     * This is modelled on the DB_Table pear module interface.
     *
     * keys:
     *
     * - 'view': The view to use
     * - 'filter': The where clause to use
     * - 'order': The ordering of the result set
     * - 'start': The number of skipped rows in the result set
     * - 'limit': The number of items to get in the result set
     * - 'count': Run a count query and not a select if set to true
     *
     * @param
     *            array Associative array with the possible following
     * @return \ArrayObject of items or through an exception if
     *         database failure
     */
    function getList($p = array())
    {
        $query = new Query($p);
        return Pluf::getDataRepository($this)->get($query);
    }

    /**
     * Get the number of items.
     *
     * @see getList() for definition of the keys
     *     
     * @param
     *            array with associative keys 'view' and 'filter'
     * @return int The number of items
     * @see \Pluf\Data\Repository#getCount
     */
    function getCount($p = array()): int
    {
        $p['count'] = true;
        $count = $this->getList($p);
        if (! isset($count)) {
            return 0;
        } else {
            return (int) $count;
        }
    }

    // /**
    // * Get a list of related items.
    // *
    // * See the getList() method for usage of the view and filters.
    // *
    // * @param
    // * string Class of the related items
    // * @param
    // * string Method call in a many to many related
    // * @param
    // * array Parameters, see getList() for the definition of
    // * the keys
    // * @return array Array of items
    // */
    // private function getRelated(string $modelClass, $relationName, $p = array())
    // {
    // if ($this->isAnonymous()) {
    // return new ArrayObject();
    // }

    // $default = array(
    // 'view' => null,
    // 'filter' => null,
    // 'order' => null,
    // 'start' => null,
    // 'nb' => null,
    // 'count' => false
    // );
    // $query = new Query(array_merge($default, $p));

    // // $toMd = ModelDescription::getInstance($modelClass);
    // // $fromMd = ModelDescription::getInstance($this);

    // $rep = Pluf::getDataRepository([
    // 'owner' => $this,
    // 'target' => $modelClass,
    // 'name' => $relationName
    // ]);

    // return $repo->get([]);

    // // $property = $fromMd->$relationName;
    // // switch ($property->type) {
    // // case Schema::MANY_TO_ONE:

    // // case Schema::MANY_TO_MANY:
    // // case Schema::ONE_TO_MANY:
    // // $view = '__px__view_' . $relationName;
    // // $query->view
    // // default:
    // // throw new Exception([
    // // 'message' => 'Property {relation} is not a relation field',
    // // 'relation' => $relationName,
    // // 'model' => get_class($this)
    // // ]);
    // // }

    // // return Pluf::getDataRepository($modelClass)
    // // ->getRelations(, $relationName, $query);
    // }

    /**
     * Update the model into the database.
     *
     * If no where clause is provided, the index definition is used to
     * find the sequence. These are used to limit the update
     * to the current model.
     *
     * @param
     *            string Where clause to update specific items. ('')
     * @return bool Success
     */
    function update($where = null)
    {
        if (isset($where)) {
            throw new Exception([
                'message' => 'Bulky update is not supported in old Pluf Model.'
            ]);
        }

        $this->preSave();
        Pluf::getDataRepository($this)->update($this);
        $this->postSave();
        return true;
    }

    /**
     * Create the model into the database.
     *
     * If raw insert is requested, the preSave/postSave methods are
     * not called and the current id of the object is directly
     * used. This is particularily used when doing backup/restore of
     * data.
     *
     * @param
     *            bool Raw insert (false)
     * @return bool Success
     */
    function create($raw = false)
    {
        if (! $raw) {
            $this->preSave(true);
        }

        Pluf::getDataRepository([
            'type' => 'model',
            'model' => get_class($this)
        ])->create($this);

        if (! $raw) {
            $this->postSave(true);
        }

        return true;
    }

    /**
     * Delete the current model from the database.
     *
     * If another model link to the current model through a foreign
     * key, the DB is responsible to mange relations.
     */
    function delete()
    {
        if ($this->isAnonymous()) {
            return false;
        }
        $this->preDelete();
        Pluf::getDataRepository($this)->delete($this);
        $this->_reset();
        return true;
    }

    /**
     * Set the association of a model to another in many to many.
     *
     * @param
     *            object Object to associate to the current object
     */
    function setAssoc(\Pluf\Data\Model $model, ?string $assocName = null)
    {
        if (! isset($assocName)) {
            $property = ModelUtils::getRelationProperty(ModelDescription::getInstance($this), ModelDescription::getInstance($model));
            $assocName = $property->name;
        }
        Pluf::getDataRepository([
            'relation' => $assocName,
            'source' => get_class($this),
            'target' => get_class($model)
        ])->create($this, $model);
        return true;
    }

    /**
     * Set the association of a model to another in many to many.
     *
     * @param
     *            object Object to associate to the current object
     */
    function delAssoc(\Pluf\Data\Model $model, ?string $assocName = null)
    {
        if (! isset($assocName)) {
            $property = Pluf::getDataSchema()->getRelationProperty($this, $model);
            $assocName = $property->name;
        }
        Pluf::getDataRepository([
            'relation' => $assocName,
            'source' => get_class($this),
            'target' => get_class($model)
        ])->delete($this, $model);
        return true;
    }

    /**
     * Reset the fields to default values.
     */
    private function _reset()
    {
        foreach ($this->_a['cols'] as $col => $val) {
            if (isset($val['default'])) {
                $this->_data[$col] = $val['default'];
            } elseif (isset($val['is_null'])) {
                $this->_data[$col] = null;
            } else {
                $this->_data[$col] = '';
            }
        }
    }

    /**
     * Represents the model in auto generated lists.
     *
     * You need to overwrite this method to have a nice display of
     * your objects in the select boxes, logs.
     *
     * @return string reperesentation of the current object
     */
    function __toString()
    {
        return $this->_a['model'] . '(' . $this->_data['id'] . ')';
    }

    /**
     * مقادیر مدل را بر اساس یک فرم تعیین می‌کند
     *
     * این مقادیر به صورت یک آرایه به عنوان ورودی دریافت شده و بر اساس
     * آن داده‌های مورد نیاز مدل تعیین می‌شود.
     */
    function setFromFormData($cleaned_values)
    {
        foreach ($cleaned_values as $key => $val) {
            $this->_data[$key] = $val;
        }
    }

    /**
     * بررسی می‌کند که آیا مدل داده‌ای وجود دارد یا نه
     *
     * در صورتی که مدل داده‌ای ذخیره نشده باشد به عنوان داده بی نام و نشان در
     * نظر
     * گرفته می‌شود. در مورد کاربران این تابع کاربرد فراوان دارد.
     *
     * @return bool True if the user is anonymous.
     */
    function isAnonymous()
    {
        return ($this->id == '' || 0 === (int) $this->id);
    }

    /**
     * مدل داده‌ای را تعیین می‌کند
     *
     * هر مدل داده‌ای یک نام دارد.
     *
     * این فراخوانی نام مدل داده‌ای را تعیین می‌کند که معادل با نام کلاس است.
     *
     * @return string
     */
    public function getClass()
    {
        return $this->_a['model'];
    }

    /**
     * شناسه را تعیین می‌کند.
     *
     * @return integer id
     */
    public function getId()
    {
        return $this->id;
    }

    // /**
    // * (non-PHPdoc)
    // *
    // * @see JsonSerializable::jsonSerialize()
    // */
    // public function jsonSerialize()
    // {
    // $coded = array();
    // foreach ($this->_data as $col => $val) {
    // /*
    // * خصوصیت‌هایی که قابل خواندن نیستن سریال نخواهند شد
    // */
    // if (array_key_exists($col, $this->_a['cols']) && array_key_exists('readable', $this->_a['cols'][$col]) && ! $this->_a['cols'][$col]['readable'])
    // continue;
    // /*
    // * If parameter ins null, zero, empty, ... we will not encode
    // */
    // if ($val)
    // $coded[$col] = $val;
    // }
    // return $coded;
    // }
    public function getName()
    {
        return array_key_exists('verbose', $this->_a) ? $this->_a['verbose'] : $this->getClass();
    }

    // /**
    // * Gets engine where this model is managed
    // */
    // public function getRepository(): ?Repository
    // {
    // return Pluf::getDataRepository($this);
    // }

    // /**
    // *
    // * @deprecated
    // * @param string $name
    // * @return array
    // */
    // public function getView(string $name): array
    // {
    // $md = ModelDescription::getInstance($this);
    // return $md->getView($name);
    // }

    // /**
    // * Set a view.
    // *
    // * @deprecated
    // * @param string $name
    // * Name of the view.
    // * @param array $view
    // * Definition of the view.
    // */
    // public function setView(string $name, array $view): void
    // {
    // $md = ModelDescription::getInstance($this);
    // $md->setView($name, $view);
    // }

    // /**
    // *
    // * @deprecated
    // * @param string $name
    // * @return bool
    // */
    // public function hasView(?string $name = null): bool
    // {
    // $md = ModelDescription::getInstance($this);
    // return $md->hasView($name);
    // }

    /**
     * Loads and return views
     */
    public function loadViews(): array
    {
        return [];
    }

    public function getIndexes(): array
    {
        $indexes = $this->loadIndexes();
        if (Pluf::f('multitenant', false) && $this->_a['multitenant']) {
            foreach ($indexes as $col => $idx) {
                $indexes[$col]['col'] = 'tenant,' . $idx['col'];
            }
        }
        return $indexes;
    }

    /**
     * Load indexes of the model
     *
     * @return array
     */
    public function loadIndexes(): array
    {
        if (isset($this->_a['idx'])) {
            return $this->_a['idx'];
        }
        return [];
    }

    // /**
    // * Generate the SQL select from the columns
    // */
    // function getSelect()
    // {
    // if (isset($this->_cache['getSelect'])) {
    // return $this->_cache['getSelect'];
    // }
    // $schema = $this->getEngine()->getSchema();
    // $select = array();
    // $table = $schema->getTableName($this);
    // foreach ($this->_a['cols'] as $col => $val) {
    // if ($val['type'] != Engine::MANY_TO_MANY) {
    // $select[] = $table . '.' . $schema->qn($col) . ' AS ' . $schema->qn($col);
    // }
    // }
    // $this->_cache['getSelect'] = implode(', ', $select);
    // return $this->_cache['getSelect'];
    // }

    // /**
    // * Get models affected by delete.
    // *
    // * @return array Models deleted if deleting current model.
    // */
    // function getDeleteSideEffect()
    // {
    // $affected = array();
    // foreach ($this->_m['list'] as $method => $details) {
    // if (is_array($details)) {
    // // foreignkey
    // $related = $this->$method();
    // $affected = array_merge($affected, (array) $related);
    // foreach ($related as $rel) {
    // if ($details[0] == $this->_a['model'] and $rel->id == $this->_data['id']) {
    // continue; // $rel == $this
    // }
    // $affected = array_merge($affected, (array) $rel->getDeleteSideEffect());
    // }
    // }
    // }
    // return Pluf_Model_RemoveDuplicates($affected);
    // }

    // public function getSchema()
    // {
    // $mainInfo = array(
    // "type" => $this->getclass(),
    // "unit" => null,
    // "name" => $this->getname(),
    // "title" => $this->getname(),
    // "description" => null,
    // "defaultvalue" => null,
    // "required" => false,
    // "visible" => false,
    // "editable" => false,
    // "priority" => 0,
    // "validators" => [],
    // "tags" => [],
    // "children" => []
    // );
    // foreach ($this->_a['cols'] as $name => $field) {
    // $fieldInfo = $this->getFieldInfo($name, $field);
    // array_push($mainInfo['children'], $fieldInfo);
    // }
    // return $mainInfo;
    // }
    //
    // private function getFieldInfo($name, $field)
    // {
    // return array(
    // "type" => $field['type'],
    // "unit" => null,
    // "name" => $name,
    // "title" => $name,
    // "description" => null,
    // "defaultValue" => array_key_exists('default', $field) ? $field['default'] : null,
    // "required" => array_key_exists('is_null', $field) ? $field['is_null'] : true,
    // "visible" => array_key_exists('readable', $field) ? $field['readable'] : true,
    // "editable" => array_key_exists('editable', $field) ? $field['editable'] : false,
    // "priority" => 0,
    // "validators" => [],
    // "tags" => [],
    // "children" => []
    // );
    // }

    // /**
    // * Prepare the value to be put in the DB.
    // *
    // * @param
    // * mixed Value.
    // * @param
    // * string Column name.
    // * @return string SQL ready string.
    // */
    // function _toDb($val, $col)
    // {
    // return $this->getEngine()->toDb($val, $this->_a['cols'][$col]['type']);
    // }

    // /**
    // * Get the value from the DB.
    // *
    // * Create DB field and returns. The field type is used as the output
    // * value type.
    // *
    // * @param
    // * mixed Value.
    // * @param
    // * string Column name.
    // * @return mixed Value.
    // */
    // function _fromDb($val, $col)
    // {
    // return $this->getEngine()->_fromDb($val, $this->_a['cols'][$col]['type']);
    // }

    // /**
    // * Display value.
    // *
    // * When you have a list of choices for a field and you want to get
    // * the display value of the current stored value.
    // *
    // * @param
    // * string Field to display the value.
    // * @return mixed Display value, if not available default to the value.
    // */
    // function displayVal($col)
    // {
    // if (! isset($this->_a['cols'][$col]['choices'])) {
    // return $this->_data[$col]; // will on purposed failed if not set
    // }
    // $val = array_search($this->_data[$col], $this->_a['cols'][$col]['choices']);
    // if ($val !== false) {
    // return $val;
    // }
    // return $this->_data[$col];
    // }

    // ------------------------------------------------------------------------
    // Function and automated part
    // ------------------------------------------------------------------------
    // /**
    // * متدهای اتوماتیک را برای مدل ورودی ایجاد می‌کند.
    // *
    // * Adds the get_xx_list method when the methods of the model
    // * contains custom names.
    // *
    // * @param string $type
    // * Relation type: Engine::FORINKEY or ENGINE::MANY_TO_MANY
    // */
    // protected function _setupAutomaticListMethods($type)
    // {
    // $current_model = ModelUtils::getModelCacheKey($this);
    // $relations = ModelUtils::getRelatedModels($this, $type);

    // foreach ($relations as $related) {
    // if ($related != $current_model) {
    // $model = new $related();
    // } else {
    // $model = clone $this;
    // }
    // $fkeys = $model->getRelationKeysToModel($current_model, $type);
    // foreach ($fkeys as $fkey => $val) {
    // $mname = (isset($val['relate_name'])) ? $val['relate_name'] : $related;
    // $mname = 'get_' . strtolower($mname) . '_list';
    // if (Engine::FOREIGNKEY === $type) {
    // $this->_m['list'][$mname] = array(
    // $related,
    // $fkey
    // );
    // } else {
    // $this->_m['list'][$mname] = $related;
    // $this->_m['many'][$related] = $type;
    // }
    // }
    // }
    // }

    /**
     * Add tenant required fields
     *
     * Adds extra fields if multi-tenant is enabled
     */
    protected function _setupMultitenantFields()
    {
        if (Pluf::f('multitenant', false) && $this->_a['multitenant']) {
            $this->_a['cols']['tenant'] = $this->tenant_field;
        }
    }

    // ------------------------------------------------------------------------
    // TO override
    // ------------------------------------------------------------------------

    /**
     * Hook run just after loading a model from the database.
     *
     * Just overwrite it into your model to perform custom actions.
     */
    function restore()
    {}

    /**
     * دستگیره‌ای که درست قبل از ذخیره شدن در پایگاه داده اجرا می‌شود.
     *
     * در صورتی که نیاز به انجام پردازش‌هایی قبل از ذخیره شدن مدل داده‌ای دارید،
     * این فراخوانی
     * را بازنویسی کنید.
     *
     * @param
     *            bool Create.
     */
    function preSave($create = false)
    {
        // TODO: maso, 1395: بررسی داده‌های پیش فرض و به روز رسانی آنها
        //
        // برخی داده‌ها در تمام مدلهای داده‌ای به صورت تکراری استفاده می‌شود.
        // بهتر است که
        // وجود این داده‌ها بررسی شود و در صورت وجود همین جا به روز رسانی انجام
        // شود.
        //
        // - creation_dtime
        // - modif_dtime
    }

    /**
     * فراخوانی پس از ذخیره شدن
     *
     * @param string $create
     */
    function postSave($create = false)
    {}

    /**
     * Hook run just before deleting a model from the database.
     *
     * Just overwrite it into your model to perform custom actions.
     */
    function preDelete()
    {}

    /**
     * ساختار داده‌ای را ایجاد می‌کند.
     *
     * این فراخوانی تمام ساختارهای داده‌ای اصلی را ایجاد می‌کند. تمام زیر
     * کلاس‌ها
     * باید این کلاس را پیاده سازی کنند و ساختارهای داده‌ای خود را ایجاد کنند.
     */
    function init()
    {
        // Define it yourself.
    }

    /**
     * Traditional JSON Encoding
     *
     * In Pluf V5 supports JsonSerializable for each model, This is a new implementation
     * to support old Data Model.
     *
     * @see JsonSerializable::jsonSerialize()
     */
    public function jsonSerialize()
    {
        return ModelEncoder::getInstance(ModelEncoder::JSON)-> //
        setProperties(Pluf::getConfigurationPrifix('data_', true))
            ->setModel($this)
            ->encode($this);
    }
}

