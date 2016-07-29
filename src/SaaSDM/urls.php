	<?php
return array (
		
		array ( // Content urls
				'regex' => '#^/link/(secure_link:.*)$#',
				'model' => 'SaaSDM_Views_Link',
				'method' => 'download',
				'http-method' => 'GET',
				'precond' => array (
						'Pluf_Precondition::loginRequired',
						'SaaS_Precondition::tenantMember' 
				) 
		),
		array ( // Content urls
				'regex' => '#^/(?P<id>\d+)$#',
				'model' => 'SaaSDM_Views_Asset',
				'method' => 'get',
				'http-method' => 'GET'
// 				'precond' => array (
// 						//'Pluf_Precondition::loginRequired',
// 						//'SaaS_Precondition::tenantMember' 
// 				) 
		),
		array ( // Content urls
				'regex' => '#^/(?P<id>\d+)/$#',
				'model' => 'SaaSDM_Views_Asset',
				'method' => 'get',
				'http-method' => 'GET'
				// 				'precond' => array (
						// 						//'Pluf_Precondition::loginRequired',
						// 						//'SaaS_Precondition::tenantMember'
						// 				)
		),		
		array (
				'regex' => '#^/find$#',
				'model' => 'SaaSDM_Views_Asset',
				'method' => 'find',
				'http-method' => 'GET'
// 				'precond' => array (
// 						//'Pluf_Precondition::loginRequired',
// 						//'SaaS_Precondition::tenantMember' 
// 				) 
		),
		array (
				'regex' => '#^/delete/(?P<id>\d+)$#',
				'model' => 'SaaSDM_Views_Asset',
				'method' => 'delete',
				'http-method' => 'GET'
				// 				'precond' => array (
						// 						//'Pluf_Precondition::loginRequired',
						// 						//'SaaS_Precondition::tenantMember'
						// 				)
		),		
		array (
				'regex' => '#^/asset/(?P<id>\d+)$#',
				'model' => 'SaaSDM_Views_Asset',
				'method' => 'update',
				'http-method' => 'POST'
				// 				'precond' => array (
						// 						//'Pluf_Precondition::loginRequired',
						// 						//'SaaS_Precondition::tenantMember'
						// 				)
		)		
				
		
);