<?php
namespace Pluf\Core;

use Pluf\Orm\Attribute\Entity;
use Pluf\Orm\Attribute\Column;

// TODO: maso, 2020: add following constraint
// use Pluf\Orm\Attribute\IsPositive;
// use Pluf\Orm\Attribute\Max;

#[Entity]
class CollectionQuery
{
    
    #[Column('start')]
    public int $start = 0;
    
    #[Column('count')]
    public int $count = 500;
    
    #[Column('query')]
    public ?string $query = null;
}

