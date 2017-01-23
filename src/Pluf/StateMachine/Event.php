<?php

class Pluf_StateMachine_Event
{

    var $from;

    var $to;

    var $event;

    var $object;

    /**
     *
     * @var Pluf_HTTP_Request
     */
    var $request;

    /**
     * ماشین حالت منبع را تعیین می‌کند.
     *
     * @var Pluf_StateMachine
     */
    var $source;

    public function __construct ($request, $object, $action, $state, 
            $transaction)
    {
        $this->request = $request;
        $this->object = $object;
        $this->event = $action;
        $this->from = $state['name'];
        $this->to = $transaction['next'];
    }
}