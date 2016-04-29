<?php
return array(
        /*
         * کار با دنبال‌کننده‌ها
         */
        array(
                'regex' => '#^/follower/new$#',
                'model' => 'SaaSNewspaper_Views_Follower',
                'method' => 'create',
                'http-method' => array(
                        'POST'
                )
        ),
        array(
                'regex' => '#^/follower/find$#',
                'model' => 'SaaSNewspaper_Views_Follower',
                'method' => 'find',
                'http-method' => array(
                        'GET'
                )
        ),
       
);