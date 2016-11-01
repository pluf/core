<?php
return array (
		
		array ( // Assets urls
				'regex' => '#^/asset/new$#',
				'model' => 'SDP_Views_Asset',
				'method' => 'create',
				'http-method' => 'POST',
				'precond' => array (
						'Pluf_Precondition::loginRequired',
						'SaaS_Precondition::tenantMember' 
				) 
		),
		array (
				'regex' => '#^/asset/find$#',
				'model' => 'SDP_Views_Asset',
				'method' => 'find',
				'http-method' => 'GET' 
		),
		array ( // Asset urls
				'regex' => '#^/asset/(?P<id>\d+)$#',
				'model' => 'SDP_Views_Asset',
				'method' => 'get',
				'http-method' => 'GET' 
		),
		array ( // Asset urls
				'regex' => '#^/asset/(?P<id>\d+)/find$#',
				'model' => 'SDP_Views_Asset',
				'method' => 'findchild',
				'http-method' => 'GET' 
		),
		array ( // Asset urls
				'regex' => '#^/asset/(?P<id>\d+)$#',
				'model' => 'SDP_Views_Asset',
				'method' => 'update',
				'http-method' => 'POST' 
		),
		array ( // Link urls
				'regex' => '#^/(?P<asset_id>\d+)/link$#',
				'model' => 'SDP_Views_Link',
				'method' => 'create',
				'http-method' => 'GET' 
		),
		array ( // Link urls
				'regex' => '#^/link/(?P<id>\d+)$#',
				'model' => 'SDP_Views_Link',
				'method' => 'get',
				'http-method' => 'GET',
				'precond' => array (
						'Pluf_Precondition::loginRequired',
						'SaaS_Precondition::tenantMember' 
				) 
		),
		array ( // Link urls
				'regex' => '#^/link/find$#',
				'model' => 'SDP_Views_Link',
				'method' => 'findlink',
				'http-method' => 'GET',
				'precond' => array (
						'Pluf_Precondition::loginRequired',
						'SaaS_Precondition::tenantMember' 
				) 
		),
		array ( // Download urls
				'regex' => '#^/download/(?P<secure_link>.+)$#',
				'model' => 'SDP_Views_Link',
				'method' => 'download',
				'http-method' => 'GET' 
		),

		array (
				'regex' => '#^/(?P<id>\d+)$#',
				'model' => 'SDP_Views_Asset',
				'method' => 'delete',
				'http-method' => 'DELETE' 
		) 
);
