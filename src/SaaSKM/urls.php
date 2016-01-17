<?php
return array(
        /*
         * کار با تگ
         */
        array(
                'regex' => '#^/tag/find$#',
                'model' => 'SaaSKM_Views_Tag',
                'method' => 'find',
                'http-method' => array(
                        'GET'
                )
        ),
        array(
                'regex' => '#^/tag/create$#',
                'model' => 'SaaSKM_Views_Tag',
                'method' => 'create',
                'precond' => array(
                        'SaaSKM_Precondition::userCanCreateTag'
                ),
                'http-method' => array(
                        'POST'
                )
        ),
        array(
                'regex' => '#^/tag/bulkyCreate$#',
                'model' => 'SaaSKM_Views_TagBulky',
                'method' => 'create',
                'precond' => array(
                        'SaaSKM_Precondition::userCanCreateTag'
                ),
                'http-method' => array(
                        'POST'
                )
        ),
        array(
                'regex' => '#^/tag/(\d+)$#',
                'model' => 'SaaSKM_Views_Tag',
                'method' => 'get',
                'http-method' => array(
                        'GET'
                )
        ),
        array(
                'regex' => '#^/tag/(\d+)$#',
                'model' => 'SaaSKM_Views_Tag',
                'method' => 'update',
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                ),
                'http-method' => array(
                        'POST'
                )
        ),
        array(
                'regex' => '#^/tag/(\d+)$#',
                'model' => 'SaaSKM_Views_Tag',
                'method' => 'delete',
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                ),
                'http-method' => array(
                        'DELETE'
                )
        ),
       
);