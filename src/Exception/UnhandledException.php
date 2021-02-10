<?php
namespace Pluf\Core\Exception;

use Throwable;

class UnhandledException extends \Pluf\Core\Exception
{
    
    public function __construct($message = '', ?int $code = null, ?Throwable $previous = null, ?int $status = 500, ?array $params = [], ?array $solutions = [])
    {
        parent::__construct($message, $code, $previous, $status, $params, $solutions);
    }
}

