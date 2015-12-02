<?php

/**
 * ساختارهای اولیه داده‌ای و پایگاه داده را ایجاد می‌کند.
 * 
 * @param string $params
 */
function Jayab_Migrations_Install_setup($params = '') {
	$models = array (
			'Jayab_Location',
			'Jayab_Vote',
	        'Jayab_Tag',
	        'Jayab_SearchStatistic'
	);
	$db = Pluf::db ();
	$schema = new Pluf_DB_Schema ( $db );
	foreach ( $models as $model ) {
		$schema->model = new $model ();
		$schema->createTables ();
	}
	
	/*
	 * موجودیت‌های پیش فرض سیستم
	 */
	$users = new Pluf_User (1);
// 	$users->login = 'admin';
// 	$users->last_name = 'admin';
// 	$users->email = 'admin@dpq.co.ir';
// 	$users->setPassword ( 'admin' );
// 	$users->administrator = true;
// 	$users->staff = true;
// 	$users->create ();
	
	
	$tag = new Jayab_Tag();
	$tag->tag_key = 'amenity';
	$tag->tag_value = 'toilets';
	$tag->create();

	$tag = new Jayab_Tag();
	$tag->tag_key = 'amenity';
	$tag->tag_value = 'parking';
	$tag->create();
	
	$tag = new Jayab_Tag();
	$tag->tag_key = 'amenity';
	$tag->tag_value = 'place_of_worship';
	$tag->create();
	
	$tag = new Jayab_Tag();
	$tag->tag_key = 'building';
	$tag->tag_value = 'mosque';
	$tag->create();
	
	$tag = new Jayab_Tag();
	$tag->tag_key = 'shop';
	$tag->tag_value = 'boutique';
	$tag->create();
	
}

/**
 * تمام داده‌های ایجاد شده را از سیستم حذف می‌کند.
 *
 * @param string $params        	
 */
function Jayab_Migrations_Install_teardown($params = '') {
	$models = array (
			'Jayab_Location',
			'Jayab_Vote',
	        'Jayab_Tag',
	        'Jayab_SearchStatistic'
	);
	$db = Pluf::db ();
	$schema = new Pluf_DB_Schema ( $db );
	foreach ( $models as $model ) {
		$schema->model = new $model ();
		$schema->dropTables ();
	}
}
