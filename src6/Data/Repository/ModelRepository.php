<?php
namespace Pluf\Data\Repository;

use Pluf\Data\Exception;
use Pluf\Data\ModelDescription;
use Pluf\Data\Query;
use Pluf\Db\Expression;

/**
 * A repository of data models
 *
 * Pluf use repository model to manages objects.
 * We do not support Data Model query, but, repository model are responsible to query and find
 * object from DB.
 *
 * Model repository works with a model and manages all instance of it. It is responsible to perform
 * CRUD and bulky operations.
 *
 *
 * @author maso(mostafa.barmshory@gmail.com)
 * @since 6.0 Adding first version of model repository
 * @see \Pluf\Data\Repository
 */
class ModelRepository extends \Pluf\Data\Repository
{

    /**
     * Stores an instance of the model type
     *
     * @var \Pluf_Model
     */
    public $model;

    /**
     * Store the model description of current model
     *
     * This is a virtual attribute and is build from the current model. It will
     * be updated automatically.
     *
     * @var ModelDescription
     */
    public ?ModelDescription $modelDescription = null;

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Data\Repository::get()
     */
    public function get(?Query $query = null)
    {
        return $this->getListByQuery($query);
    }

    /**
     * Get a given item.
     *
     * @param
     *            int Id of the item.
     * @return mixed Item or false if not found.
     */
    public function getById($id)
    {
        $connection = $this->getConnection();
        $schema = $this->getSchema();
        $md = $this->getModelDescription();

        $stm = $connection->query()
            ->table($schema->getTableName($md))
            ->where('id', $id)
            ->select();

        $this->checkStatement($stm);
        return $schema->newInstance($md, $stm->fetch());
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
     * @see self::getList
     * @param Query $query)
     *            A query to run on db
     * @return mixed|null find model
     */
    public function getOne(Query $query)
    {
        $items = $this->get($query);
        if (count($items) == 1) {
            return $items[0];
        }
        if (count($items) == 0) {
            return null;
        }
        throw new \Pluf\Exception([
            'message' => 'More than one matching item found.'
        ]);
    }

    /**
     * Creates the model and update the id
     *
     * @param mixed $model
     * @return mixed updated model
     */
    public function create($model)
    {
        $md = ModelDescription::getInstance($model);
        $schema = $this->getSchema();
        $connection = $this->getConnection();

        $stm = $connection->query()
            ->mode('insert')
            ->table($schema->getTableName($md))
            ->set($schema->getValues($md, $model))
            ->execute();

        $this->checkStatement($stm);
        $model->id = $connection->lastInsertID();
        return $model;
    }

    /**
     * Updates the model and return it as result
     *
     * @param mixed $model
     *            updated model
     * @throws Exception if the model is anonymous
     * @return mixed updated model
     */
    public function update($model)
    {
        $md = ModelDescription::getInstance($model);
        $schema = $this->getSchema();
        $connection = $this->getConnection();

        if ($md->isAnonymous($model)) {
            throw new Exception([
                'message' => 'Impossible to update anonymous object'
            ]);
        }

        $query = new Query([
            'filter' => [
                [
                    'id',
                    $model->id
                ]
            ]
        ]);

        $stm = $connection->query()
            ->mode('update')
            ->table($schema->getTableName($md))
            ->set($schema->getValues($md, $model))
            ->where($schema->getQueryFilters($md, $query))
            ->execute();

        $this->checkStatement($stm);
        return $model;
    }

    /**
     * Removes the model
     *
     * @param mixed $model
     *            A model to delete from repository
     * @throws Exception if the model is anonymous
     * @return mixed deleted model
     */
    public function delete($model)
    {
        $md = ModelDescription::getInstance($model);
        $schema = $this->getSchema();
        $connection = $this->getConnection();

        if ($md->isAnonymous($model)) {
            throw new Exception([
                'message' => 'Impossible to update anonymous object'
            ]);
        }

        $query = new Query([
            'filter' => [
                [
                    'id',
                    $model->id
                ]
            ]
        ]);

        $stm = $connection->query()
            ->mode('delete')
            ->table($schema->getTableName($md))
            ->set($schema->getValues($md, $model))
            ->where($schema->getQueryFilters($md, $query))
            ->execute();

        $this->checkStatement($stm);
        return $model;
    }

    /**
     * Gets list of object
     *
     * Note: all object will be fetched in the memory
     *
     * The filter should be used only for simple filtering. If you want
     * a complex query, you should create a new view.
     * Both filter and order accept an array or a string in case of multiple
     *
     * @param Query $query
     *            to run on db
     * @return array|int The result of items or through an exception if
     *         database failure
     */
    public function getListByQuery(Query $query)
    {
        // TODO: maso, 2020: support cache
        $schema = $this->getSchema();
        $md = $this->getModelDescription();
        $connection = $this->getConnection();

        // fields
        $view = [
            'filter' => [],
            'field' => [],
            'order' => [],
            'join' => [],
            'group' => [],
            'having' => []
        ];

        if ($query->hasView()) {
            // load the view
            $viewName = $query->getView();
            if (is_string($viewName)) {
                $dataView = $md->getView($query->getView());
            } else {
                $dataView = $viewName;
            }

            // Filter
            if (array_key_exists('filter', $dataView)) {
                $view['filter'] = array_merge( //
                $schema->getViewFilters($md, $dataView), //
                $schema->getQueryFilters($md, $query) //
                );
            } else {
                $view['filter'] = $schema->getQueryFilters($md, $query);
            }

            // Field
            if (array_key_exists('field', $dataView)) {
                $view['field'] = $schema->getViewFields($md, $dataView);
            } else {
                $view['field'] = $schema->getFields($md);
            }

            // Order
            $allDataOrders = $query->getOrder();
            if (isset($dataView['order'])) {
                $allDataOrders = array_merge($allDataOrders, $dataView['order']);
            }
            foreach ($allDataOrders as $name => $order) {
                $view['order'][] = [
                    $schema->getField($md, $name),
                    $order == Query::ORDER_DESC
                ];
            }

            // Join
            if (isset($dataView['join'])) {
                $view['join'] = $schema->getViewJoins($md, $dataView);
            }

            // Group
            $view['group'] = $schema->getViewGroupBy($md, $dataView);
            $view['having'] = $schema->getViewHaving($md, $dataView);
        } else {
            $view['field'] = $schema->getFields($md);
            $view['filter'] = $schema->getQueryFilters($md, $query);

            // Order
            foreach ($query->getOrder() as $name => $order) {
                $view['order'][] = [
                    $schema->getField($md, $name),
                    $order == Query::ORDER_DESC
                ];
            }
        }

        $dbQuery = $connection->query()
            ->mode('select')
            ->table($schema->getTableName($md))
            ->group($view['group']);
        // add where
        foreach ($view['filter'] as $filter) {
            if ($filter instanceof Expression) {
                $dbQuery->where($filter);
                continue;
            }
            call_user_func_array([
                $dbQuery,
                'where'
            ], $filter);
        }
        // add having
        foreach ($view['having'] as $filter) {
            if ($filter instanceof Expression) {
                $dbQuery->having($filter);
                continue;
            }
            call_user_func_array([
                $dbQuery,
                'having'
            ], $filter);
        }

        // add join
        foreach ($view['join'] as $join) {
            call_user_func_array([
                $dbQuery,
                'join'
            ], $join);
        }

        if ($query->getCount()) {
            $dbQuery->field('count(*)', 'count');
        } else {
            // add order
            foreach ($view['order'] as $order) {
                call_user_func_array([
                    $dbQuery,
                    'order'
                ], $order);
            }
            // limit
            $dbQuery->field($view['field'])->limit($query->getLimit(), $query->getStart());
        }

        // Checks the query statement
        $stm = $dbQuery->execute();
        $this->checkStatement($stm);

        if ($query->getCount()) {
            return $stm->fetch()['count'];
        }
        // Convert to new items
        $result = $stm->fetchAll();
        $items = [];
        foreach ($result as $data) {
            $items[] = $schema->newInstance($md, $data);
        }
        return $items;
    }

    public function deleteListByQuery(Query $query): array
    {
        $res = $this->sourceModel->getList($query->toArray());
        if ($res instanceof \ArrayObject) {
            return $res->getArrayCopy();
        }
        return [];
    }

    /**
     * Get the number of items.
     *
     * @see getList() for definition of the keys
     *     
     * @param Query $query
     *            with associative keys 'view' and 'filter'
     * @return int The number of items
     */
    public function getCount(Query $query): int
    {
        return $this->sourceModel->getCount($query->toArray());
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Data\Repository::clean()
     */
    protected function clean()
    {
        $this->modelDescription = null;
    }

    /**
     * Gets model descriptions related to the current type
     *
     * @return \Pluf\Data\ModelDescription
     */
    protected function getModelDescription(?string $type = null): ModelDescription
    {
        if (! isset($this->modelDescription)) {
            $type = $this->model;
            $this->modelDescription = parent::getModelDescription($type);
        }
        return $this->modelDescription;
    }
}

