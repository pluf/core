<?php
return array (
        /*
         * کار با صفحه‌ها
         */
        array(
                'regex' => '#^/page/find$#',
                'model' => 'Wiki_Views_Page',
                'method' => 'find',
                'http-method' => 'GET'
        ),
        array(
                'regex' => '#^/page/create$#',
                'model' => 'Wiki_Views_Page',
                'method' => 'create',
                'precond' => array(
                        'Pluf_Precondition::loginRequired',
                        'Wiki_Precondition::userCanCreatePage'
                ),
                'http-method' => 'POST'
        ),
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
        array( // category
                'regex' => '#^/page/(\d+)/categories$#',
                'model' => 'Wiki_Views_Page',
                'method' => 'categories',
                'http-method' => 'GET'
        ),
        array(
                'regex' => '#^/page/(\d+)/category/(\d+)$#',
                'model' => 'Wiki_Views_Page',
                'method' => 'addCategory',
                'http-method' => 'POST'
        ),
        array(
                'regex' => '#^/page/(\d+)/category/(\d+)$#',
                'model' => 'Wiki_Views_Page',
                'method' => 'removeCategory',
                'http-method' => 'DELETE'
        ),
        
        
        /*
         * کار با کتابها
         */
        array(
                'regex' => '#^/book/find$#',
                'model' => 'Wiki_Views_Book',
                'method' => 'find',
                'http-method' => 'GET'
        ),
        array(
                'regex' => '#^/book/create$#',
                'model' => 'Wiki_Views_Book',
                'method' => 'create',
                'precond' => array(
                        'Pluf_Precondition::loginRequired',
                        'Wiki_Precondition::userCanCreateBook'
                ),
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
                'regex' => '#^/book/(\d+)/labels$#',
                'model' => 'Wiki_Views_Book',
                'method' => 'labels',
                'http-method' => 'GET'
        ),
        array(
                'regex' => '#^/book/(\d+)/label/(\d+)$#',
                'model' => 'Wiki_Views_Book',
                'method' => 'addLabel',
                'http-method' => 'POST'
        ),
        array(
                'regex' => '#^/book/(\d+)/label/(\d+)$#',
                'model' => 'Wiki_Views_Book',
                'method' => 'removeLabel',
                'http-method' => 'DELETE'
        ),
        array( // category
                'regex' => '#^/book/(\d+)/categories$#',
                'model' => 'Wiki_Views_Book',
                'method' => 'categories',
                'http-method' => 'GET'
        ),
        array(
                'regex' => '#^/book/(\d+)/category/(\d+)$#',
                'model' => 'Wiki_Views_Book',
                'method' => 'addCategory',
                'http-method' => 'POST'
        ),
        array(
                'regex' => '#^/book/(\d+)/category/(\d+)$#',
                'model' => 'Wiki_Views_Book',
                'method' => 'removeCategory',
                'http-method' => 'DELETE'
        ),
        array( // Book pages
                'regex' => '#^/book/(\d+)/pages$#',
                'model' => 'Wiki_Views_Book',
                'method' => 'pages',
                'http-method' => 'GET'
        ),
        array(
                'regex' => '#^/book/(\d+)/page/(\d+)$#',
                'model' => 'Wiki_Views_Book',
                'method' => 'addPage',
                'http-method' => 'POST'
        ),
        array(
                'regex' => '#^/book/(\d+)/page/(\d+)$#',
                'model' => 'Wiki_Views_Book',
                'method' => 'removePage',
                'http-method' => 'DELETE'
        ),
        
        // جستجو با استفاده عنوان و زبان
        array(
                'regex' => '#^/(.+)/(.+)$#',
                'model' => 'Wiki_Views_Page',
                'method' => 'index',
                'http-method' => 'GET'
        )
);


