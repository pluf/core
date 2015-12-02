<?php
return array (
        array( // یک واحد را به عنوان پیش فرض تعیین می‌کند
                'regex' => '#^/feedback$#',
                'model' => 'HM_Views_System',
                'method' => 'createFeedback',
                'http-method' => 'POST',
                'freemium' => array(
                        'level' => 0
                )
        ),
        /* واحدها*/
        array( // یک واحد را به عنوان پیش فرض تعیین می‌کند
                'regex' => '#^/part/active/(\d+)$#',
                'model' => 'HM_Views_Part',
                'method' => 'setActive',
                'freemium' => array(
                        'level' => 0
                )
        ),
        array( // یک واحد را به عنوان پیش فرض تعیین می‌کند
                'regex' => '#^/part/active$#',
                'model' => 'HM_Views_Part',
                'method' => 'getActive',
                'freemium' => array(
                        'level' => 0
                )
        ),
        array( // فهرستی از واحدهای آپارتمان تعیین می‌شود
                'regex' => '#^/(\d+)/part/list$#',
                'model' => 'HM_Views_Part',
                'method' => 'parts',
                'saas' => array(
                        'match-application' => 1
                ),
                'freemium' => array(
                        'level' => 0
                )
        ),
        array( // ایجاد یک واحد جدید
                'regex' => '#^/(\d+)/part$#',
                'model' => 'HM_Views_Part',
                'method' => 'create',
                'saas' => array(
                        'match-application' => 1
                ),
                'freemium' => array(
                        'level' => 0
                )
        ),
        array( // ویرایش اطلاعات یک واحد
                'regex' => '#^/(\d+)/part/(\d+)$#',
                'model' => 'HM_Views_Part',
                'method' => 'part',
                'saas' => array(
                        'match-application' => 1
                ),
                'freemium' => array(
                        'level' => 0
                )
        ),
		/* پیام‌ها */
		array( // فهرستی از پیام های عمومی یک ساختمان را تعیین می‌کند
                'regex' => '#^/(\d+)/message/list$#',
                'model' => 'HM_Views_Message',
                'method' => 'messages',
                'http-method' => 'GET',
                'saas' => array(
                        'match-application' => 1
                ),
                'freemium' => array(
                        'level' => 0
                )
        ),
        array( // یک پیام را ایجاد می‌کند
                'regex' => '#^/(\d+)/message/create$#',
                'model' => 'HM_Views_Message',
                'method' => 'create',
                'http-method' => 'POST',
                'saas' => array(
                        'match-application' => 1
                ),
                'freemium' => array(
                        'level' => 0
                )
        ),
        array( // گرفتن جزئیات یک پیام
                'regex' => '#^/(\d+)/message/(\d+)$#',
                'model' => 'HM_Views_Message',
                'method' => 'get',
                'http-method' => 'GET',
                'saas' => array(
                        'match-application' => 1
                ),
                'freemium' => array(
                        'level' => 0
                )
        ),
        array( // یک پیام را به روز می‌کند
                'regex' => '#^/(\d+)/message/(\d+)$#',
                'model' => 'HM_Views_Message',
                'method' => 'update',
                'http-method' => 'POST',
                'saas' => array(
                        'match-application' => 1
                ),
                'freemium' => array(
                        'level' => 0
                )
        ),
        array( // یک پیام را حذف می‌کند
                'regex' => '#^/(\d+)/message/(\d+)$#',
                'model' => 'HM_Views_Message',
                'method' => 'delete',
                'http-method' => 'DELETE',
                'saas' => array(
                        'match-application' => 1
                ),
                'freemium' => array(
                        'level' => 0
                )
        ),
		/* پرداخت‌ها */
        array( // دریافت اطلاعات یک پرداخت
                'regex' => '#^/payment/(\d+)$#',
                'model' => 'HM_Views_Payment',
                'method' => 'get',
                'http-method' => 'GET',
                'freemium' => array(
                        'level' => 0
                )
        ),
        array( // به روز کردن یک پرداخت
                'regex' => '#^/payment/(\d+)$#',
                'model' => 'HM_Views_Payment',
                'method' => 'update',
                'http-method' => 'POST',
                'freemium' => array(
                        'level' => 0
                )
        ),
        array( // حذف یک پرداخت
                'regex' => '#^/payment/(\d+)$#',
                'model' => 'HM_Views_Payment',
                'method' => 'delete',
                'http-method' => 'DELETE',
                'freemium' => array(
                        'level' => 0
                )
        ),
        array( // فهرست پرداخت‌های یک واحد
                'regex' => '#^/(\d+)/part/(\d+)/payment/list$#',
                'model' => 'HM_Views_Part',
                'method' => 'payments',
                'saas' => array(
                        'match-application' => 1
                ),
                'freemium' => array(
                        'level' => 0
                )
        ),
        array( // ایجاد پرداخت برای واحد
                'regex' => '#^/apartment/part/(\d+)/payment/create$#',
                'model' => 'HM_Views_Part',
                'method' => 'createPayment',
                'http-method' => 'POST',
                'freemium' => array(
                        'level' => 0
                )
        ),
        array( // فهرست پرداخت‌ها برای یک آپارتمان
                'regex' => '#^/(\d+)/payment/list$#',
                'model' => 'HM_Views_Application',
                'method' => 'payments',
                'http-method' => 'GET',
                'saas' => array(
                        'match-application' => 1
                ),
                'freemium' => array(
                        'level' => 0
                )
        ),
        array( // ایجاد پرداخت برای کل آپارتمان
                'regex' => '#^/(\d+)/payment$#',
                'model' => 'HM_Views_Application',
                'method' => 'createPayment',
                'http-method' => 'POST',
                'saas' => array(
                        'match-application' => 1
                ),
                'freemium' => array(
                        'level' => 0
                )
        ),
        /*
         * گزارش‌ها
         */
        array( // ایجاد پرداخت برای کل آپارتمان
                'regex' => '#^/report/part/(.+)/correlation$#',
                'model' => 'HM_Views_Report',
                'method' => 'partCorrelation',
                'http-method' => 'GET',
                'freemium' => array(
                        'level' => 0
                )
        ),
        
        
        
);
