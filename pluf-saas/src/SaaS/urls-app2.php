<?php
return array(
        array( // مانیفست نرم‌افزارها
                'regex' => '#^/saas.appcache$#',
                'model' => 'SaaS_Views',
                'method' => 'appcache'
        ),
        array( // صفحه اصلی نرم‌افزار
                'regex' => '#^/(\d+)$#',
                'model' => 'SaaS_Views',
                'method' => 'sap'
        ),
        array( // سایر نرم‌افزار
                'regex' => '#^/(\d+)/(.+)$#',
                'model' => 'SaaS_Views',
                'method' => 'applicationPage'
        ),
        array( // صفحه اصلی سیستم
                'regex' => '#^/$#',
                'model' => 'SaaS_Views',
                'method' => 'index'
        )
);