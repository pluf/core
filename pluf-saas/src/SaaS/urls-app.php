<?php
return array (
		array (// صفحه اصلی نرم‌افزار
				'regex' => '#^/(\d+)$#',
				'model' => 'SaaS_Views',
				'method' => 'application' 
		),
		array (// نرم‌افزارهای که تنها اعضا می‌توانند استفاده کنند
				'regex' => '#^/(\d+)/member/(.+)$#',
				'model' => 'SaaS_Views',
				'method' => 'member' 
		), 
		array (// نرم‌افزارهایی که تنها مربوط به مالک است
				'regex' => '#^/(\d+)/owner/(.+)$#',
				'model' => 'SaaS_Views',
				'method' => 'owner' 
		),
		array (// سایر صفحه‌های نرم‌افزار
				'regex' => '#^/(\d+)/(.+)$#',
				'model' => 'SaaS_Views',
				'method' => 'applicationPage' 
		),
		array (//  صفحه اصلی سیستم
				'regex' => '#^/$#',
				'model' => 'SaaS_Views',
				'method' => 'index'
		),
		array (//  صفحه اصلی مدیریت سیستم
				'regex' => '#^/admin/(.+)$#',
				'model' => 'SaaS_Views',
				'method' => 'admin' 
		),
		array (// سایر صفحه‌ها
				'regex' => '#^/user/(.+)$#',
				'model' => 'SaaS_Views',
				'method' => 'user'
		),
		array (// سایر صفحه‌ها
				'regex' => '#^/page/(.+)$#',
				'model' => 'SaaS_Views',
				'method' => 'page'
		),
);