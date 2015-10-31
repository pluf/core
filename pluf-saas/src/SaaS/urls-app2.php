<?php
return array(
        array( // مانیفست نرم‌افزارها
                'regex' => '#^/saas.appcache$#',
                'model' => 'SaaS_Views_SAP',
                'method' => 'appcache'
        ),
        array( // صفحه اصلی نرم‌افزار
                'regex' => '#^/(\d+)$#',
                'model' => 'SaaS_Views_SAP',
                'method' => 'sap'
        )
);