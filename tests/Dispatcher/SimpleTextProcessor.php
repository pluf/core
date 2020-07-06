<?php
namespace Pluf\Test\Dispatcher;

use Pluf\ProcessorAdaptor;
use Pluf\HTTP\Request;
use Pluf\HTTP\Response;

class SimpleTextProcessor extends ProcessorAdaptor
{

    public function response(Request $request, Response $response): Response
    {
        return new Response\PlainText('ok');
    }
}

