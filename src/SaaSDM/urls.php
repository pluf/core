<?php
return array (
		
		array ( // Assets urls
				'regex' => '#^/new$#',
				'model' => 'SaaSDM_Views_Asset',
				'method' => 'create',
				'http-method' => 'POST' 
		),
		// 'precond' => array (
		// //'Pluf_Precondition::loginRequired',
		// //'SaaS_Precondition::tenantMember'
		// )
		array (
				'regex' => '#^/find$#',
				'model' => 'SaaSDM_Views_Asset',
				'method' => 'find',
				'http-method' => 'GET' 
		),
		// 'precond' => array (
		// //'Pluf_Precondition::loginRequired',
		// //'SaaS_Precondition::tenantMember'
		// )
		array ( // Asset urls
				'regex' => '#^/(?P<id>\d+)$#',
				'model' => 'SaaSDM_Views_Asset',
				'method' => 'get',
				'http-method' => 'GET' 
		),
		// 'precond' => array (
		// //'Pluf_Precondition::loginRequired',
		// //'SaaS_Precondition::tenantMember'
		// )
		array ( // Asset urls
				'regex' => '#^/(?P<id>\d+)/find$#',
				'model' => 'SaaSDM_Views_Asset',
				'method' => 'findchild',
				'http-method' => 'GET' 
		),
		// 'precond' => array (
		// //'Pluf_Precondition::loginRequired',
		// //'SaaS_Precondition::tenantMember'
		// )
		array ( // Asset urls
				'regex' => '#^/(?P<id>\d+)$#',
				'model' => 'SaaSDM_Views_Asset',
				'method' => 'update',
				'http-method' => 'POST' 
		),
		array ( // Link urls
				'regex' => '#^/link/new$#',
				'model' => 'SaaSDM_Views_Link',
				'method' => 'create',
				'http-method' => 'POST' 
		)
		// 'precond' => array (
		// 'Pluf_Precondition::loginRequired',
		// 'SaaS_Precondition::tenantMember'
		// )
		,
		array ( // Link urls
				'regex' => '#^/link/(?P<id>\d+)$#',
				'model' => 'SaaSDM_Views_Link',
				'method' => 'link',
				'http-method' => 'GET',
				'precond' => array (
						'Pluf_Precondition::loginRequired',
						'SaaS_Precondition::tenantMember' 
				) 
		),
		array ( // Link urls
				'regex' => '#^/link/find$#',
				'model' => 'SaaSDM_Views_Link',
				'method' => 'findlink',
				'http-method' => 'GET',
				'precond' => array (
						'Pluf_Precondition::loginRequired',
						'SaaS_Precondition::tenantMember' 
				) 
		),
		array ( // Plan urls
				'regex' => '#^/plan/(?P<id>\d+)$#',
				'model' => 'SaaSDM_Views_Plan',
				'method' => 'plan',
				'http-method' => 'GET',
				'precond' => array (
						'Pluf_Precondition::loginRequired',
						'SaaS_Precondition::tenantMember' 
				) 
		),
		array ( // Plan urls
				'regex' => '#^/plan/new$#',
				'model' => 'SaaSDM_Views_Plan',
				'method' => 'newplan',
				'http-method' => 'POST',
				'precond' => array (
						'Pluf_Precondition::loginRequired',
						'SaaS_Precondition::tenantMember' 
				) 
		),
		array ( // Plan urls
				'regex' => '#^/plan/find$#',
				'model' => 'SaaSDM_Views_Plan',
				'method' => 'findplan',
				'http-method' => 'GET',
				'precond' => array (
						'Pluf_Precondition::loginRequired',
						'SaaS_Precondition::tenantMember' 
				) 
		),
		array ( // PlanTemplate urls
				'regex' => '#^/plantemplate/(?P<id>\d+)$#',
				'model' => 'SaaSDM_Views_PlanTemplate',
				'method' => 'get',
				'http-method' => 'GET',
// 				'precond' => array (
// 						'Pluf_Precondition::loginRequired',
// 						'SaaS_Precondition::tenantMember' 
// 				) 
		),
		array ( // PlanTemplate urls
				'regex' => '#^/plantemplate/new$#',
				'model' => 'SaaSDM_Views_PlanTemplate',
				'method' => 'create',
				'http-method' => 'POST' 
		)
		// 'precond' => array (
		// 'Pluf_Precondition::loginRequired',
		// 'SaaS_Precondition::tenantMember'
		// )
		,
		array ( // PlanTemplate urls
				'regex' => '#^/plantemplate/(?P<id>\d+)$#',
				'model' => 'SaaSDM_Views_PlanTemplate',
				'method' => 'update',
				'http-method' => 'POST',
// 				'precond' => array (
// 						'Pluf_Precondition::loginRequired',
// 						'SaaS_Precondition::tenantMember' 
// 				) 
		),
		array ( // Download urls
				'regex' => '#^/download/find$#',
				'model' => 'SaaSDM_Views_PlanTemplate',
				'method' => 'findplantemplate',
				'http-method' => 'POST',
				'precond' => array (
						'Pluf_Precondition::loginRequired',
						'SaaS_Precondition::tenantMember' 
				) 
		),
		array ( // Download urls
				'regex' => '#^/download/(secure_id:.*)$#',
				'model' => 'SaaSDM_Views_Link',
				'method' => 'download',
				'http-method' => 'GET',
				'precond' => array (
						'Pluf_Precondition::loginRequired',
						'SaaS_Precondition::tenantMember' 
				) 
		),
		array (
				'regex' => '#^/(?P<id>\d+)$#',
				'model' => 'SaaSDM_Views_Asset',
				'method' => 'delete',
				'http-method' => 'DELETE' 
		) 
)
// 'precond' => array (
// //'Pluf_Precondition::loginRequired',
// //'SaaS_Precondition::tenantMember'
// )

;
// 'precond' => array (
// //'Pluf_Precondition::loginRequired',
// //'SaaS_Precondition::tenantMember'
// )

