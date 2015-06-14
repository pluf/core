<?php
return array (
		array (//  صفحه اصلی سیستم
				'regex' => '#^/$#',
				'base' => $base,
				'model' => 'SaaS_Views',
				'method' => 'index' 
		), 
		array (// صفحه اصلی نرم‌افزار
				'regex' => '#^/(\d+)$#',
				'base' => $base,
				'model' => 'SaaS_Views',
				'method' => 'application' 
		), 
		array (// سایر صفحه‌های نرم‌افزار
				'regex' => '#^/(\d+)/page/(.+)$#',
				'base' => $base,
				'model' => 'SaaS_Views',
				'method' => 'page' 
		), 
		array (// نرم‌افزارهای که تنها اعضا می‌توانند استفاده کنند
				'regex' => '#^/(\d+)/member/(.+)$#',
				'base' => $base,
				'model' => 'SaaS_Views',
				'method' => 'member' 
		), 
		array (// نرم‌افزارهایی که تنها مربوط به مالک است
				'regex' => '#^/(\d+)/owner/(.+)$#',
				'base' => $base,
				'model' => 'SaaS_Views',
				'method' => 'owner' 
		),
		array (//  صفحه اصلی سیستم
				'regex' => '#^/admin/(.+)$#',
				'base' => $base,
				'model' => 'SaaS_Views',
				'method' => 'admin' 
		), 
);