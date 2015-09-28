<?php
return array (
        /*
         * System messages
         */
		array( // پیامها را دریافت کرده و آنها را حذف می‌کند.
                'regex' => '#^/system/list$#',
                'model' => 'Inbox_Views_System',
                'method' => 'messages',
                'http-method' => 'GET'
        ),
        array( // یک پیام تست ایجاد می‌کند.
                'regex' => '#^/system/test$#',
                'model' => 'Inbox_Views_System',
                'method' => 'testMessage',
                'http-method' => array(
                        'GET',
                        'POST'
                )
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