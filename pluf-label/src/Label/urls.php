<?php
return array (
		array (
				'regex' => '#^/label/list$#',
				'base' => $base,
				'model' => 'Label_Views_Label',
				'method' => 'labels' 
		),
		array (
				'regex' => '#^/label/create$#',
				'base' => $base,
				'model' => 'Label_Views_Label',
				'method' => 'create' 
		),
		array (
				'regex' => '#^/label$#',
				'base' => $base,
				'model' => 'Label_Views_Label',
				'method' => 'label' 
		),
);