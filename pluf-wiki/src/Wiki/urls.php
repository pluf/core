<?php
return array (
		array (
				'regex' => '#^/(.+)/(.+)$#',
				'base' => $base,
				'model' => 'Wiki_Views_Page',
				'method' => 'index',
		        'http-method' => 'GET'
		) 
);


