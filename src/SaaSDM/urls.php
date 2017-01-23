<?php
return array (
		
		array ( // Assets urls
				'regex' => '#^/asset/new$#',
				'model' => 'SaaSDM_Views_Asset',
				'method' => 'create',
				'http-method' => 'POST',
				'precond' => array (
						'Pluf_Precondition::loginRequired',
						'Pluf_Precondition::memberRequired' 
				) 
		),
		array (
				'regex' => '#^/asset/find$#',
				'model' => 'SaaSDM_Views_Asset',
				'method' => 'find',
				'http-method' => 'GET' 
		),
		// 'precond' => array (
		// //'Pluf_Precondition::loginRequired',
		// //'Pluf_Precondition::memberRequired'
		// )
		array ( // Asset urls
				'regex' => '#^/asset/(?P<id>\d+)$#',
				'model' => 'SaaSDM_Views_Asset',
				'method' => 'get',
				'http-method' => 'GET' 
		),
		// 'precond' => array (
		// //'Pluf_Precondition::loginRequired',
		// //'Pluf_Precondition::memberRequired'
		// )
		array ( // Asset urls
				'regex' => '#^/asset/(?P<id>\d+)/find$#',
				'model' => 'SaaSDM_Views_Asset',
				'method' => 'findchild',
				'http-method' => 'GET' 
		),
		// 'precond' => array (
		// //'Pluf_Precondition::loginRequired',
		// //'Pluf_Precondition::memberRequired'
		// )
		array ( // Asset urls
				'regex' => '#^/asset/(?P<id>\d+)$#',
				'model' => 'SaaSDM_Views_Asset',
				'method' => 'update',
				'http-method' => 'POST' 
		),
		array (
				'regex' => '#^/asset/(?P<id>\d+)$#',
				'model' => 'SaaSDM_Views_Asset',
				'method' => 'delete',
				'http-method' => 'DELETE',
				'precond' => array (
						'Pluf_Precondition::loginRequired',
						'Pluf_Precondition::memberRequired'
				)
		),		
		array ( // Link urls
				'regex' => '#^/asset/(?P<asset_id>\d+)/link/new$#',
				'model' => 'SaaSDM_Views_Link',
				'method' => 'create',
				'http-method' => 'GET' 
		),
		// 'precond' => array (
		// 'Pluf_Precondition::loginRequired',
		// 'Pluf_Precondition::memberRequired'
		// )
		array ( // Link urls
				'regex' => '#^/link/(?P<id>\d+)$#',
				'model' => 'SaaSDM_Views_Link',
				'method' => 'get',
				'http-method' => 'GET',
				'precond' => array (
						'Pluf_Precondition::loginRequired',
						'Pluf_Precondition::memberRequired' 
				) 
		),
		array ( // Link urls
				'regex' => '#^/link/find$#',
				'model' => 'SaaSDM_Views_Link',
				'method' => 'find',
				'http-method' => 'GET',
				'precond' => array (
						'Pluf_Precondition::loginRequired',
						'Pluf_Precondition::memberRequired' 
				) 
		),
		array ( // Plan urls
				'regex' => '#^/plan/(?P<id>\d+)$#',
				'model' => 'SaaSDM_Views_Plan',
				'method' => 'plan',
				'http-method' => 'GET',
				'precond' => array (
						'Pluf_Precondition::loginRequired',
						'Pluf_Precondition::memberRequired' 
				) 
		),
		array ( // Plan urls
				'regex' => '#^/plan/new$#',
				'model' => 'SaaSDM_Views_Plan',
				'method' => 'create',
				'http-method' => 'POST',
				'precond' => array (
						'Pluf_Precondition::loginRequired',
						'Pluf_Precondition::memberRequired' 
				) 
		),
		array ( // Plan urls
				'regex' => '#^/plan/find$#',
				'model' => 'SaaSDM_Views_Plan',
				'method' => 'find',
				'http-method' => 'GET',
				'precond' => array (
						'Pluf_Precondition::loginRequired',
						'Pluf_Precondition::memberRequired' 
				) 
		),
		array ( // Plan urls
				'regex' => '#^/plan/(?P<planId>\d+)/pay$#',
				'model' => 'SaaSDM_Views_Plan',
				'method' => 'payment',
				'http-method' => 'POST',
				'precond' => array (
						'Pluf_Precondition::loginRequired',
						'Pluf_Precondition::memberRequired' 
				) 
		),
		array ( // Plan urls
				'regex' => '#^/plan/(?P<planId>\d+)/activate$#',
				'model' => 'SaaSDM_Views_Plan',
				'method' => 'activate',
				'http-method' => 'GET',
				'precond' => array (
						'Pluf_Precondition::loginRequired',
						'Pluf_Precondition::memberRequired' 
				) 
		),
		array ( // PlanTemplate urls
				'regex' => '#^/plantemplate/(?P<id>\d+)$#',
				'model' => 'SaaSDM_Views_PlanTemplate',
				'method' => 'get',
				'http-method' => 'GET',
				'precond' => array (
						'Pluf_Precondition::loginRequired',
						'Pluf_Precondition::memberRequired' 
				) 
		),
		array ( // PlanTemplate urls
				'regex' => '#^/plantemplate/new$#',
				'model' => 'SaaSDM_Views_PlanTemplate',
				'method' => 'create',
				'http-method' => 'POST',
				'precond' => array (
						'Pluf_Precondition::loginRequired',
						'Pluf_Precondition::memberRequired' 
				) 
		),
		array ( // PlanTemplate urls
				'regex' => '#^/plantemplate/(?P<id>\d+)$#',
				'model' => 'SaaSDM_Views_PlanTemplate',
				'method' => 'update',
				'http-method' => 'POST',
				'precond' => array (
						'Pluf_Precondition::loginRequired',
						'Pluf_Precondition::memberRequired' 
				) 
		),
		array ( // Download urls
				'regex' => '#^/download/(?P<secure_link>.+)$#',
				'model' => 'SaaSDM_Views_Link',
				'method' => 'download',
				'http-method' => 'GET' 
		),
		// 'precond' => array (
		// 'Pluf_Precondition::loginRequired',
		// 'Pluf_Precondition::memberRequired'
		// )
);


// 'precond' => array (
// //'Pluf_Precondition::loginRequired',
// //'Pluf_Precondition::memberRequired'
// )

