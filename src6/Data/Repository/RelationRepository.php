<?php
namespace Pluf\Data\Repository;

use Pluf\Data\ModelDescription;
use Pluf\Data\ModelProperty;
use Pluf\Data\Query;
use Pluf\Data\Repository;

/**
 * A relation repository
 *
 * In pluf all relations will be stored in a repository and you are free to query them.
 * For example you can select all related chapter to a book with a query or finds all itmes in a shop order.
 *
 *
 * @author maso(mostafa.barmshory@gmail.com)
 * @since 6.0 Adding first version of relation repository
 * @see \Pluf\Data\Repository
 */
class RelationRepository extends Repository
{

    private ?ModelDescription $targetMd;

    private ?string $targetType;

    private ?string $relationName;

    private ?ModelProperty $relationPd;

    public function getTargets($source, Query $query): array
    {
        return [];
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
    public function getSources($target, Query $query): array
    {

        // if (isset($this->_m['list'][$method]) and is_array($this->_m['list'][$method])) {
        // $foreignkey = $this->_m['list'][$method][1];
        // if (strlen($foreignkey) == 0) {
        // throw new Exception(sprintf('No matching foreign key found in model: %s for model %s', $model, $this->_a['model']));
        // }
        // if (! is_null($p['filter'])) {
        // if (is_array($p['filter'])) {
        // $p['filter'] = implode(' AND ', $p['filter']);
        // }
        // $p['filter'] .= ' AND ';
        // } else {
        // $p['filter'] = '';
        // }
        // $p['filter'] .= $schema->qn($foreignkey) . '=' . $engine->toDb($this->_data['id'], Engine::SEQUENCE);
        // } else {
        // $manyToManyView = array(
        // 'join' => '',
        // 'where' => ''
        // );

        // if ($model->hasView($p['view'])) {
        // $manyToManyView = array_merge($manyToManyView, $model->getView($p['view']));
        // }

        // $manyToManyView['join'] .= ' LEFT JOIN ' . $schema->getRelationTable($this, $model) . ' ON ' . $schema->getAssocField($model) . ' = ' . $schema->getTableName($model) . '.id';
        // $manyToManyView['where'] = $schema->getAssocField($this) . '=' . $this->id;

        // $manyToManyViewName = $p['view'] . '__manytomany__';
        // $model->setView($manyToManyViewName, $manyToManyView);
        // $p['view'] = $manyToManyViewName;
        // }
        // return $model->getList($p);
    }

    public function createRelation($source, $target): array
    {
        // $fromMd = $this->getModelDescription();
        // $toMd = ModelDescription::getInstance($to);

        // $schema = $this->schema;

        // if (! isset($relationName)) {
        // $relationName = $this->getRelationName($fromMd, $toMd);
        // }
    }

    public function deleteRelation($source, $target): array
    {
        // if ($this->isForignKeyRelation($relationName)) {
        // // TODO: support foring key
        // } elseif ($this->isManyToManyRelation($relationName)) {
        // $this->sourceModelType->getRelated($model, null, $query->toArray());
        // } else {
        // throw new InvalidRelationKeyException($this->sourceModelType, $model, $relationName);
        // }
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Data\Repository::clean()
     */
    protected function clean()
    {
        // load model description
        if ($this->sourceType) {
            $this->sourceMd = ModelDescription::getInstance($this->sourceType);
        } else {
            $this->sourceMd = null;
        }
    }
}

