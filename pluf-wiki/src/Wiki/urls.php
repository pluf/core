<?php
return array (
        /*
         * کار با صفحه‌ها
         */
        array (
                'regex' => '#^/page/(\d+)$#',
                'model' => 'Wiki_Views_Page',
                'method' => 'get',
                'http-method' => 'GET'
        ),
        array (
                'regex' => '#^/page/(\d+)$#',
                'model' => 'Wiki_Views_Page',
                'method' => 'delete',
                'http-method' => 'DELETE'
        ),
        array (
                'regex' => '#^/page/(\d+)$#',
                'model' => 'Wiki_Views_Page',
                'method' => 'update',
                'http-method' => 'POST'
        ),
        array (
                'regex' => '#^/page/create$#',
                'model' => 'Wiki_Views_Page',
                'method' => 'create',
                'http-method' => 'POST'
        ),
        array (
                'regex' => '#^/page/list$#',
                'model' => 'Wiki_Views_Page',
                'method' => 'find',
                'http-method' => 'GET'
        ),
        // جستجو با استفاده عنوان و زبان
		array (
				'regex' => '#^/(.+)/(.+)$#',
				'model' => 'Wiki_Views_Page',
				'method' => 'index',
		        'http-method' => 'GET'
		),
);


