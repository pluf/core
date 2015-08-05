<?php
return array (
        /*
         * System messages
         */
		array(
                'regex' => '#^/system/list$#',
                'model' => 'Inbox_Views_System',
                'method' => 'messages',
                'http-method' => 'GET'
        ),
        /*
         * Inbox
         */
		array(
                'regex' => '#^/inbox$#',
                'model' => 'User_Views_Authentication',
                'method' => 'login'
        )
);