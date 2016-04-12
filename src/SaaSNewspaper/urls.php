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
       
);