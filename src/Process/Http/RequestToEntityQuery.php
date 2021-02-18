<?php
namespace Pluf\Core\Process\Http;

use Pluf\Orm\EntityManager;
use Pluf\Scion\UnitTrackerInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Creates an entity query based on http request
 * 
 * NOTE: the class must set.
 * 
 * @author maso
 *
 */
class RequestToEntityQuery
{
    public function __invoke(ServerRequestInterface $request, EntityManager $entityManager, UnitTrackerInterface $unitTracker)
    {
        $entityQuery = $entityManager->createQuery();
        //XXX: maso, 2021: read params from the request header.
        return $unitTracker->next([
            'entityQuery' => $entityQuery
        ]);
    }
}

