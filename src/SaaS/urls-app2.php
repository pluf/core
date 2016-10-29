<?php
return array(
    
    // Url for sitemap
    array(
        'regex' => '#^/sitemap.xml$#',
        'model' => 'SaaS_Views_Application',
        'method' => 'getSiteMap'
    ),
    // url format for SPA:  /spa
    array( // SPA main page
        'regex' => '#^/(?P<spa>[^/]+)$#',
        'model' => 'SaaS_Views_SPA',
        'method' => 'loadSpaByName'
    ),
    // url format for SPA resources:    /spa/path/to/resource
    array( // SPA resource
        'regex' => '#^/(?P<spa>[^/]+)/(?P<resource>.*)$#',
        'model' => 'SaaS_Views_SPA',
        'method' => 'getResource'
    ),
     
    array( // Default SPA main page of Default Tenant
        'regex' => '#^/$#',
        'model' => 'SaaS_Views_SPA',
        'method' => 'loadDefaultSpa'
    ),
    array( // Default SPA resource
        'regex' => '#^/(?P<resource>.*)$#',
        'model' => 'SaaS_Views_SPA',
        'method' => 'getResourceOfDefault'
    )
)
;