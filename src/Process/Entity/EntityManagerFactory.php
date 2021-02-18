<?php
namespace Pluf\Core\Process\Entity;

use Pluf\Scion\UnitTrackerInterface;

class EntityManagerFactory
{

    public function __invoke(\Pluf\Orm\EntityManagerFactory $entityManagerFactory, UnitTrackerInterface $unitTracker)
    {
        $entityManager = $entityManagerFactory->createEntityManager();
        try {
            return $unitTracker->next([
                "entityManager" => $entityManager
            ]);
        } finally {
            $entityManager->close();
        }
    }
}

