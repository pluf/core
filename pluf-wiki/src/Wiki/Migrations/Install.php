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
// 	$users = new Pluf_User ();
// 	$users->login = 'admin';
// 	$users->last_name = 'admin';
// 	$users->email = 'admin@dpq.co.ir';
// 	$users->setPassword ( 'admin' );
// 	$users->administrator = true;
// 	$users->staff = true;
// 	$users->create ();
	
// 	$apartment = new HM_Models_Apartment();
// 	$apartment->user = $users;
// 	$apartment->title = 'Admin demo apartment';
// 	$apartment->address = '';
// 	$apartment->create();
	
// 	$message = new HM_Models_Message();
// 	$message->title = 'Welcome to Apartment manager';
// 	$message->message = 'This is the welcome message from DPQ';
// 	$message->apartment = $apartment;
// 	$message->create();
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
