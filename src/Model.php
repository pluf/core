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
namespace Pluf;

use JsonSerializable;
use ArrayObject;

/**
 * Sort of Active Record Class
 *
 * @author maso<mostafa.barmshory@dpq.co.ir>
 *         @date 1394 روش کدگزاری JSON به کلاس اضافه شده است تا به سادگی بتوان
 *         به عنوان نتیجه از یک مدل
 *         استفاده شود.
 */
abstract class Model implements JsonSerializable
{

    /**
     * Tenant field
     *
     * This field is added to model in the multi-tenancy mode automaticlly.
     *
     * @var array
     */
    protected $tenant_field = array(
        'type' => '\Pluf\DB\Field\Foreignkey',
        'model' => 'Pluf_Tenant',
        'blank' => false,
        'relate_name' => 'tenant',
        'editable' => false,
        'readable' => false,
        'graphql_field' => false
    );

    /**
     * Stores the name of the mapped table
     *
     * @var string
     */
    public ?string $tableName = null;

    /**
     * Is model multi tenant based
     *
     * @var bool
     */
    public bool $multitinant = true;

    // set it to your model name

    /**
     * Database connection.
     */
    public $_con = null;

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
        'model' => 'Model',
        'cols' => array(),
        'idx' => array(),
        'views' => array()
    );

    /**
     * Storage of the data.
     *
     * The object data are stored in an associative array. Each key
     * corresponds to a column and stores a \\Pluf\\DB\\Field\\* variable.
     */
    protected $_data = array();

    /**
     * Storage cached data for methods_get
     */
    protected $_cache = array();

    // We should use a global cache.

    /**
     * List of the foreign keys.
     *
     * Set by the init() method from the definition of the columns.
     */
    protected $_fk = array();

    /**
     * Methods available, this array is dynamically populated by init
     * method.
     */
    protected $_m = array(
        'list' => array(), // get_*_list methods
        'many' => array(), // many to many
        'get' => array(), // foreign keys
        'extra' => array()
    );

    // added by some fields
    function __construct(int $pk = 0, $values = array())
    {
        $reflectionObject = new \ReflectionObject($this);
        $this->class = $reflectionObject;

        // Set default table name
        $this->tableName = ModelUtils::skipeName($this->getClass()->getName());

        $this->_init($pk > 0);
        if ((int) $pk > 0) {
            $this->get($pk); // Should not have a side effect
        }

        // TODO: maso, 2020: use values
    }

    /**
     * Data initialization by sub classes
     *
     * این فراخوانی تمام ساختارهای داده‌ای اصلی را ایجاد می‌کند. تمام زیر
     * کلاس‌ها
     * باید این کلاس را پیاده سازی کنند و ساختارهای داده‌ای خود را ایجاد کنند.
     */
    protected abstract function init();

    /**
     * Load and init the model
     */
    private function _init()
    {
        // 1- Check if is cached
        $this->_getConnection();
        $modelCache = ModelUtils::getModelCache($this->getClass());
        if (isset($modelCache)) {
            $this->tableName = $modelCache['tableName'];
            $this->_cache = $modelCache['cache'];
            $this->_m = $modelCache['m'];
            $this->_a = $modelCache['a'];
            $this->_fk = $modelCache['fk'];
            $this->_data = $modelCache['data'];
            return;
        }

        // 2- run subclass initialization
        $this->init();
        if (array_key_exists('table', $this->_a)) {
            $this->tableName = $this->_a['table'];
        }

        // 3- setup the model
        $this->setupMultitenantFields();
        $this->setupFields();
        $this->setupForeignkeyMethods();
        $this->setupManytoManyMethods();

        // 4- Save the model to cache
        ModelUtils::putModelCache($this->getClass(), array(
            'tableName' => $this->tableName,
            'cache' => $this->_cache,
            'm' => $this->_m,
            'a' => $this->_a,
            'fk' => $this->_fk,
            'data' => $this->_data
        ));
    }

    private function setupForeignkeyMethods()
    {
        $this->setupAutomaticListMethods('foreignkey');
    }

    private function setupManytoManyMethods()
    {
        $this->setupAutomaticListMethods('manytomany');
    }

    private function setupFields()
    {
        foreach ($this->_a['cols'] as $col => $val) {
            $field = new $val['type']('', $col);
            $col_lower = strtolower($col);

            $type = 'foreignkey';
            if ($type === $field->type) {
                $this->_m['get']['get_' . $col_lower] = array(
                    $val['model'],
                    $col
                );
                $this->_cache['fk'][$col] = $type;
                $this->_fk[$col] = $type;
                /*
                 * TODO: maso, 2018: this model will replace the old one in the
                 * next major version
                 */
                if (array_key_exists('name', $val)) {
                    $this->_m['get']['get_' . $val['name']] = $this->_m['get']['get_' . $col_lower];
                }
            }

            $type = 'manytomany';
            if ($type === $field->type) {
                $this->_m['list']['get_' . $col_lower . '_list'] = $val['model'];
                $this->_m['many'][$val['model']] = $type;
                /*
                 * TODO: maso, 2018: this model will replace the old one in the
                 * next major version
                 */
                if (array_key_exists('name', $val)) {
                    $this->_m['list']['get_' . $val['name'] . '_list'] = $val['model'];
                }
            }

            foreach ($field->methods as $method) {
                $this->_m['extra'][$method[0]] = array(
                    $col_lower,
                    $method[1]
                );
            }

            if (array_key_exists('default', $val)) {
                $this->_data[$col] = $val['default'];
            } else {
                // TODO: hadi 1398-09-27: I think we should set an appropriate default value for each type (ex 0 for numerical types and null for nullable fields).
                $this->_data[$col] = '';
            }
        }
    }

    /**
     * Retrieve key relationships of a given model.
     *
     * @param string $model
     * @param string $type
     *            Relation type: 'foreignkey' or 'manytomany'.
     * @return array Key relationships.
     */
    public function getRelationKeysToModel($model, $type)
    {
        $keys = array();
        foreach ($this->_a['cols'] as $col => $val) {
            if (isset($val['model']) && $model === $val['model']) {
                $field = new $val['type']();
                if ($type === $field->type) {
                    $keys[$col] = $val;
                }
            }
        }

        return $keys;
    }

    /**
     * Get the foreign keys relating to a given model.
     *
     * @deprecated Use {@link self::getRelationKeysToModel()} instead.
     * @param
     *            string Model
     * @return array Foreign keys
     */
    function getForeignKeysToModel($model)
    {
        return $this->getRelationKeysToModel($model, 'foreignkey');
    }

    /**
     * Get the raw data of the object.
     *
     * For the many to many relations, the value is an array of ids.
     *
     * @return array Associative array of the data.
     */
    function getData()
    {
        foreach ($this->_a['cols'] as $col => $val) {
            $field = new $val['type']();
            if ($field->type == 'manytomany') {
                $this->_data[$col] = array();
                // XXX: maso, 2018: do not load many to many relation if is not required
                $method = 'get_' . strtolower($col) . '_list';
                foreach ($this->$method() as $item) {
                    $this->_data[$col][] = $item->id;
                }
            }
        }
        return $this->_data;
    }

    /**
     * Set the association of a model to another in many to many.
     *
     * @param
     *            object Object to associate to the current object
     */
    function setAssoc($model)
    {
        $req = 'INSERT INTO ' . ModelUtils::getAssocTable($this, $model);
        $req .= '(' . ModelUtils::getAssocField($this) . ', ' . ModelUtils::getAssocField($model) . ') VALUES ' . "\n";
        $req .= '(' . $this->_toDb($this->_data['id'], 'id') . ', ';
        $req .= $this->_toDb($model->id, 'id') . ')';
        $this->_con->execute($req);
        return true;
    }

    /**
     * Set the association of a model to another in many to many.
     *
     * @param
     *            object Object to associate to the current object
     */
    function delAssoc(Model $model)
    {
        $table = ModelUtils::getAssocTable($this, $model);

        $first = ModelUtils::getAssocField(this);
        $second = ModelUtils::getAssocField($model);

        $req = 'DELETE FROM ' . $table . ' WHERE ';
        $req .= $first . ' = ' . $this->_toDb($this->getId(), 'id');
        $req .= ' AND ' . $second . ' = ' . $this->_toDb($model->getId(), 'id');

        $this->_con->execute($req);
        return true;
    }

    /**
     * Bulk association of models to the current one.
     *
     * @param
     *            string Model name
     * @param
     *            array Ids of Model name
     * @return bool Success
     */
    function batchAssoc($model_name, $ids)
    {
        $currents = $this->getRelated($model_name);
        foreach ($currents as $cur) {
            $this->delAssoc($cur);
        }
        foreach ($ids as $id) {
            $m = new $model_name($id);
            if ($m->id == $id) {
                $this->setAssoc($m);
            }
        }
        return true;
    }

    /**
     * Get a database connection.
     */
    function _getConnection()
    {
        static $con = null;
        if ($this->_con !== null) {
            return $this->_con;
        }
        if ($con !== null) {
            $this->_con = $con;
            return $this->_con;
        }
        $this->_con = Bootstrap::db($this);
        $con = $this->_con;
        return $this->_con;
    }

    /**
     * Get a database connection.
     */
    function getDbConnection()
    {
        return $this->_getConnection();
    }

    /**
     * Overloading of the get method.
     *
     * @param
     *            string Property to get
     */
    function __get($prop)
    {
        return (array_key_exists($prop, $this->_data)) ? $this->_data[$prop] : $this->__call($prop, array());
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
        if (null !== $val and isset($this->_cache['fk'][$prop])) {
            $this->_data[$prop] = $val->id;
            unset($this->_cache['get_' . $prop]);
        } else {
            $this->_data[$prop] = $val;
        }
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
        // The foreign keys of the current object.
        if (isset($this->_m['get'][$method])) {
            if (isset($this->_cache[$method])) {
                return $this->_cache[$method];
            } else {
                $className = $this->_m['get'][$method][0];
                $this->_cache[$method] = new $className($this->_data[$this->_m['get'][$method][1]]);
                if ($this->_cache[$method]->id == '') {
                    $this->_cache[$method] = null;
                }
                return $this->_cache[$method];
            }
        }
        // Many to many or foreign keys on the other objects.
        if (isset($this->_m['list'][$method])) {
            if (is_array($this->_m['list'][$method])) {
                $model = $this->_m['list'][$method][0];
            } else {
                $model = $this->_m['list'][$method];
            }
            $args = array_merge(array(
                $model,
                $method
            ), $args);
            return call_user_func_array(array(
                $this,
                'getRelated'
            ), $args);
        }

        // NOTE: extra field method is not supported anymore
        // // Extra methods added by fields
        // if (isset($this->_m['extra'][$method])) {
        // $args = array_merge(array(
        // $this->_m['extra'][$method][0],
        // $method,
        // $this
        // ), $args);
        // Bootstrap::loadFunction($this->_m['extra'][$method][1]);
        // return call_user_func_array($this->_m['extra'][$method][1], $args);
        // }
        throw new Exception(sprintf('Method "%s" not available in model "%s".', $method, $this->getClass()->getName()));
    }

    /**
     * Get a given item.
     *
     * @param
     *            int Id of the item.
     * @return mixed Item or false if not found.
     */
    function get($id)
    {
        $req = 'SELECT * FROM ' . ModelUtils::getTable($this) . ' WHERE ';
        if (Bootstrap::f('tenant_multi_enable', false) && $this->_a['multitenant']) {
            $sql = new SQL('tenant=%s AND id=%s', array(
                Tenant::current()->id,
                $this->_toDb($id, 'id')
            ));
        } else {
            $sql = new SQL('id=%s', array(
                $this->_toDb($id, 'id')
            ));
        }
        $req .= $sql->gen();
        if (false === ($rs = $this->_con->select($req))) {
            throw new Exception($this->_con->getError());
        }
        if (count($rs) == 0) {
            return false;
        }
        foreach ($this->_a['cols'] as $col => $val) {
            $field = new $val['type']();
            if ($field->type != 'manytomany' && array_key_exists($col, $rs[0])) {
                $this->_data[$col] = $this->_fromDb($rs[0][$col], $col);
            }
        }
        $this->restore();
        return $this;
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
     * $model = new My_Model();
     * $m = $model->getOne(array('filter' => 'id=1'));
     * </pre>
     * <pre>
     * $m = $model->getOne('id=1');
     * </pre>
     *
     * @param
     *            array|string Filter string or array given to getList
     * @see self::getList
     * @return Model|null find model
     */
    public function getOne($p = array())
    {
        if (! is_array($p)) {
            $p = array(
                'filter' => $p
            );
        }
        $items = $this->getList($p);
        if ($items->count() == 1) {
            return $items[0];
        }
        if ($items->count() == 0) {
            return null;
        }
        throw new Exception('Error: More than one matching item found.');
    }

    /**
     * یک فهرست از موجودیت‌ها را تعیین می‌کند
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
     * @param
     *            array Associative array with the possible following
     *            keys:
     *            'view': The view to use
     *            'filter': The where clause to use
     *            'order': The ordering of the result set
     *            'start': The number of skipped rows in the result set
     *            'nb': The number of items to get in the result set
     *            'count': Run a count query and not a select if set to true
     * @return ArrayObject of items or through an exception if
     *         database failure
     */
    function getList($p = array())
    {
        $default = array(
            'view' => null,
            'group' => null,
            'filter' => null,
            'order' => null,
            'start' => null,
            'select' => null,
            'nb' => null,
            'count' => false
        );
        $p = array_merge($default, $p);
        if (! is_null($p['view']) && ! isset($this->_a['views'][$p['view']])) {
            throw new Exception(sprintf('The view "%s" is not defined.', $p['view']));
        }
        $query = array(
            'select' => $this->getSelect(),
            'from' => $this->_a['table'],
            'join' => '',
            'where' => '',
            'group' => '',
            'having' => '',
            'order' => '',
            'limit' => '',
            'props' => array()
        );
        if (! is_null($p['view'])) {
            $query = array_merge($query, $this->_a['views'][$p['view']]);
        }
        if (! is_null($p['select'])) {
            $query['select'] = $p['select'];
        }
        if (! is_null($p['group'])) {
            $query['group'] = $p['group'];
        }
        if (! is_null($p['filter'])) {
            if (is_array($p['filter'])) {
                $p['filter'] = implode(' AND ', $p['filter']);
            }
            if (strlen($query['where']) > 0) {
                $query['where'] .= ' AND ';
            }
            $query['where'] .= ' (' . $p['filter'] . ') ';
        }
        // Multi-Tenant filter
        if (Bootstrap::f('tenant_multi_enable', false) && $this->_a['multitenant']) {
            // Note: Hadi, 1395-11-26: Table should be set before tenant field.
            // It is to avoid ambiguous problem in join tables which both have tenant field.
            $sql = new SQL(ModelUtils::getTable($this) . '.tenant=%s', array(
                Tenant::current()->id
            ));
            if (strlen($query['where']) > 0) {
                $query['where'] = ' AND ' . $query['where'];
            }
            $query['where'] = $sql->gen() . $query['where'];
        }
        if (! is_null($p['order'])) {
            if (is_array($p['order'])) {
                $p['order'] = implode(', ', $p['order']);
            }
            if (strlen($query['order']) > 0 and strlen($p['order']) > 0) {
                $query['order'] .= ', ';
            }
            $query['order'] .= $p['order'];
        }
        if (! is_null($p['start']) && is_null($p['nb'])) {
            $p['nb'] = 10000000;
        }
        if (! is_null($p['start'])) {
            if ($p['start'] != 0) {
                $p['start'] = (int) $p['start'];
            }
            $p['nb'] = (int) $p['nb'];
            $query['limit'] = 'LIMIT ' . $p['nb'] . ' OFFSET ' . $p['start'];
        }
        if (! is_null($p['nb']) && is_null($p['start'])) {
            $p['nb'] = (int) $p['nb'];
            $query['limit'] = 'LIMIT ' . $p['nb'];
        }
        if ($p['count'] == true) {
            if (isset($query['select_count'])) {
                $query['select'] = $query['select_count'];
            } else {
                $query['select'] = 'COUNT(*) as nb_items';
            }
            $query['order'] = '';
            $query['limit'] = '';
        }
        $req = 'SELECT ' . $query['select'] . ' FROM ' . $this->_con->pfx . $query['from'] . ' ' . $query['join'];
        if (strlen($query['where'])) {
            $req .= "\n" . 'WHERE ' . $query['where'];
        }
        if (strlen($query['group'])) {
            $req .= "\n" . 'GROUP BY ' . $query['group'];
        }
        if (strlen($query['having'])) {
            $req .= "\n" . 'HAVING ' . $query['having'];
        }
        if (strlen($query['order'])) {
            $req .= "\n" . 'ORDER BY ' . $query['order'];
        }
        if (strlen($query['limit'])) {
            $req .= "\n" . $query['limit'];
        }
        if (false === ($rs = $this->_con->select($req))) {
            throw new Exception($this->_con->getError());
        }
        if (count($rs) == 0) {
            return new ArrayObject();
        }
        if ($p['count'] == true) {
            return $rs;
        }
        $res = new ArrayObject();
        foreach ($rs as $row) {
            $this->_reset();
            foreach ($this->_a['cols'] as $col => $val) {
                if (isset($row[$col])) {
                    $this->_data[$col] = $this->_fromDb($row[$col], $col);
                }
            }
            // FIXME: The associated properties need to be converted too.
            foreach ($query['props'] as $prop => $key) {
                $this->_data[$key] = (isset($row[$prop])) ? $row[$prop] : null;
            }
            $this->restore();
            $res[] = clone ($this);
        }
        return $res;
    }

    /**
     * Get the number of items.
     *
     * @see getList() for definition of the keys
     *     
     * @param
     *            array with associative keys 'view' and 'filter'
     * @return int The number of items
     */
    function getCount($p = array())
    {
        $p['count'] = true;
        $count = $this->getList($p);
        if (empty($count) or count($count) == 0) {
            return 0;
        } else {
            return (int) $count[0]['nb_items'];
        }
    }

    /**
     * Get a list of related items.
     *
     * See the getList() method for usage of the view and filters.
     *
     * @param
     *            string Class of the related items
     * @param
     *            string Method call in a many to many related
     * @param
     *            array Parameters, see getList() for the definition of
     *            the keys
     * @return array Array of items
     */
    function getRelated($model, $method = null, $p = array())
    {
        $default = array(
            'view' => null,
            'filter' => null,
            'order' => null,
            'start' => null,
            'nb' => null,
            'count' => false
        );
        $p = array_merge($default, $p);
        if ('' == $this->_data['id']) {
            return new ArrayObject();
        }
        $m = new $model();
        if (isset($this->_m['list'][$method]) and is_array($this->_m['list'][$method])) {
            $foreignkey = $this->_m['list'][$method][1];
            if (strlen($foreignkey) == 0) {
                throw new Exception(sprintf('No matching foreign key found in model: %s for model %s', $model, $this->getClass()->getName()));
            }
            if (! is_null($p['filter'])) {
                if (is_array($p['filter'])) {
                    $p['filter'] = implode(' AND ', $p['filter']);
                }
                $p['filter'] .= ' AND ';
            } else {
                $p['filter'] = '';
            }
            $p['filter'] .= $this->_con->qn($foreignkey) . '=' . $this->_toDb($this->_data['id'], 'id');
        } else {
            $table = ModelUtils::getAssocTable($this, $m);
            if (isset($m->_a['views'][$p['view']])) {
                $m->_a['views'][$p['view'] . '__manytomany__'] = $m->_a['views'][$p['view']];
                if (! isset($m->_a['views'][$p['view'] . '__manytomany__']['join'])) {
                    $m->_a['views'][$p['view'] . '__manytomany__']['join'] = '';
                }
                if (! isset($m->_a['views'][$p['view'] . '__manytomany__']['where'])) {
                    $m->_a['views'][$p['view'] . '__manytomany__']['where'] = '';
                }
            } else {
                $m->_a['views']['__manytomany__'] = array(
                    'join' => '',
                    'where' => ''
                );
                $p['view'] = '';
            }

            $m->_a['views'][$p['view'] . '__manytomany__']['join'] .= ' LEFT JOIN ' . $table . ' ON ' . ModelUtils::getAssocField($m) . ' = ' . ModelUtils::getTable($m) . '."id"';

            $m->_a['views'][$p['view'] . '__manytomany__']['where'] = ModelUtils::getAssocField($this) . '=' . $this->getId();
            $p['view'] = $p['view'] . '__manytomany__';
        }
        return $m->getList($p);
    }

    /**
     * Generate the SQL select from the columns
     */
    function getSelect()
    {
        if (isset($this->_cache['getSelect']))
            return $this->_cache['getSelect'];
        $select = array();
        $table = ModelUtils::getTable($this);
        foreach ($this->_a['cols'] as $col => $val) {
            if ($val['type'] != '\\Pluf\\DB\\Field\\Manytomany') {
                $select[] = $table . '.' . $this->_con->qn($col) . ' AS ' . $this->_con->qn($col);
            }
        }
        $this->_cache['getSelect'] = implode(', ', $select);
        return $this->_cache['getSelect'];
    }

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
    function update($where = '')
    {
        $this->preSave();
        $req = 'UPDATE ' . ModelUtils::getTable($this) . ' SET' . "\n";
        $fields = array();
        $assoc = array();
        foreach ($this->_a['cols'] as $col => $val) {
            $field = new $val['type']();
            if ($col == 'id') {
                continue;
            } elseif ($field->type == 'manytomany') {
                if (is_array($this->$col)) {
                    $assoc[$val['model']] = $this->$col;
                }
                continue;
            }
            $fields[] = $this->_con->qn($col) . ' = ' . $this->_toDb($this->$col, $col);
        }
        $req .= implode(',' . "\n", $fields);
        if (strlen($where) > 0) {
            $req .= ' WHERE ' . $where;
        } else {
            $req .= ' WHERE id = ' . $this->_toDb($this->_data['id'], 'id');
        }
        $this->_con->execute($req);
        if (false === $this->get($this->_data['id'])) {
            return false;
        }
        foreach ($assoc as $model => $ids) {
            $this->batchAssoc($model, $ids);
        }
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
        if (Bootstrap::f('tenant_multi_enable', false) && $this->_a['multitenant']) {
            $this->tenant = Tenant::current();
        }
        $req = 'INSERT INTO ' . ModelUtils::getTable($this) . "\n";
        $icols = array();
        $ivals = array();
        $assoc = array();
        foreach ($this->_a['cols'] as $col => $val) {
            $field = new $val['type']();
            if ($col == 'id' and ! $raw) {
                continue;
            } elseif ($field->type == 'manytomany') {
                // If is a defined array, we need to associate.
                if (is_array($this->_data[$col])) {
                    $assoc[$val['model']] = $this->_data[$col];
                }
                continue;
            }
            $icols[] = $this->_con->qn($col);
            $ivals[] = $this->_toDb($this->_data[$col], $col);
        }
        $req .= '(' . implode(', ', $icols) . ') VALUES ';
        $req .= '(' . implode(',' . "\n", $ivals) . ')';
        $this->_con->execute($req);
        if (! $raw) {
            if (false === ($id = $this->_con->getLastID())) {
                throw new Exception($this->_con->getError());
            }
            $this->_data['id'] = $id;
        }
        foreach ($assoc as $model => $ids) {
            $this->batchAssoc($model, $ids);
        }
        if (! $raw) {
            $this->postSave(true);
        }
        return true;
    }

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
    // return self::removeDuplicates($affected);
    // }

    /**
     * Delete the current model from the database.
     *
     * If another model link to the current model through a foreign
     * key, the DB is responsible to mange relations.
     */
    function delete()
    {
        if (false === $this->get($this->_data['id'])) {
            return false;
        }
        $this->preDelete();
        $this->_con->execute('DELETE FROM ' . ModelUtils::getTable($this) . ' WHERE id = ' . $this->_toDb($this->_data['id'], 'id'));
        $this->_reset();
        return true;
    }

    /**
     * Reset the fields to default values.
     */
    function _reset()
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
        return '<' . $this->getClass()->getName() . '(' . $this->_data['id'] . ')';
    }

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
     * Set a view.
     *
     * @param
     *            string Name of the view.
     * @param
     *            array Definition of the view.
     */
    function setView($view, $def)
    {
        $this->_a['views'][$view] = $def;
    }

    /**
     * Prepare the value to be put in the DB.
     *
     * @param
     *            mixed Value.
     * @param
     *            string Column name.
     * @return string SQL ready string.
     */
    function _toDb($val, $col)
    {
        $m = $this->_con->type_cast[$this->_a['cols'][$col]['type']][1];
        return $m($val, $this->_con);
    }

    /**
     * Get the value from the DB.
     *
     * Create DB field and returns. The field type is used as the output
     * value type.
     *
     * @param
     *            mixed Value.
     * @param
     *            string Column name.
     * @return mixed Value.
     */
    function _fromDb($val, $col)
    {
        $m = $this->_con->type_cast[$this->_a['cols'][$col]['type']][0];
        return ($m == '\Pluf\DB::identityFromDb') ? $val : $m($val);
    }

    /**
     * Display value.
     *
     * When you have a list of choices for a field and you want to get
     * the display value of the current stored value.
     *
     * @param
     *            string Field to display the value.
     * @return mixed Display value, if not available default to the value.
     */
    function displayVal($col)
    {
        if (! isset($this->_a['cols'][$col]['choices'])) {
            return $this->_data[$col]; // will on purposed failed if not set
        }
        $val = array_search($this->_data[$col], $this->_a['cols'][$col]['choices']);
        if ($val !== false) {
            return $val;
        }
        return $this->_data[$col];
    }

    /**
     * Creates automatic function for a model
     *
     * Adds the get_xx_list method when the methods of the model
     * contains custom names.
     *
     * @param string $type
     *            Relation type: 'foreignkey' or 'manytomany'.
     */
    private function setupAutomaticListMethods($type)
    {
        $relations = ModelUtils::getModelRelations($this, $type);

        if (isset($relations)) {
            foreach ($relations as $related) {
                $current_model = '\\' . $this->getClass()->getName();
                if ($related != $current_model) {
                    $model = new $related();
                } else {
                    $model = clone $this;
                }
                $fkeys = $model->getRelationKeysToModel($current_model, $type);

                foreach ($fkeys as $fkey => $val) {
                    $relatedRef = new \ReflectionClass($related);
                    $mname = $relatedRef->getShortName();
                    if (array_key_exists('relate_name', $val)) {
                        $mname = $val['relate_name'];
                    }
                    $mname = 'get_' . strtolower($mname) . '_list';
                    if ('foreignkey' === $type) {
                        $this->_m['list'][$mname] = array(
                            $related,
                            $fkey
                        );
                    } else {
                        $this->_m['list'][$mname] = $related;
                        $this->_m['many'][$related] = $type;
                    }
                }
            }
        }
    }

    /**
     * Add tenant required fields
     *
     * Adds extra fields if multi-tenant is enabled
     */
    private function setupMultitenantFields()
    {
        if (Bootstrap::f('tenant_multi_enable', false) && $this->multitinant) {
            // Add key
            $this->_a['cols']['tenant'] = $this->tenant_field;
            // Add idx
            foreach ($this->_a['idx'] as $col => $idx) {
                $this->_a['idx'][$col]['col'] = 'tenant,' . $idx['col'];
            }
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
    public function getClass(): \ReflectionObject
    {
        return $this->class;
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

    /**
     * (non-PHPdoc)
     *
     * @see JsonSerializable::jsonSerialize()
     */
    public function jsonSerialize()
    {
        $coded = array();
        foreach ($this->_data as $col => $val) {
            /*
             * خصوصیت‌هایی که قابل خواندن نیستن سریال نخواهند شد
             */
            if (array_key_exists($col, $this->_a['cols']) && array_key_exists('readable', $this->_a['cols'][$col]) && ! $this->_a['cols'][$col]['readable'])
                continue;
            /*
             * If parameter ins null, zero, empty, ... we will not encode
             */
            if ($val)
                $coded[$col] = $val;
        }
        return $coded;
    }

    public function getName()
    {
        return array_key_exists('verbose', $this->_a) ? $this->_a['verbose'] : $this->getClass()->getName();
    }

    public function getSchema()
    {
        $mainInfo = array(
            "type" => $this->getClass()->getShortName(),
            "unit" => null,
            "name" => $this->getName(),
            "title" => $this->getName(),
            "description" => null,
            "defaultValue" => null,
            "required" => false,
            "visible" => false,
            "editable" => false,
            "priority" => 0,
            "validators" => [],
            "tags" => [],
            "children" => []
        );
        foreach ($this->_a['cols'] as $name => $field) {
            $fieldInfo = $this->getFieldInfo($name, $field);
            array_push($mainInfo['children'], $fieldInfo);
        }
        return $mainInfo;
    }

    public function isEquals(Model $model)
    {
        return $this->getClass()->isInstance($model) && $this->getId() == $model->getId();
    }

    private function getFieldInfo($name, $field)
    {
        return array(
            "type" => (new $field['type']())->type,
            "unit" => null,
            "name" => $name,
            "title" => $name,
            "description" => null,
            "defaultValue" => array_key_exists('default', $field) ? $field['default'] : null,
            "required" => array_key_exists('is_null', $field) ? $field['is_null'] : true,
            "visible" => array_key_exists('readable', $field) ? $field['readable'] : true,
            "editable" => array_key_exists('editable', $field) ? $field['editable'] : false,
            "priority" => 0,
            "validators" => [],
            "tags" => [],
            "children" => []
        );
    }

    // /**
    // * Check if a model is already in an array of models.
    // *
    // * It is not possible to override the == function in PHP to directly
    // * use in_array.
    // *
    // * @param
    // * Model The model to test
    // * @param
    // * Array The models
    // * @return bool
    // */
    // public static function inArray(Model $model, $array)
    // {
    // if ($model->isAnonymous()) {
    // return false;
    // }
    // foreach ($array as $item) {
    // if ($model->isEquals($item)) {
    // return true;
    // }
    // }
    // return false;
    // }

    // /**
    // * Return a list of unique models.
    // *
    // * @param
    // * array Models with duplicates
    // * @return array Models with duplicates.
    // */
    // public static function removeDuplicates($array)
    // {
    // $res = array();
    // foreach ($array as $model) {
    // if (! self::inArray($model, $res)) {
    // $res[] = $model;
    // }
    // }
    // return $res;
    // }
}
