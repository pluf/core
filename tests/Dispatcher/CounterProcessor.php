<?php
namespace Pluf\Test\Dispatcher;

use Pluf\ProcessorAdaptor;
use Pluf\HTTP\Request;
use Pluf\HTTP\Response;

/**
 * A test processor to redirect
 *
 * @author maso
 *        
 */
class CounterProcessor extends ProcessorAdaptor
{

    /**
     * Redirects all requests
     *
     * {@inheritdoc}
     * @see \Pluf\Processor::request()
     */
    public function request(Request &$request)
    {
        $counter = $request->counter;
        if (! isset($counter)) {
            $counter = 0;
        }
        $counter ++;
        $request->counter = $counter;
    }

    /**
     * Redirects all response
     *
     * {@inheritdoc}
     * @see \Pluf\Processor::response()
     */
    public function response(Request $request, Response $response): Response
    {
        if ($response->hasBody()) {
            return $response;
        }
        $response->setBody($request->counter);
        return $response;
    }
}

