<?php
return array (
		array (
				'regex' => '#^/list$#',
				'base' => $base,
				'model' => 'Label_Views_Label',
				'method' => 'labels' 
		),
		array (
				'regex' => '#^/create$#',
				'base' => $base,
				'model' => 'Label_Views_Label',
				'method' => 'create' 
		),
		array (
				'regex' => '#^/(.+)$#',
				'base' => $base,
				'model' => 'Label_Views_Label',
				'method' => 'label' 
		),
);