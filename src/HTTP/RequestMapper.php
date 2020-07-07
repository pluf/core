<?php
namespace Pluf\HTTP;

use Pluf\ObjectMapper;

/**
 * Maps HTTP request to objects
 *
 * Supported mime types:
 *
 *
 * @author maso
 *        
 */
class RequestMapper extends ObjectMapper
{

    private ?Request $request;

    private int $length = 0;

    private int $pointer = 0;

    private array $values = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
        switch ($request->method) {
            case 'GET':
                $this->loadGetParams();
                break;
            case 'POST':
                $this->loadPostParams();
                break;
            case 'PUT':
            case 'HEAD':
            default:
                $this->loadEmptyParams();
                break;
        }
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\ObjectMapper::next()
     */
    public function next($object): Object
    {
        $values = $this->values[$this->pointer];
        return $this->fillObject($object, $values);
    }

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\ObjectMapper::hasMore()
     */
    public function hasMore(): bool
    {
        return $this->pointer < $this->length;
    }

    private function loadGetParams()
    {
        $this->length = 1;
        $this->pointer = 0;
        $this->values = [
            $this->request->REQUEST
        ];
    }

    private function loadPostParams()
    {
        $request = $this->request;
        $type = $request->headers->getHeader('Content-Type');
        switch ($type) {
            /*
             * An associative array of variables passed to the current script
             * via the HTTP POST method.
             *
             * HTTP Content-Type in the request:
             *
             * - application/x-www-form-urlencoded
             * - multipart/form-data
             */
            case 'application/x-www-form-urlencoded':
            case 'multipart/form-data':
                $this->length = 1;
                $this->pointer = 0;
                $this->values = [
                    $request->REQUEST
                ];
                break;
            default:
                $this->loadEmptyParams();
                break;
        }
    }

    private function loadEmptyParams()
    {
        $this->length = 0;
        $this->pointer = 0;
        $this->values = [];
    }
}

