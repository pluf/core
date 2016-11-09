<?php
return array(
        array(
                'regex' => '#^/find$#',
                'model' => 'Message_Views',
                'method' => 'find',
                'http-method' => 'GET',
                'precond' => array()
        ),
        array(
                'regex' => '#^/(?P<messageId>\d+)$#',
                'model' => 'Message_Views',
                'method' => 'get',
                'http-method' => 'GET',
                'precond' => array()
        ),
        array(
                'regex' => '#^/(?P<messageId>\d+)$#',
                'model' => 'Message_Views',
                'method' => 'delete',
                'http-method' => 'DELETE',
                'precond' => array()
        )
);