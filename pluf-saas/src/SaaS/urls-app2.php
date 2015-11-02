<?php
return array(
        array( // مانیفست نرم‌افزارها
                'regex' => '#^/(\d+)/saas.appcache$#',
                'model' => 'SaaS_Views_SAP',
                'method' => 'appcache'
        ),
        array( // صفحه اصلی نرم‌افزار
                'regex' => '#^/(\d+)$#',
                'model' => 'SaaS_Views_SAP',
                'method' => 'sap'
        ),
		array (//  صفحه اصلی سیستم
				'regex' => '#^/$#',
				'model' => 'SaaS_Views_SAP',
				'method' => 'sap'
		),
);