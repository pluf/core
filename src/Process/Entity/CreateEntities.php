<?php
namespace Pluf\Core\Process\Entity;

use Pluf\Orm\EntityManager;

/**
 * Creates a list of customer and return them as result
 *
 *
 * @author maso
 *        
 */
class CreateEntities
{

    /**
     * Store list of cusomers into the repositoer
     *
     *
     * @param EntityManager $entityManager
     * @param array $customers
     *            list of customer
     * @return mixed[] list of entities to persist
     */
    public function __invoke(EntityManager $entityManager, $entities): array
    {
        if (! is_array($entities)) {
            $entities = [
                $entities
            ];
        }
        $resultList = [];
        foreach ($entities as $entity) {
            $item = $entityManager->persist​($entity);
            $resultList[] = $item;
        }
        return $resultList;
    }
}

