<?php
return array (
		array (
				'regex' => '#^/list$#',
				'base' => $base,
				'model' => 'KM_Views_Label',
				'method' => 'labels' 
		),
		array (
				'regex' => '#^/create$#',
				'base' => $base,
				'model' => 'KM_Views_Label',
				'method' => 'create' 
		),
		array (
				'regex' => '#^/(.+)$#',
				'base' => $base,
				'model' => 'KM_Views_Label',
				'method' => 'label' 
		),
);