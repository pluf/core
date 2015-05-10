<?php
return array (
		array (
				'regex' => '#^/login$#',
				'base' => $base,
				'model' => 'User_Views_Authentication',
				'method' => 'login' 
		),
		array (
				'regex' => '#^/logout$#',
				'base' => $base,
				'model' => 'User_Views_Authentication',
				'method' => 'logout' 
		) 
);