<?php
namespace Pluf\Data\Repository;

use Pluf\Options;
use Pluf\Data\Exception;
use Pluf\Data\ModelDescription;
use Pluf\Data\ModelProperty;
use Pluf\Data\Query;
use Pluf\Data\Repository;
use Pluf\Data\Schema;

/**
 * A relation repository
 *
 * In pluf all relations will be stored in a repository and you are free to query them.
 * For example you can select all related chapter to a book with a query or finds all itmes in a shop order.
 *
 * There are three main concepts:
 *
 * - source
 * - relation
 * - target
 *
 * Which is mean a source has a relation to a target. To map standard repository CRUDE we
 * suppose following functionalities:
 *
 * - get: list all targets with
 * - create: a new relation (target as extra param)
 * - delete: a relation (target as extra param)
 * - update: not supported
 *
 * @author maso(mostafa.barmshory@gmail.com)
 * @since 6.0 Adding first version of relation repository
 * @see \Pluf\Data\Repository
 */
class RelationRepository extends Repository
{

    public ?string $source = null;

    public ?string $target = null;

    public ?string $relation = null;

    /**
     * Creates new instance of the repository
     *
     * NOTE: Relation name and property will be loaded and checked.
     *
     * @param Options $optionss
     * @throws Exception
     */
    public function __construct(Options $optionss)
    {
        parent::__construct($optionss);

        // cehck relation name
        if (! isset($this->relation)) {
            throw new Exception([
                'message' => 'Relation name is required'
            ]);
        }

        // cheach relation property
        $relationName = $this->relation;
        $smd = $this->getModelDescription($this->source);
        $this->relationPd = $smd->$relationName;
        if (! isset($this->relationPd)) {
            throw new Exception([
                'message' => 'Relation property is not defined in srouce relation'
            ]);
        }
    }

    /**
     * Get a list of targets
     *
     * See the get() method for usage of the view and filters.
     *
     * For example, to find a book which is related to a item from NoteBook application
     * you have to get relation repository as follow:
     *
     * ```php
     * <?php
     *
     * $repository = new RelationRepository(new Options([
     * 'source' => '\Pluf\NoteBook\Item',
     * 'target' => '\Pluf\NoteBook\Book',
     * 'relation' => 'items',
     * ]));
     *
     * $query->setFilter('id', $item->id);
     * $books = $repository->get($query);
     * ```
     * As you can see, the get is responsible to list all targets item which is
     * joined to the source model based on query.
     *
     * In the other hand to get list of items from a book:
     *
     * ```php
     * <?php
     *
     * $repository = new RelationRepository(new Options([
     * 'source' => '\Pluf\NoteBook\Book',
     * 'target' => '\Pluf\NoteBook\Item',
     * 'relation' => 'items',
     * ]));
     *
     * $query->setFilter('book', $book);
     * $items = $repository->get($query);
     * ```
     *
     * ## Query
     *
     * You are free to use both target and source attributes in the query at
     * the same time. How ever there may be same attributes in both source and
     * target. To cover this condition, the relation name will be assuemd as
     * alias for all target objects.
     *
     * For example, here is a code to get all chapters of a book which is published:
     *
     * ```php
     * <?php
     *
     * $repository = new RelationRepository(new Options([
     * 'source' => '\Pluf\NoteBook\Book',
     * 'target' => '\Pluf\NoteBook\Item',
     * 'relation' => 'items', // is used as alias
     * ]));
     *
     * $query->setFilter('book', $book);
     * $query->setFilter('items.status', 'published');
     * $items = $repository->get($query);
     * ```
     *
     *
     *
     * {@inheritdoc}
     * @see \Pluf\Data\Repository::get()
     */
    public function get(?Query $query = null)
    {
        if (! isset($query)) {
            $query = new Query();
        }
        if ($query->hasView()) {
            throw new Exception([
                'message' => 'Query view is not supported in relation repository'
            ]);
        }

        $query->setView([
            'join' => [
                [
                    'joinProperty' => $this->relation,
                    'alias' => $this->relation
                ]
            ]
        ]);

        $repo = Repository::getInstance([
            'model' => $this->source,
            'connection' => $this->getConnection(),
            'schema' => $this->getSchema()
        ]);
        return $repo->get($query);
    }

    /**
     * Creates new relation from $srouce to $target
     *
     * {@inheritdoc}
     * @see \Pluf\Data\Repository::create()
     */
    public function create($source, $target = null)
    {
        if (! isset($target)) {
            throw new Exception([
                'message' => 'It is not possible to put null object into relation repository'
            ]);
        }
        $schema = $this->getSchema();

        $smd = $this->getModelDescription($this->source);
        $tmd = $this->getModelDescription($this->target);
        $relation = $this->getRelationProperty($smd, $tmd, $this->relation);

        switch ($relation->type) {
            case Schema::MANY_TO_MANY:
                // 1. define table:
                $relationTable = $schema->getRelationTable($smd, $tmd, $relation);

                // 2. get relation fields
                $relationSourceField = $schema->getRelationSourceField($smd, $tmd, $relation, false);
                $relationTargetField = $schema->getRelationTargetField($smd, $tmd, $relation, false);

                $connection = $this->getConnection();
                // NOTE: suppose the id is unique for source
                // NOTE: suppose the id is unique for target
                $stm = $connection->query()
                    ->mode('insert')
                    ->table($relationTable)
                    ->set($relationSourceField, $source->id)
                    ->set($relationTargetField, $target->id)
                    ->execute();

                $this->checkStatement($stm);
                break;
            default:
                throw new Exception([
                    'message' => 'Relation type {type} is not supported in Create from relation repository.'
                ]);
        }
    }

    /**
     * Deletes the relation from $srouce to $target
     *
     * {@inheritdoc}
     * @see \Pluf\Data\Repository::delete()
     */
    public function delete($source, $target = null)
    {
        if (! isset($target)) {
            throw new Exception([
                'message' => 'It is not possible to put null object into relation repository'
            ]);
        }

        $smd = $this->getModelDescription($this->source);
        $tmd = $this->getModelDescription($this->target);
        $schema = $this->getSchema();

        $rp = $this->getRelationProperty($smd, $tmd, $this->relation);

        // 1. define table:
        $relationTable = $schema->getRelationTable($smd, $tmd, $rp);

        // 2. get relation fields
        $relationSourceField = $schema->getRelationSourceField($smd, $tmd, $rp);
        $relationTargetField = $schema->getRelationTargetField($smd, $tmd, $rp);

        $connection = $this->getConnection();
        // NOTE: suppose the id is unique for source
        // NOTE: suppose the id is unique for target
        $stm = $connection->query()
            ->mode('delete')
            ->table($relationTable)
            ->where($relationSourceField, '=', $source->id)
            ->where($relationTargetField, '=', $target->id)
            ->execute();

        $this->checkStatement($stm);
    }

    /**
     * Updates the relation.
     *
     * NOTE: it is not common fro relations
     *
     * {@inheritdoc}
     * @see \Pluf\Data\Repository::update()
     */
    public function update($source, $target = null)
    {
        throw new Exception([
            'message' => 'It is not possible to update a relation'
        ]);
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Data\Repository::clean()
     */
    protected function clean()
    {
        // load model description
        $this->sourceMd = null;
        $this->targetMd = null;
        $this->relationPd = null;
    }

    private function getRelationProperty(ModelDescription $smd, ModelDescription $tmd, string $relation): ModelProperty
    {
        // check relation
        $relationProperty = $smd->$relation;
        if (! isset($relationProperty)) {
            throw new Exception([
                'message' => 'The property wtih name {name} does not exist in type {type}',
                'type' => $smd->type,
                'name' => $relation->name
            ]);
        }
        // check type
        $type = $relationProperty->type;
        if (! ($type == Schema::MANY_TO_MANY || $type == Schema::MANY_TO_ONE || $type == Schema::ONE_TO_MANY)) {
            throw new Exception([
                'message' => 'The property wtih name {name} is not a relation type in {type}',
                'type' => $smd->type,
                'name' => $relation->name
            ]);
        }
        // check target model
        if (! $tmd->isInstanceOf($relationProperty->inverseJoinModel)) {
            throw new Exception([
                'message' => 'The type {target} does not match with relation type {type} from {source}',
                'type' => $relationProperty->inverseJoinModel,
                'source' => $smd->type,
                'target' => $tmd->type
            ]);
        }

        return $relationProperty;
    }
}



