<?php
return array (
		array (
				'regex' => '#^/label/list$#',
				'base' => $base,
				'model' => 'KM_Views_Label',
				'method' => 'labels' 
		),
		array (
				'regex' => '#^/label/create$#',
				'base' => $base,
				'model' => 'KM_Views_Label',
				'method' => 'create' 
		),
		array (
				'regex' => '#^/label/(\d+)$#',
				'base' => $base,
				'model' => 'KM_Views_Label',
				'method' => 'label' 
		),
		array (
				'regex' => '#^/category/list$#',
				'base' => $base,
				'model' => 'KM_Views_Category',
				'method' => 'categories' 
		),
		array (
				'regex' => '#^/category/create$#',
				'base' => $base,
				'model' => 'KM_Views_Category',
				'method' => 'create' 
		),
		array (
				'regex' => '#^/category/(\d+)$#',
				'base' => $base,
				'model' => 'KM_Views_Category',
				'method' => 'category' 
		) 
);