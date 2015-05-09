<?php
$ctl = array ();

/*
 * نمایش های پایه
 *
 * نمایش‌های پایه عبارت اند از تمام نمایش‌های که به کارهای عمومی تار افزار
 * مربوط است. این نمایش‌ها به موضوع خاصی ارتباط ندارند
 */
$base = '';
// ****************************************************************************
// * برنامه‌های کاربردی *
// ****************************************************************************
$ctl [] = array (
		'regex' => '#^/$#',
		'base' => $base,
		'model' => 'HM_Views',
		'method' => 'index' 
);
$ctl [] = array (
		'regex' => '#^/admin$#',
		'base' => $base,
		'model' => 'HM_Views',
		'method' => 'admin' 
);
$ctl [] = array (
		'regex' => '#^/apartment/(.+)$#',
		'base' => $base,
		'model' => 'HM_Views',
		'method' => 'apartment' 
);

// ****************************************************************************
// * مدیریت کاربران *
// ****************************************************************************
$ctl [] = array (
		'regex' => '#^/api/user/login$#',
		'base' => $base,
		'model' => 'HM_Views_Authentication',
		'method' => 'login' 
);
$ctl [] = array (
		'regex' => '#^/api/user/logout$#',
		'base' => $base,
		'model' => 'HM_Views_Authentication',
		'method' => 'logout' 
);

// ****************************************************************************
// * Wiki Page *
// ****************************************************************************
$ctl [] = array (
		'regex' => '#^/wiki/(.+)/(.+)$#',
		'base' => $base,
		'model' => 'HM_Views_Wiki',
		'method' => 'page' 
);

// ****************************************************************************
// * مدیریت پیام‌ها *
// ****************************************************************************
$ctl [] = array (
		'regex' => '#^/api/message/list$#',
		'base' => $base,
		'model' => 'HM_Views_Message',
		'method' => 'messages' 
);
$ctl [] = array (
		'regex' => '#^/api/message$#',
		'base' => $base,
		'model' => 'HM_Views_Message',
		'method' => 'message' 
);

// ****************************************************************************
// * مدیریت آپارتمانها *
// ****************************************************************************
$ctl [] = array (
		'regex' => '#^/api/apartment/list$#',
		'base' => $base,
		'model' => 'HM_Views_Apartment',
		'method' => 'apartments' 
);
$ctl [] = array (
		'regex' => '#^/api/apartment$#',
		'base' => $base,
		'model' => 'HM_Views_Apartment',
		'method' => 'apartment' 
);
$ctl [] = array (
		'regex' => '#^/api/apartment/(.+)/part/list$#',
		'base' => $base,
		'model' => 'HM_Views_Apartment',
		'method' => 'parts' 
);

// ****************************************************************************
// * مدیریت واحدها *
// ****************************************************************************
$ctl [] = array (
		'regex' => '#^/api/apartment/part/list$#',
		'base' => $base,
		'model' => 'HM_Views_Part',
		'method' => 'parts' 
);
$ctl [] = array (
		'regex' => '#^/api/apartment/part$#',
		'base' => $base,
		'model' => 'HM_Views_Part',
		'method' => 'create'
);
$ctl [] = array (
		'regex' => '#^/api/apartment/part/(.+)$#',
		'base' => $base,
		'model' => 'HM_Views_Part',
		'method' => 'part' 
);

return $ctl;

