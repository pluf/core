<?php
return array(
        array( // مانیفست نرم‌افزارها
                'regex' => '#^/(\d+)/saas.appcache$#',
                'model' => 'SaaS_Views_SPA',
                'method' => 'appcache'
        ),
        array( // صفحه اصلی نرم‌افزار
                'regex' => '#^/(\d+)$#',
                'model' => 'SaaS_Views_SPA',
                'method' => 'spa'
        ),
		array (//  صفحه اصلی سیستم
				'regex' => '#^/$#',
				'model' => 'SaaS_Views_SPA',
				'method' => 'spa'
		),
);