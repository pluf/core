<?php

/**
 * ساختارهای اولیه داده‌ای و پایگاه داده را ایجاد می‌کند.
 * 
 * @param string $params
 */
function Label_Migrations_Install_setup($params = '') {
	$models = array (
			'Label_Models_Label' 
	);
	$db = Pluf::db ();
	$schema = new Pluf_DB_Schema ( $db );
	foreach ( $models as $model ) {
		$schema->model = new $model ();
		$schema->createTables ();
	}
}

/**
 * تمام داده‌های ایجاد شده را از سیستم حذف می‌کند.
 *
 * @param string $params        	
 */
function Label_Migrations_Install_teardown($params = '') {
	$models = array (
			'Label_Models_Label' 
	);
	$db = Pluf::db ();
	$schema = new Pluf_DB_Schema ( $db );
	foreach ( $models as $model ) {
		$schema->model = new $model ();
		$schema->dropTables ();
	}
}
