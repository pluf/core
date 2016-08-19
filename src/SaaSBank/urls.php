<?php
return array (
		
		// اطلاعات بسته
		array (
				'regex' => '#^/$#',
				'model' => 'SaaSBank_Views_Main',
				'method' => 'modeul' 
		) ,
		
		array (
				'regex' => '#^/engine/find$#',
				'model' => 'SaaSBank_Views_Engine',
				'method' => 'find' 
		),
		array (
				'regex' => '#^/engine/(?P<type>\d+)$#',
				'model' => 'SaaSBank_Views_Engine',
				'method' => 'get' 
		) ,
);