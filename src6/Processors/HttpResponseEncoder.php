<?php
namespace Pluf\Processors;

use Pluf\ProcessorAdaptor;
use Pluf\HTTP\Request;
use Pluf\HTTP\Response;

/**
 * Render response as HTTP Resutl
 *
 * @author maso
 *        
 */
class HttpResponseEncoder extends ProcessorAdaptor
{

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Processor::response()
     */
    public function response(Request $request, Response $response)
    {
        // convert to response
        $http = new \Pluf\HTTP2();
        $contentType = array(
            'application/json',
            'text/plain'
        );
        $mime = $http->negotiateMimeType($contentType, $contentType[0]);
        if ($mime === false) {
            throw new \Pluf\Exception("You don't want any of the content types I have to offer\n");
        }
        switch ($mime) {
            case 'application/json':
                $response = new Response\Json($response);
                break;
            case 'text/plain':
                $response = new Response\PlainText($response);
                break;
        }

        return $response;
    }
}

