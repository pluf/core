<?php

/**
 * ساختارهای اولیه داده‌ای و پایگاه داده را ایجاد می‌کند.
 * 
 * @param string $params
 */
function Wiki_Migrations_Install_setup($params = '') {
	$models = array (
			'Wiki_Models_Page',
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
}

/**
 * تمام داده‌های ایجاد شده را از سیستم حذف می‌کند.
 * 
 * @param string $params
 */
function Wiki_Migrations_Install_teardown($params = '') {
	// $models = array(
	// 'Peechak_Models_Job',
	// 'Peechak_Models_JobLib',
	// 'Peechak_Models_JobProperty',
	// 'Peechak_Models_JobResult',
	// 'Peechak_Models_Agent',
	// 'Peechak_Models_AgentCapability'
	// );
	// $db = Pluf::db();
	// $schema = new Pluf_DB_Schema($db);
	// foreach ($models as $model) {
	// $schema->model = new $model();
	// $schema->dropTables();
	// }
}
