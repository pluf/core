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
		),
		array (
				'regex' => '#^/account$#',
				'base' => $base,
				'model' => 'User_Views_User',
				'method' => 'account' 
		),
		array (
				'regex' => '#^/signup$#',
				'base' => $base,
				'model' => 'User_Views_User',
				'method' => 'signup' 
		),
		array (
				'regex' => '#^/profile$#',
				'base' => $base,
				'model' => 'User_Views_Profile',
				'method' => 'profile' 
		),
);