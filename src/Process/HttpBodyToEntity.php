<?php
namespace Pluf\Core\Process;

use Pluf\Orm\AssertionTrait;

class HttpBodyToEntity extends HttpBodyToEntities
{
    use AssertionTrait;

    private string $class;

    private bool $multi;

    private string $name = "entities";

    public function __construct(string $class, string $name = "entity")
    {
        parent::__construct($class, false, $name);
    }
}

