<?php
namespace Pluf\Core\Process\Entity;

use Pluf\Orm\AssertionTrait;
use Pluf\Orm\EntityQuery;

class ReadEntities
{
    use AssertionTrait;
    
    private string $class;
    private string $name = "entities";
    
    public function __construct(string $class, string $name = "entities")
    {
        $this->class = $class;
        $this->name = $name;
    }
    
    /**
     * Store list of cusomers into the repositoer
     *
     *
     * @param EntityQuery $entityQuery to perform on entities
     * @return array of results
     */
    public function __invoke(EntityQuery $entityQuery): array
    {
        return $entityQuery->entity($this->class)
            ->mode('select')
            ->exec();
    }
        
}

