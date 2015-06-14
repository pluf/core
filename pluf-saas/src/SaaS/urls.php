<?php
return array (
		array (//  صفحه اصلی سیستم
				'regex' => '#^/app/list$#',
				'base' => $base,
				'model' => 'Saas_Views_Application',
				'method' => 'applications' 
		), 
);