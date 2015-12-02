<?php
return array (
        /*
         * Locations
         */
		array( // جستجوی مکانها
                'regex' => '#^/location/find$#',
                'base' => $base,
                'model' => 'Jayab_Views_Location',
                'method' => 'find',
                'http-method' => 'GET',
                'precond' => array()
        ),
        array( // فهرست مکانها
                'regex' => '#^/location/list$#',
                'base' => $base,
                'model' => 'Jayab_Views_Location',
                'method' => 'locations',
                'http-method' => 'GET',
                'precond' => array(
                        'Pluf_Precondition::loginRequired',
                        'Jayab_Precondition::baseAccess'
                )
        ),
        array( // ایجاد یک مکان جدید
                'regex' => '#^/location/create$#',
                'base' => $base,
                'model' => 'Jayab_Views_Location',
                'method' => 'create',
                'http-method' => 'POST',
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                )
        ),
        array( // لود کردن پرونده‌های جیسان
                'regex' => '#^/location/load/gson$#',
                'base' => $base,
                'model' => 'Jayab_Views_Location',
                'method' => 'loadGsonFile',
                'http-method' => 'POST',
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                )
        ),
        array( // دریافت اطلاعات یک مکان
                'regex' => '#^/location/(\d+)$#',
                'base' => $base,
                'model' => 'Jayab_Views_Location',
                'method' => 'get',
                'http-method' => 'GET',
                'precond' => array()
        ),
        array( // به روز رسانی اطلاعات یک مکان
                'regex' => '#^/location/(\d+)$#',
                'base' => $base,
                'model' => 'Jayab_Views_Location',
                'method' => 'update',
                'http-method' => 'POST',
                'precond' => array(
                        'Pluf_Precondition::loginRequired',
                        'Jayab_Precondition::baseAccess'
                )
        ),
        array( // حذف اطلاعات یک مکان
                'regex' => '#^/location/(\d+)$#',
                'base' => $base,
                'model' => 'Jayab_Views_Location',
                'method' => 'delete',
                'http-method' => 'DELETE',
                'precond' => array(
                        'Pluf_Precondition::loginRequired',
                        'Jayab_Precondition::baseAccess'
                )
        ),
        // تگ‌های یک مکان
        array( // اضافه کردن یک تگ به یک مکان
                'regex' => '#^/location/(\d+)/tag/(\d+)$#',
                'model' => 'Jayab_Views_Location',
                'method' => 'addTag',
                'http-method' => array(
                        'POST',
                        'GET'
                ),
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                )
        ),
        array( // حذف کردن یک تگ به یک مکان
                'regex' => '#^/location/(\d+)/tag/(\d+)$#',
                'model' => 'Jayab_Views_Location',
                'method' => 'deleteTag',
                'http-method' => 'DELETE',
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                )
        ),
        array( // اضافه کردن یک تگ به یک مکان
                'regex' => '#^/location/(\d+)/tag$#',
                'model' => 'Jayab_Views_Location',
                'method' => 'addTagBykeyvalue',
                'http-method' => array(
                        'POST'
                ),
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                )
        ),
        array( // حذف کردن یک تگ به یک مکان
                'regex' => '#^/location/(\d+)/tag$#',
                'model' => 'Jayab_Views_Location',
                'method' => 'deleteTagBykeyvalue',
                'http-method' => array(
                        'DELETE'
                ),
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                )
        ),
        // برچسب‌های یک مکان
        array( // فهرست برچسب‌های یک مکان
                'regex' => '#^/location/(\d+)/label/list$#',
                'model' => 'Jayab_Views_Location',
                'method' => 'labels',
                'http-method' => array(
                        'POST',
                        'GET'
                ),
                'precond' => array()
        ),
        array( // اضافه کردن یک برچسب به یک مکان
                'regex' => '#^/location/(\d+)/label/(\d+)$#',
                'model' => 'Jayab_Views_Location',
                'method' => 'addLabel',
                'http-method' => array(
                        'POST',
                        'GET'
                ),
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                )
        ),
        array( // حذف یک برچسب از یک مکان
                'regex' => '#^/location/(\d+)/label/(\d+)$#',
                'model' => 'Jayab_Views_Location',
                'method' => 'deleteLabel',
                'http-method' => 'DELETE',
                'precond' => array(
                        'Pluf_Precondition::loginRequired'
                )
        ),
        // رای به مکان‌ها
        array( // گرفتن اطلاعات رای به یک مکان
                'regex' => '#^/location/(\d+)/vote$#',
                'base' => $base,
                'model' => 'Jayab_Views_Location',
                'method' => 'getVote',
                'http-method' => 'GET',
                'precond' => array(
                        'Pluf_Precondition::loginRequired',
                        'Jayab_Precondition::baseAccess'
                )
        ),
        array( // به روز کردن و یا ایجاد رای به یک مکان
                'regex' => '#^/location/(\d+)/vote$#',
                'base' => $base,
                'model' => 'Jayab_Views_Location',
                'method' => 'updateVote',
                'http-method' => 'POST',
                'precond' => array(
                        'Pluf_Precondition::loginRequired',
                        'Jayab_Precondition::baseAccess'
                )
        ),
        array( // حذف رای کاربر از یک مکان
                'regex' => '#^/location/(\d+)/vote$#',
                'base' => $base,
                'model' => 'Jayab_Views_Location',
                'method' => 'deleteVote',
                'http-method' => 'DELETE',
                'precond' => array(
                        'Pluf_Precondition::loginRequired',
                        'Jayab_Precondition::baseAccess'
                )
        ),
        array( // فهرست تمام ارا به یک مکان
                'regex' => '#^/location/(\d+)/votes$#',
                'base' => $base,
                'model' => 'Jayab_Views_Location',
                'method' => 'votes',
                'http-method' => 'GET',
                'precond' => array(
                        'Pluf_Precondition::loginRequired',
                        'Jayab_Precondition::baseAccess'
                )
        ),
        /*
         * Tag
         * 
         * 
         */
        array(
                'regex' => '#^/tag/find$#',
                'base' => $base,
                'model' => 'Jayab_Views_Tag',
                'method' => 'find',
                'http-method' => 'GET',
                'precond' => array()
        ),
        array(
                'regex' => '#^/tag/(\d+)$#',
                'base' => $base,
                'model' => 'Jayab_Views_Tag',
                'method' => 'get',
                'http-method' => 'GET',
                'precond' => array()
        ),
        array(
                'regex' => '#^/tag$#',
                'base' => $base,
                'model' => 'Jayab_Views_Tag',
                'method' => 'getByTag',
                'http-method' => 'GET',
                'precond' => array()
        ),
        array( 
                'regex' => '#^/tag/create$#',
                'base' => $base,
                'model' => 'Jayab_Views_Tag',
                'method' => 'create',
                'http-method' => 'POST',
                'precond' => array(
                        'Pluf_Precondition::staffRequired',
                )
        ),
        array( 
                'regex' => '#^/tag/(\d+)$#',
                'base' => $base,
                'model' => 'Jayab_Views_Tag',
                'method' => 'delete',
                'http-method' => 'DELETE',
                'precond' => array(
                        'Pluf_Precondition::staffRequired',
                )
        ),
        
        
        /*
         * Vote view layer
         */
		array( // فهرست تمام ارا
                'regex' => '#^/vote/list$#',
                'base' => $base,
                'model' => 'Jayab_Views_Vote',
                'method' => 'votes',
                'http-method' => 'GET'
        ),
        array( // دریافت اطلاعات یک رای
                'regex' => '#^/vote/(\d+)$#',
                'base' => $base,
                'model' => 'Jayab_Views_Vote',
                'method' => 'get',
                'http-method' => 'GET'
        )
);