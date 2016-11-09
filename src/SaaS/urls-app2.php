<?php
return array(
    
    // Url for sitemap
    array(
        'regex' => '#^/sitemap.xml$#',
        'model' => 'SaaS_Views_Application',
        'method' => 'getSiteMap'
    ),
    // url format for SPA main page or a Resource-File of default spa
    // main page of a spa:               /spa-name
    // resource-file from default spa:   /resource-file
    array(
        'regex' => '#^/(?P<path>[^/]+)$#',
        'model' => 'SaaS_Views_Run',
        'method' => 'loadSpaOrResource'
    ),
    // url format for SPA resources:    
    // resource from default spa: /path/to/resource
    // resource form specified spa: /spa-name/path/to/resource
    array(
        'regex' => '#^/(?P<spa>[^/]+)/(?P<resource>.*)$#',
        'model' => 'SaaS_Views_Run',
        'method' => 'getResource'
    ),    
    // main page of default SPA
    array(
        'regex' => '#^/$#',
        'model' => 'SaaS_Views_Run',
        'method' => 'defaultSpa'
    )
);