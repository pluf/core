<?php
return array (
        /*
         * کار با صفحه‌ها
         */
        array(
                'regex' => '#^/page/(\d+)$#',
                'model' => 'Wiki_Views_Page',
                'method' => 'get',
                'http-method' => 'GET'
        ),
        array(
                'regex' => '#^/page/(\d+)$#',
                'model' => 'Wiki_Views_Page',
                'method' => 'delete',
                'http-method' => 'DELETE'
        ),
        array(
                'regex' => '#^/page/(\d+)$#',
                'model' => 'Wiki_Views_Page',
                'method' => 'update',
                'http-method' => 'POST'
        ),
        array(
                'regex' => '#^/page/(\d+)/labels$#',
                'model' => 'Wiki_Views_Page',
                'method' => 'labels',
                'http-method' => 'GET'
        ),
        array(
                'regex' => '#^/page/(\d+)/label/(\d+)$#',
                'model' => 'Wiki_Views_Page',
                'method' => 'addLabel',
                'http-method' => 'POST'
        ),
        array(
                'regex' => '#^/page/(\d+)/label/(\d+)$#',
                'model' => 'Wiki_Views_Page',
                'method' => 'removeLabel',
                'http-method' => 'DELETE'
        ),
        array(
                'regex' => '#^/page/create$#',
                'model' => 'Wiki_Views_Page',
                'method' => 'create',
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                ),
                'http-method' => 'POST'
        ),
        array(
                'regex' => '#^/page/find$#',
                'model' => 'Wiki_Views_Page',
                'method' => 'find',
                'http-method' => 'GET'
        ),
        
        
        /*
         * کار با کتابها
         */
        array(
                'regex' => '#^/book/create$#',
                'model' => 'Wiki_Views_Book',
                'method' => 'create',
                'http-method' => 'POST'
        ),
        array(
                'regex' => '#^/book/(\d+)$#',
                'model' => 'Wiki_Views_Book',
                'method' => 'get',
                'http-method' => 'GET'
        ),
        array(
                'regex' => '#^/book/(\d+)$#',
                'model' => 'Wiki_Views_Book',
                'method' => 'update',
                'http-method' => 'POST'
        ),
        array(
                'regex' => '#^/book/(\d+)$#',
                'model' => 'Wiki_Views_Book',
                'method' => 'delete',
                'http-method' => 'DELETE'
        ),
        array(
                'regex' => '#^/book/find$#',
                'model' => 'Wiki_Views_Book',
                'method' => 'find',
                'http-method' => 'GET'
        ),
        
        // جستجو با استفاده عنوان و زبان
        array(
                'regex' => '#^/(.+)/(.+)$#',
                'model' => 'Wiki_Views_Page',
                'method' => 'index',
                'http-method' => 'GET'
        )
);


