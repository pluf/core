<?php
namespace Pluf\Core\Exception;

use Throwable;

class ResourceNotFoundException extends \Pluf\Core\Exception
{

    public function __construct($message = '', ?int $code = null, ?Throwable $previous = null, ?int $status = 404, ?array $params = [], ?array $solutions = [])
    {
        parent::__construct($message, $code, $previous, $status, $params, $solutions);
    }
}

