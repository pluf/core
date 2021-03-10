<?php
namespace Pluf\Core\Process;

use Pluf\Orm\AssertionTrait;

class HttpBodyToEntity extends HttpBodyToEntities
{
    use AssertionTrait;

    public function __construct(string $class, string $name = "entity")
    {
        parent::__construct($class, false, $name);
    }
}

