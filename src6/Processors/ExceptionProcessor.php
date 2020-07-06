<?php
namespace Pluf\Processors;

use Pluf\ProcessorAdaptor;
use Pluf\HTTP\Request;
use Pluf\HTTP\Response;

/**
 * Process the response body and convert to a readable message
 *
 * @author maso
 *        
 */
class ExceptionProcessor extends ProcessorAdaptor
{

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\ProcessorAdaptor::response()
     */
    public function response(Request $request, Response $response): Response
    {
        if ($response->isOk()) {
            return $response;
        }
        $body = $response->getBody();
        // XXX: maso, 2020: convert body exception into a serializable object
        // XXX: maso, 2020: check response header (status code)
        return $response->setBody($body);
    }
}

