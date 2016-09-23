<?php
$base = '';
return array (
		array (
				'regex' => '#^/api/user#',
				'base' => $base,
				'sub' => include 'User/urls.php' 
		),
		array (
				'regex' => '#^/api/group#',
				'base' => $base,
				'sub' => include 'Group/urls.php' 
		),
		array (
				'regex' => '#^/api/role#',
				'base' => $base,
				'sub' => include 'Role/urls.php' 
		),
		array (
				'regex' => '#^/api/tenant#',
				'base' => $base,
				'sub' => include 'Tenant/urls.php' 
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
		// SaaSNewspaper : online applications
		array ( 
				'regex' => '#^/api/newspaper#',
				'base' => $base,
				'sub' => include 'SaaSNewspaper/urls.php' 
		),
		// SaaSBank
		array ( 
				'regex' => '#^/api/bank#',
				'base' => $base,
				'sub' => include 'SaaSBank/urls.php' 
		),
		array ( // SaaS : online applications
				'regex' => '#^#',
				'base' => $base,
				'sub' => include 'SaaS/urls-app2.php' 
		) 
);


