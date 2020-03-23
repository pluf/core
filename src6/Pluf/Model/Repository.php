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

namespace Pluf\Model;

use Pluf_Model;

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

    /**
     * Stores an instance of the model type
     *
     * @var \Pluf_Model
     */
    private Pluf_Model $model;

    private static array $repositoryMap = [];

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

    public function get($id): ?Pluf_Model
    {
        $modelClassName = get_class($this->model);
        $model = new $modelClassName($id);
        return $model;
    }

    public function getOne(Query $query): ?Pluf_Model
    {
        return $this->model->getOne($query->toArray());
    }

    public function getList(Query $query): array
    {
        $res = $this->model->getList($query->toArray());
        if($res instanceof \ArrayObject){
            return $res->getArrayCopy();
        }
        return [];
    }

    public function getCount(Query $query): int
    {
        return $this->model->getCount($query->toArray());
    }

    public function getRelated(string $model, string $relation = null, Query $query): array
    {
        $this->model->getRelated($model, null, $query->toArray());
    }
}

