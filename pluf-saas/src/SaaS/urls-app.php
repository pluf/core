<?php
return array (
		array (// صفحه اصلی نرم‌افزار
				'regex' => '#^/(\d+)$#',
				'base' => $base,
				'model' => 'SaaS_Views',
				'method' => 'application' 
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
		array (// سایر صفحه‌های نرم‌افزار
				'regex' => '#^/(\d+)/(.+)$#',
				'base' => $base,
				'model' => 'SaaS_Views',
				'method' => 'applicationPage' 
		),
		array (//  صفحه اصلی سیستم
				'regex' => '#^/$#',
				'base' => $base,
				'model' => 'SaaS_Views',
				'method' => 'index'
		),
		array (//  صفحه اصلی مدیریت سیستم
				'regex' => '#^/admin/(.+)$#',
				'base' => $base,
				'model' => 'SaaS_Views',
				'method' => 'admin' 
		),
		array (// سایر صفحه‌ها
				'regex' => '#^/user/(.+)$#',
				'base' => $base,
				'model' => 'SaaS_Views',
				'method' => 'user'
		),
		array (// سایر صفحه‌ها
				'regex' => '#^/page/(.+)$#',
				'base' => $base,
				'model' => 'SaaS_Views',
				'method' => 'page'
		),
);