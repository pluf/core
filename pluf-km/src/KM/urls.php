<?php
return array (
		array (
				'regex' => '#^/label/list$#',
				'model' => 'KM_Views_Label',
				'method' => 'labels' 
		),
		array (
				'regex' => '#^/label/create$#',
				'model' => 'KM_Views_Label',
				'method' => 'create' 
		),
		array (
				'regex' => '#^/label/(\d+)$#',
				'model' => 'KM_Views_Label',
				'method' => 'label' 
		),
		array (
				'regex' => '#^/category/list$#',
				'model' => 'KM_Views_Category',
				'method' => 'categories' 
		),
		array (
				'regex' => '#^/category/create$#',
				'model' => 'KM_Views_Category',
				'method' => 'create' 
		),
		array (
				'regex' => '#^/category/(\d+)$#',
				'model' => 'KM_Views_Category',
				'method' => 'category' 
		) 
);