<?php
return array(
    
    // url format for SPA:  spa@tanant
    array( // SPA main page
        'regex' => '#^/([^/]*)' . Pluf::f('saas_tenant_url_prefix', '@') . '[^/]*$#',
        'model' => 'SaaS_Views_SPA',
        'method' => 'loadSpaByName'
    ),
    // url format for SPA resources:    spa@tenant/path/to/resource
    array( // SPA resource
        'regex' => '#^/([^/]*)' . Pluf::f('saas_tenant_url_prefix', '@') . '[^/]*/(.*)$#',
        'model' => 'SaaS_Views_SPA',
        'method' => 'getResource'
    ),
     
    array( // Default SPA main page of Default Tenant
        'regex' => '#^/$#',
        'model' => 'SaaS_Views_SPA',
        'method' => 'loadDefaultSpa'
    ),
    array( // Default SPA resource
        'regex' => '#^/(.*)$#',
        'model' => 'SaaS_Views_SPA',
        'method' => 'getResourceOfDefault'
    )
)
;