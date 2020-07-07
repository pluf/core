<?php
namespace Pluf\ObjectMapper;

use Pluf\HTTP\Request;

/**
 * Maps HTTP request to objects
 *
 * Supported mime types:
 *
 *
 * @author maso
 *        
 */
class RequestMapper extends ArrayMapper
{

    private ?Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $values = [];
        switch ($request->method) {
            case 'GET':
                $values = $this->loadGetParams();
                break;
            case 'POST':
                $values = $this->loadPostParams();
                break;
            case 'PUT':
            case 'HEAD':
            default:
                $values = $this->loadEmptyParams();
                break;
        }
        parent::__construct($values);
    }

    private function loadGetParams()
    {
        return [
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
                return [
                    $request->REQUEST
                ];
                break;
            default:
                return $this->loadEmptyParams();
                break;
        }
    }

    private function loadEmptyParams()
    {
        return [];
    }
}

