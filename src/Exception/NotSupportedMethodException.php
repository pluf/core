<?php
namespace Pluf\Core\Exception;

use Pluf\Orm\Attribute\Entity;
use Pluf\Orm\Attribute\Column;
use Pluf\Orm\Attribute\Transients;
use Throwable;

#[Entity]
#[Transients(["line", "file", "string", "trace", "previous"])]
class NotSupportedMethodException extends \Pluf\Core\Exception
{
}

