<?php
return array(
		
		array( // SPA main page
				'regex' => '#^/([^/]*)'.Pluf::f('saas_tenant_url_prefix', '@').'[^/]*$#',
				'model' => 'SaaS_Views_SPA',
				'method' => ''
		),
		array( // SPA resource
				'regex' => '#^/([^/]*)'.Pluf::f('saas_tenant_url_prefix', '@').'[^/]*/(.*)$#',
				'model' => 'SaaS_Views_SPA',
				'method' => ''
		),
		array( // Default SPA
				'regex' => '#^/$#',
				'model' => 'SaaS_Views_SPA',
				'method' => ''
		),
		array( // Default SPA resource
				'regex' => '#^/(.*)$#',
				'model' => 'SaaS_Views_SPA',
				'method' => ''
		),
		
);