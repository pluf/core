<?php
namespace Pluf\HelloWord\Processors;

use Pluf\ProcessorAdaptor;
use Pluf\HTTP\Request;
use Pluf\HTTP\Response;

class HelloWordProcessor extends ProcessorAdaptor
{

    public function response(Request $request, Response $response): Response
    {
        $response->setBody('Hello Word');
        return $response;
    }
}

