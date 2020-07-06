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
class RedirectProcessor extends ProcessorAdaptor
{

    /**
     * Redirects all response
     *
     * {@inheritdoc}
     * @see \Pluf\Processor::response()
     */
    public function response(Request $request, Response $response): Response
    {
        return new Response\Redirect('/login');
    }
}

