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

use Pluf\Db\Connection;
use PDOStatement;
use Pluf_Model;
use Pluf\Db\Expression;

/**
 * Repositories are object or components that encapsulate the logic required to access data sources.
 *
 * They centralize common data access functionality, providing better maintainability and decoupling the
 * infrastructure or technology used to access databases from the domain model layer. If you use an
 * Object-Relational Mapper (ORM) like Entity Framework, the code that must be implemented is simplified.
 * This lets you focus on the data persistence logic rather than on data access plumbing.
 *
 * The Repository pattern is a well-documented way of working with a data source. In the book Patterns
 * of Enterprise Application Architecture, Martin Fowler describes a repository as follows:
 *
 * A repository performs the tasks of an intermediary between the domain model layers and data mapping, acting
 * in a similar way to a set of domain objects in memory. Client objects declaratively build queries and send
 * them to the repositories for answers. Conceptually, a repository encapsulates a set of objects stored in
 * the database and operations that can be performed on them, providing a way that is closer to the persistence
 * layer. Repositories, also, support the purpose of separating, clearly and in one direction, the dependency
 * between the work domain and the data allocation or mapping.
 *
 * Basically, a repository allows you to populate data in memory that comes from the database in the form of the
 * domain entities. Once the entities are in memory, they can be changed and then persisted back to the
 * database through transaction.
 *
 * @author maso
 * @see https://martinfowler.com/eaaCatalog/repository.html
 * @see https://docs.microsoft.com/en-us/previous-versions/msp-n-p/ff649690(v=pandp.10)
 *
 */
class Repository
{

    private static array $repositoryMap = [];

    /**
     * Stores an instance of the model type
     *
     * @var \Pluf_Model
     */
    private $model;

    private ?Connection $dbConnection = null;

    private ?Schema $schema = null;

    /**
     * Store the model description of current model
     *
     * This is a virtual attribute and is build from the current model. It will
     * be updated automatically.
     *
     * @var ModelDescription
     */
    private ?ModelDescription $modelDescription = null;

    /**
     * Defines one repository per aggregate
     *
     * For each aggregate or aggregate root, you should create one repository object. In a microservice based on
     * Domain-Driven Design (DDD) patterns, the only channel you should use to update the database should be the
     * repositories. This is because they have a one-to-one relationship with the aggregate root, which controls
     * the aggregate’s invariants and transactional consistency. It's okay to query the database through other
     * channels (as you can do following a CQRS approach), because queries don't change the state of the database.
     * However, the transactional area (that is, the updates) must always be controlled by the repositories and
     * the aggregate roots.
     *
     * It can be valuable to create your repository in such a way that it enforces the rule that only
     * aggregate roots should have repositories. This utility functions create new instance of such a repository
     * for a given $modelType. Forexample:
     *
     * $repo = Repository::getInstance(Book::class);
     *
     * Creates a repository to mange Books.
     */
    public static function getInstance(string $modelType): Repository
    {
        if (array_key_exists($modelType, self::$repositoryMap)) {
            return self::$repositoryMap[$modelType];
        }
        // create
        $repo = new Repository($modelType);
        self::$repositoryMap[$modelType] = $repo;
        return $repo;
    }

    /**
     * Creates new instance of the repository
     *
     * @param string $modelType
     */
    public function __construct(string $modelType)
    {
        $this->model = new $modelType();
    }

    public function setSchema(Schema $schema): Repository
    {
        $this->schema = $schema;
        $this->clean();
        return $this;
    }

    public function setConnection(Connection $connection): Repository
    {
        $this->dbConnection = $connection;
        return $this;
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
        $stm = $this->dbConnection->query()
            ->table($this->getTableName())
            ->where('id', $id)
            ->select();

        $this->checkStatement($stm);
        return $this->createInstanceModel($stm->fetch());
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
     * @return Pluf_Model|null find model
     */
    public function getOne(Query $query)
    {
        return $this->model->getOne($query->toArray());
    }

    public function create($model)
    {
        $md = ModelDescription::getInstance($model);
        $schema = $this->schema;

        $stm = $this->dbConnection->query()
            ->mode('insert')
            ->table($schema->getTableName($md))
            ->set($schema->getValues($md, $model))
            ->execute();

        $this->checkStatement($stm);
        $model->id = $this->dbConnection->lastInsertID();
        return $model;
    }

    public function update($model)
    {}

    public function delete($model)
    {}

    public function createList(?array $models = []): array
    {}

    public function updateList(?array $models = []): array
    {}

    public function deleteList(?array $models = []): array
    {}

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
        $schema = $this->schema;
        $md = $this->getModelDescription();

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
            $dataView = $md->getView($query->getView());

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

        $dbQuery = $this->dbConnection->query()
            ->mode('select')
            ->table($this->getTableName())
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
                'have'
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
            $items[] = $this->createInstanceModel($data);
        }
        return $items;
    }

    public function deleteListByQuery(Query $query): array
    {
        $res = $this->model->getList($query->toArray());
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
        return $this->model->getCount($query->toArray());
    }

    /**
     * Get a list of related items.
     *
     * See the getList() method for usage of the view and filters.
     *
     *
     *
     *
     * ```php
     * <?php
     *
     * $repository = new Repository('\Pluf\Cms\Content');
     *
     * $content = $repository->get(1);
     * $parent = $repository->getRelated($content, 'parent');
     * $children = $repository->getRelated($content, 'children', $query);
     * ```
     *
     * @param \Pluf_Model $model
     *            The root of the relation
     * @param string $relationName
     *            name of ther relation to
     * @param Query $query
     *            to run on related objects if the result is many objects
     * @return array Array of items
     */
    public function getRelated(Pluf_Model $model, string $relationName = null, Query $query): array
    {
        if ($this->isForignKeyRelation($relationName)) {
            // TODO: support foring key
        } elseif ($this->isManyToManyRelation($relationName)) {
            $this->model->getRelated($model, null, $query->toArray());
        } else {
            throw new InvalidRelationKeyException($this->model, $model, $relationName);
        }
    }

    private function isForignKeyRelation(string $relationName): bool
    {
        return false;
    }

    private function isManyToManyRelation(string $relationName): bool
    {
        return true;
    }

    private function checkStatement(PDOStatement $stm): void
    {
        $info = $stm->errorInfo();
        if ($info[0] != 0) {
            throw new Exception([
                'code' => $info[0],
                'driverCode' => $info[1],
                'message' => $info[2]
            ]);
        }
    }

    private function createInstanceModel($data)
    {
        $md = $this->getModelDescription();
        return $this->schema->newInstance($md, $data);
    }

    private function getModelDescription(): ModelDescription
    {
        // Check if it is exist
        if (isset($this->modelDescription)) {
            return $this->modelDescription;
        }

        // create new description
        if (! isset($this->model)) {
            throw new Exception([
                'message' => 'Model is not defined for the repository',
                'code' => 2222
            ]);
        }
        $this->modelDescription = ModelDescription::getInstance($this->model);
        return $this->modelDescription;
    }

    private function getTableName()
    {
        $md = $this->getModelDescription();
        return $this->schema->getTableName($md);
    }

    private function clean()
    {
        // load model description
        if ($this->model) {
            $this->modelDescription = ModelDescription::getInstance($this->model);
        } else {
            $this->modelDescription = null;
        }
    }
}

