<?php
return array(

        array( // A Tenant main page
                'regex' => '#^/'.Pluf::f('saas_tenant_url_prefix', 'tenant-').'[^/]+$#',
                'model' => 'SaaS_Views_SPA',
                'method' => 'tenant'
        ),
        array( // Application of a Tenant
                'regex' => '#^/'.Pluf::f('saas_tenant_url_prefix', 'tenant-').'[^/]+/([^/]+)$#',
                'model' => 'SaaS_Views_SPA',
                'method' => 'tenantSpa'
        ),
        
        /*
         * نرم افزار پیش فرض
         */
        array ( // Main tenant
                'regex' => '#^/$#',
                'model' => 'SaaS_Views_SPA',
                'method' => 'main'
        ),
        array( // Application of Main Tenant
                'regex' => '#^/([^/]+)$#',
                'model' => 'SaaS_Views_SPA',
                'method' => 'spa'
        ),

        
        
        array( // A SPA AppCache
                'regex' => '#^/saas.appcache/(.+)$#',
                'model' => 'SaaS_Views_SPA',
                'method' => 'appcache'
        ),
        array( // SPAs Assets
                'regex' => '#^/assets/(.*)$#',
                'model' => 'SaaS_Views_SPA',
                'method' => 'assets',
                'precond' => array()
        ),
        array( // A SPA Resources
                'regex' => '#^/([^/]+)/(.*)$#',
                'model' => 'SaaS_Views_SPA',
                'method' => 'source',
                'precond' => array()
        ),
);