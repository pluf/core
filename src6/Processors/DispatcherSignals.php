<?php
namespace Pluf\Processors;

use Pluf\ProcessorAdaptor;
use Pluf\Signal;
use Pluf\HTTP\Request;
use Pluf\HTTP\Response;

class DispatcherSignals extends ProcessorAdaptor
{

    /**
     * Process the request
     *
     * @param Request $request
     * @return boolean false if ther is no problem otherwize ther is an error
     */
    public function request(Request &$request)
    {}

    /**
     * Process the response
     *
     * @param Request $request
     * @param Response $response
     */
    public function response(Request $request, Response $response): Response
    {
        /**
         * [signal]
         *
         * Pluf_Dispatcher::postDispatch
         *
         * [sender]
         *
         * Pluf_Dispatcher
         *
         * [description]
         *
         * This signal is sent after the rendering of a request. This
         * means you cannot affect the response but you can use this
         * hook to do some cleaning.
         *
         * [parameters]
         *
         * array('request' => $request,
         * 'response' => $response)
         */
        Signal::send('Pluf_Dispatcher::postDispatch', 'Pluf_Dispatcher', [
            'request' => $request,
            'response' => $response
        ]);
        return $response;
    }
}

