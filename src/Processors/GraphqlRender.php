<?php
namespace Pluf\Processors;

use Pluf\ProcessorAdaptor;
use Pluf\HTTP\Request;
use Pluf\HTTP\Response;
use Pluf_Graphql;

/**
 * Processes the response body and performe the GraphQL on it.
 *
 * Note: the body must be set.
 *
 * @author maso
 *        
 */
class GraphqlRender extends ProcessorAdaptor
{

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Processor::response()
     */
    public function response(Request $request, Response $response)
    {
        // apply graphql
        if (array_key_exists('graphql', $request->REQUEST)) {
            $gl = new Pluf_Graphql();
            $newBody = $gl->render($response->getBody(), $request->REQUEST['graphql']);
            $response->setBody($newBody);
        }
        return $response;
    }
}

