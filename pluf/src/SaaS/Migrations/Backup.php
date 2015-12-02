<?php

/**
 * Backup of SaaS.
 *
 *
 * @param string Path to the folder where to store the backup
 * @param string Name of the backup (null)
 * @return int The backup was correctly written
 */
function IDF_Migrations_Backup_run($folder, $name = null) {
	$models = array (
			'SaaS_Application',
			'SaaS_Configuration' 
	);
	$db = Pluf::db ();
	// Now, for each table, we dump the content in json, this is a
	// memory intensive operation
	$to_json = array ();
	foreach ( $models as $model ) {
		$to_json [$model] = Pluf_Test_Fixture::dump ( $model, false );
	}
	if (null == $name) {
		$name = date ( 'Y-m-d' );
	}
	return file_put_contents ( sprintf ( '%s/%s-SaaS.json', $folder, $name ), json_encode ( $to_json ), LOCK_EX );
}

/**
 * Restore SaaS from a backup.
 *
 * @param
 *        	string Path to the backup folder
 * @param
 *        	string Backup name
 * @return bool Success
 */
function IDF_Migrations_Backup_restore($folder, $name) {
	$models = array (
			'SaaS_Application',
			'SaaS_Configuration' 
	);
	$db = Pluf::db ();
	$schema = new Pluf_DB_Schema ( $db );
	foreach ( $models as $model ) {
		$schema->model = new $model ();
		$schema->createTables ();
	}
	$full_data = json_decode ( file_get_contents ( sprintf ( '%s/%s-SaaS.json', $folder, $name ) ), true );
	foreach ( $full_data as $model => $data ) {
		Pluf_Test_Fixture::load ( $data, false );
	}
	return true;
}