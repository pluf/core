<?php
namespace Pluf\Core\Process\Entity;

use Pluf\Core\Exception;
use Pluf\Orm\AssertionTrait;
use Pluf\Orm\EntityManager;
use Throwable;
use Pluf\Orm\ModelDescriptionRepository;

class UpdateEntity
{
    use AssertionTrait;

    private string $class;


    public function __construct(string $class)
    {
        $this->class = $class;
    }

    public function __invoke(EntityManager $entityManager, ModelDescriptionRepository $modelDescriptionRepository, $entity, $itemId)
    {
        $md = $modelDescriptionRepository->get($this->class);
        $id = $md->properties[$md->primaryKey];
        
        $oldentity = $entityManager->find($this->class, $itemId);
        $this->assertTrue($oldentity, 'Entity type {{type}} with ID {{itemId} not found', [
            'type' => $this->class,
            'itemId' => $itemId
        ]);
        
        $idValue = $id->getValue($oldentity);
        $id->setValue($entity, $idValue);
        $entity = $entityManager->merge​($entity);
        
        return $entity;
    }
    
    
    
    /**
     * Creates new exception
     *
     * @param string $message
     * @param int $code
     * @param Throwable $previous
     * @param array $params
     * @param array $solutions
     * @return Throwable
     */
    protected function generateException($message = '', ?int $code = null, ?Throwable $previous = null, ?array $params = [], ?array $solutions = []): Throwable
    {
        return new Exception($message, $code, $previous, 404, $params, $solutions);
    }
}

