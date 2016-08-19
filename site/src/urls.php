<?php
$base = '';
return array (
		array (
				'regex' => '#^/api/netipet#',
				'base' => $base,
				'sub' => include '../netipet/server/src/NetiPet/urls.php' 
		),
		array (
				'regex' => '#^/api/user#',
				'base' => $base,
				'sub' => include 'User/urls.php' 
		),
		array (
				'regex' => '#^/api/wiki#',
				'base' => $base,
				'sub' => include 'Wiki/urls.php' 
		),
		array ( // SaaSCMS : Content Management System
				'regex' => '#^/api/saascms#',
				'base' => $base,
				'sub' => include 'SaaSCMS/urls.php' 
		),
		array (
				'regex' => '#^/api/saas#',
				'base' => $base,
				'sub' => include 'SaaS/urls.php' 
		),
		array ( // SaaSNewspaper : online applications
				'regex' => '#^/api/newspaper#',
				'base' => $base,
				'sub' => include 'SaaSNewspaper/urls.php' 
		),
		array ( // SaaS : online applications
				'regex' => '#^#',
				'base' => $base,
				'sub' => include 'SaaS/urls-app2.php' 
		) 
);


