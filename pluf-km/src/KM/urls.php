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
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                ),
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
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                ),
                'http-method' => array(
                        'POST'
                )
        ),
        array(
                'regex' => '#^/label/(\d+)$#',
                'model' => 'KM_Views_Label',
                'method' => 'delete',
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                ),
                'http-method' => array(
                        'DELETE'
                )
        ),
        /*
         * کار با دسته‌ها
         */
        array(
                'regex' => '#^/category/find$#',
                'model' => 'KM_Views_Category',
                'method' => 'find',
                'http-method' => array(
                        'GET'
                )
        ),
        array(
                'regex' => '#^/category/create$#',
                'model' => 'KM_Views_Category',
                'method' => 'create',
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                ),
                'http-method' => array(
                        'POST'
                )
        ),
        array(
                'regex' => '#^/category/(\d+)/create$#',
                'model' => 'KM_Views_Category',
                'method' => 'createSubCategory',
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                ),
                'http-method' => array(
                        'POST'
                ),
        ),
        
        
        array(
                'regex' => '#^/category/root$#',
                'model' => 'KM_Views_Category',
                'method' => 'root',
                'http-method' => array(
                        'GET'
                )
        ),
        array(
                'regex' => '#^/category/(\d+)$#',
                'model' => 'KM_Views_Category',
                'method' => 'get',
                'http-method' => array(
                        'GET'
                )
        ),
        array(
                'regex' => '#^/category/(\d+)/children$#',
                'model' => 'KM_Views_Category',
                'method' => 'children',
                'http-method' => array(
                        'GET'
                )
        )
);