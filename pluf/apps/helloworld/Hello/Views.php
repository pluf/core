<?php
class Hello_Views
{
    public function hello($request, $match)
    {
        return new Pluf_HTTP_Response('Hello World!');
    }
}
