<?php
namespace Pluf\Test;

/**
 * Generic PHPUnit exception wrapper for ATK4 repos.
 */
class ExceptionWrapper extends \PHPUnit\Framework\Exception
{

    /** @var \Exception Previous exception */
    public $previous;

    /**
     * Constructor.
     *
     * @param string $message
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct($message = '', $code = 0, \Exception $previous = null)
    {
        $this->previous = $previous;
        parent::__construct($message, $code, $previous);
    }
}
