<?php
namespace Pluf\Core\Process\Entity;

use Pluf\Orm\AssertionTrait;
use Pluf\Orm\EntityQuery;
use Pluf\Orm\EntityManager;

class DeleteEntity
{
    use AssertionTrait;

    private string $class;

    private string $name = "itemId";

    public function __construct(string $class, string $name = "itemId")
    {
        $this->class = $class;
        $this->name = $name;
    }

    /**
     * Store list of cusomers into the repositoer
     *
     *
     * @param EntityQuery $entityQuery
     *            to perform on entities
     * @return array of results
     */
    public function __invoke(EntityManager $entityManager, $itemId)
    {
        $entity = $entityManager->find($this->class, $itemId);
        $this->assertNotEmpty($entity, 'Entity type {{type}} not found with ID {{itemId}', [
            'type' => $this->class,
            'itemId' => $itemId
        ]);
        $entityManager->remove($entity);
        return $entity;
    }
}

