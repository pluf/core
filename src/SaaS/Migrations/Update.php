<?php

/**
 * به روز کردن برنامه‌های کاربردی
 *
 * تمام برنامه‌های کاربردی موجود در مخزن‌ها را در پایگاه داده به روز رسانی
 * می‌کند. این کار برای هماهنگ شدن مخزن با پایگاه داده به کار گرفته می‌شود.
 */
function SaaS_Migrations_Update_spa() {
	$repos = Pluf::f ( 'saas_spa_repository' );
	if (! is_array ( $repos )) {
		$repos = array (
				Pluf::f ( 'saas_spa_repository' ) 
		);
	}
	$pd = Pluf::f ( 'saas_spa_package', "/spa.json" );
	foreach ( $repos as $repo ) {
		$repo = $repo . '/';
		$list = SaaS_Migrations_Update_SPAStorageList ($repo);
		foreach ( $list as $path ) {
			$filename = $repo . $path . $pd;
			if (is_readable ( $filename )) {
				$myfile = fopen ( $filename, "r" ) or die ( "Unable to open file!" );
				$json = fread ( $myfile, filesize ( $filename ) );
				fclose ( $myfile );
				$package = json_decode ( $json, true );
				
				$mprofile = SaaS_SPA::getSpaByName($package['name']);
				if($mprofile == null){
					$mprofile = new SaaS_SPA ();
					$mprofile->setFromFormData ( $package );
					$mprofile->path = '/' . $path;
					$mprofile->create ();
				} else {
					$mprofile->setFromFormData ( $package );
					$mprofile->update();					
				}
			}
		}
		// TODO: maso, 1395: نرم‌افزارهایی که حذف شده‌اند باید کشف شوند.
	}
}

/**
 * فهرستی از تمام نرم افزارهایی را تعیین می‌کند که در مخزن ایجاد شده اند.
 *
 * @param unknown $directory        	
 */
function SaaS_Migrations_Update_SPAStorageList($directory) {
	// XXX: maso, 1395: بررسی این که آیا پوشه وجود دارد
	$handler = opendir ( $directory );
	$results = array();
	while ( $file = readdir ( $handler ) ) {
		if ($file != "." && $file != "..") {
			$spaFile = $directory . $file . Pluf::f ( 'saas_spa_package', "/spa.json" );
			if (is_file ( $spaFile ) && is_readable ( $spaFile )) {
				$results [] = $file;
			}
		}
	}
	return $results;
}
