<?php
namespace Pluf;

use Throwable;

/**
 * An exception builder
 *
 * @author maso
 *        
 */
class ExceptionBuilder
{

    private ?string $message;

    private array $solutions = [];

    private array $params = [];

    private ?int $code = 0;

    private ?int $status = 0;

    private ?Throwable $previous;

    /**
     *
     * @param mixed $message
     */
    public function setMessage($message): self
    {
        $this->message = $message;
        return $this;
    }

    /**
     *
     * @param multitype: $solutions
     */
    public function setSolutions($solutions): self
    {
        $this->solutions = $solutions;
        return $this;
    }

    /**
     * Adds new solution
     *
     * @param mixed $solution
     * @return self
     */
    public function addSolution($solution): self
    {
        $this->solutions[] = $solution;
        return $this;
    }

    /**
     *
     * @param multitype: $params
     */
    public function setParams($params): self
    {
        $this->params = $params;
        return $this;
    }

    /**
     *
     * @param multitype: $params
     */
    public function setParam($key, $param): self
    {
        $this->params[$key] = $param;
        return $this;
    }

    /**
     *
     * @param number $status
     */
    public function setStatus($status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     *
     * @param number $code
     */
    public function setCode($code): self
    {
        $this->code = $code;
        return $this;
    }

    /**
     *
     * @param mixed $previous
     */
    public function setPrevious($previous): self
    {
        $this->previous = $previous;
        return $this;
    }

    /**
     * Build a new instance of exception.
     *
     * @return Exception
     */
    public function build(): Exception
    {
        $ex = new Exception($this->message, $this->code, $this->previous, $this->params, $this->solutions);
        return $ex;
    }
}

