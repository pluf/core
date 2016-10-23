<?php
return array (
        /*
         * کار با صفحه‌ها
         */
        array(
                'regex' => '#^/(?P<bookId>\d+)/page/find$#',
                'model' => 'Wiki_Views_Page',
                'method' => 'find',
                'http-method' => 'GET'
        ),
        array(
                'regex' => '#^/(?P<bookId>\d+)/page/new$#',
                'model' => 'Wiki_Views_Page',
                'method' => 'create',
                'precond' => array(
                        'Pluf_Precondition::loginRequired',
                        'Wiki_Precondition::userCanCreatePage'
                ),
                'http-method' => 'POST'
        ),
        array(
                'regex' => '#^/(?P<bookId>\d+)/page/(?P<pageId>\d+)$#',
                'model' => 'Wiki_Views_Page',
                'method' => 'get',
                'http-method' => 'GET'
        ),
        array(
                'regex' => '#^/(?P<bookId>\d+)/page/(?P<pageId>\d+)$#',
                'model' => 'Wiki_Views_Page',
                'method' => 'delete',
                'http-method' => 'DELETE'
        ),
        array(
                'regex' => '#^/(?P<bookId>\d+)/page/(?P<pageId>\d+)$#',
                'model' => 'Wiki_Views_Page',
                'method' => 'update',
                'http-method' => 'POST'
        ),
        
        /*
         * کار با کتابها
         */
        array(
                'regex' => '#^/find$#',
                'model' => 'Wiki_Views_Book',
                'method' => 'find',
                'http-method' => 'GET'
        ),
        array(
                'regex' => '#^/new$#',
                'model' => 'Wiki_Views_Book',
                'method' => 'create',
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                ),
                'http-method' => 'POST'
        ),
        array(
                'regex' => '#^/(?P<bookId>\d+)$#',
                'model' => 'Wiki_Views_Book',
                'method' => 'get',
                'http-method' => 'GET'
        ),
        array(
                'regex' => '#^/(?P<bookId>\d+)$#',
                'model' => 'Wiki_Views_Book',
                'method' => 'update',
                'http-method' => 'POST',
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                )
        ),
        array(
                'regex' => '#^/(?P<bookId>\d+)$#',
                'model' => 'Wiki_Views_Book',
                'method' => 'delete',
                'http-method' => 'DELETE',
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                )
        )
);


