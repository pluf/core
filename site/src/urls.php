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
		array ( // CMS : Content Management System
				'regex' => '#^/api/cms#',
				'base' => $base,
				'sub' => include 'CMS/urls.php' 
		),
		array (
				'regex' => '#^/api/wiki#',
				'base' => $base,
				'sub' => include 'Book/urls.php' 
		),
		array (
				'regex' => '#^/api/spa#',
				'base' => $base,
				'sub' => include 'Spa/urls.php' 
		),
		array (
				'regex' => '#^/api/monitor#',
				'base' => $base,
				'sub' => include 'Monitor/urls.php' 
		),
		array (
				'regex' => '#^/api/dm#',
				'base' => $base,
				'sub' => include 'SaaSDM/urls.php' 
		),
		array (
				'regex' => '#^/api/sdp#',
				'base' => $base,
				'sub' => include 'SDP/urls.php' 
		),
		array (
				'regex' => '#^/api/message#',
				'base' => $base,
				'sub' => include 'Message/urls.php' 
		),
		array ( // Tenant configuration
				'regex' => '#^/api/setting#',
				'base' => $base,
				'sub' => include 'Setting/urls.php' 
		),
		array ( // Tenant configuration
				'regex' => '#^/api/config#',
				'base' => $base,
				'sub' => include 'Config/urls.php' 
		),
		array ( // Seo manager
				'regex' => '#^/api/seo#',
				'base' => $base,
				'sub' => include 'Seo/urls.php' 
		),
		array ( // Bank
				'regex' => '#^/api/bank#',
				'base' => $base,
				'sub' => include 'Bank/urls.php' 
		),
		array ( // Book
				'regex' => '#^/api/book#',
				'base' => $base,
				'sub' => include 'Book/urls.php' 
		),
		array ( // Calender
				'regex' => '#^/api/calendar#',
				'base' => $base,
				'sub' => include 'Calendar/urls.php' 
		),
		array ( // Backup
				'regex' => '#^/api/backup#',
				'base' => $base,
				'sub' => include 'Backup/urls.php' 
		),
		array ( // SaaS : online applications
				'regex' => '#^#',
				'base' => $base,
				'sub' => include 'Spa/urls-app2.php' 
		) 
);


