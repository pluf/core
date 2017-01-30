<?php
return array (
        /*
         * کار با صفحه‌ها
         */
        array(
                'regex' => '#^/(?P<bookId>\d+)/page/find$#',
                'model' => 'Book_Views_Page',
                'method' => 'find',
                'http-method' => 'GET'
        ),
        array(
                'regex' => '#^/(?P<bookId>\d+)/page/new$#',
                'model' => 'Book_Views_Page',
                'method' => 'create',
                'precond' => array(
                        'Pluf_Precondition::loginRequired',
                        'Book_Precondition::userCanCreatePage'
                ),
                'http-method' => 'POST'
        ),
        array(
                'regex' => '#^/(?P<bookId>\d+)/page/(?P<pageId>\d+)$#',
                'model' => 'Book_Views_Page',
                'method' => 'get',
                'http-method' => 'GET'
        ),
        array(
                'regex' => '#^/(?P<bookId>\d+)/page/(?P<pageId>\d+)$#',
                'model' => 'Book_Views_Page',
                'method' => 'delete',
                'http-method' => 'DELETE'
        ),
        array(
                'regex' => '#^/(?P<bookId>\d+)/page/(?P<pageId>\d+)$#',
                'model' => 'Book_Views_Page',
                'method' => 'update',
                'http-method' => 'POST'
        ),
        
        /*
         * کار با کتابها
         */
        array(
                'regex' => '#^/find$#',
                'model' => 'Book_Views',
                'method' => 'find',
                'http-method' => 'GET'
        ),
        array(
                'regex' => '#^/new$#',
                'model' => 'Book_Views',
                'method' => 'create',
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                ),
                'http-method' => 'POST'
        ),
        array(
                'regex' => '#^/(?P<bookId>\d+)$#',
                'model' => 'Book_Views',
                'method' => 'get',
                'http-method' => 'GET'
        ),
        array(
                'regex' => '#^/(?P<bookId>\d+)$#',
                'model' => 'Book_Views',
                'method' => 'update',
                'http-method' => 'POST',
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                )
        ),
        array(
                'regex' => '#^/(?P<bookId>\d+)$#',
                'model' => 'Book_Views',
                'method' => 'delete',
                'http-method' => 'DELETE',
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                )
        )
);


