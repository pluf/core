<?php
namespace Pluf\Core\Process\Http;

use Pluf\Core\CollectionQuery;
use Pluf\Orm\EntityManager;
use Pluf\Orm\ObjectMapper;
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

    public function __invoke(ServerRequestInterface $request, EntityManager $entityManager, ObjectMapper $objectMapperArray, UnitTrackerInterface $unitTracker)
    {
        $collectionQuery = $objectMapperArray->readValue($request->getQueryParams(), CollectionQuery::class);

        // TODO: maso, 2021: validate the collection query
        
        $query = $entityManager->query()
            ->limit($collectionQuery->count, $collectionQuery->start);

        // TODO: maso, 2021: suport collection query
        // TODO: amso, 2021: suport collection query filter
        // TODO: maso, 2021: suport collection query sort
        // TODO: maso, 2021: suport collection query order

        return $unitTracker->next([
            'collectionQuery' => $collectionQuery,
            'entityQuery' => $query
        ]);
    }
}

