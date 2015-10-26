<?php
return array(
        /*
         * کار با برچسب‌ها
         */
        array(
                'regex' => '#^/label/find$#',
                'model' => 'KM_Views_Label',
                'method' => 'find',
                'http-method' => array(
                        'GET'
                )
        ),
        array(
                'regex' => '#^/label/create$#',
                'model' => 'KM_Views_Label',
                'method' => 'create',
                'http-method' => array(
                        'POST'
                )
        ),
        array(
                'regex' => '#^/label/(\d+)$#',
                'model' => 'KM_Views_Label',
                'method' => 'get',
                'http-method' => array(
                        'GET'
                )
        ),
        array(
                'regex' => '#^/label/(\d+)$#',
                'model' => 'KM_Views_Label',
                'method' => 'update',
                'http-method' => array(
                        'POST'
                )
        ),
        array(
                'regex' => '#^/label/(\d+)$#',
                'model' => 'KM_Views_Label',
                'method' => 'delete',
                'http-method' => array(
                        'DELETE'
                )
        ),
        /*
         * کار با دسته‌ها
         */
        array(
                'regex' => '#^/category/list$#',
                'model' => 'KM_Views_Category',
                'method' => 'categories'
        ),
        array(
                'regex' => '#^/category/create$#',
                'model' => 'KM_Views_Category',
                'method' => 'create'
        ),
        array(
                'regex' => '#^/category/(\d+)$#',
                'model' => 'KM_Views_Category',
                'method' => 'category'
        )
);