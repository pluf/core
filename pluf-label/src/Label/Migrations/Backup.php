<?php


/**
 * ایجاد پشتیبان
 *
 * !! You need also to backup Pluf if you want the full backup. !!
 *
 * @param string Path to the folder where to store the backup
 * @param string Name of the backup (null)
 * @return int The backup was correctly written
 */
function Label_Migrations_Backup_run($folder, $name=null)
{
//     $models = array(
// 			'Peechak_Models_Job',
// 			'Peechak_Models_JobLib',
// 			'Peechak_Models_JobProperty',
// 			'Peechak_Models_JobResult',
// 			'Peechak_Models_Agent',
// 			'Peechak_Models_AgentCapability'
//     );
//     $db = Pluf::db();
//     // Now, for each table, we dump the content in json, this is a
//     // memory intensive operation
//     $to_json = array();
//     foreach ($models as $model) {
//         $to_json[$model] = Pluf_Test_Fixture::dump($model, false);
//     }
//     if (null == $name) {
//         $name = date('Y-m-d');
//     }
//     return file_put_contents(sprintf('%s/%s-Peechak.json', $folder, $name),
//                              json_encode($to_json), LOCK_EX);
}

/**
 * بازیابی پشتیبان
 *
 * @param string Path to the backup folder
 * @param string Backup name
 * @return bool Success
 */
function Label_Migrations_Backup_restore($folder, $name)
{

//     $db = Pluf::db();
//     $schema = new Pluf_DB_Schema($db);

// 	$models = array(
// 			'Peechak_Models_JobResult',
// 			'Peechak_Models_JobProperty',
// 			'Peechak_Models_JobLib',
// 			'Peechak_Models_Job',
// 			'Peechak_Models_AgentCapability',
// 			'Peechak_Models_Agent',
// 	);
//     foreach ($models as $model) {
//     	$schema->model = new $model();
//     	$schema->dropTables();
//     }
    
    
//     $models = array(
// 			'Peechak_Models_Job',
// 			'Peechak_Models_JobLib',
// 			'Peechak_Models_JobProperty',
// 			'Peechak_Models_JobResult',
// 			'Peechak_Models_Agent',
// 			'Peechak_Models_AgentCapability'
//     );
//     foreach ($models as $model) {
//         $schema->model = new $model();
//         $schema->createTables();
//     }
//     $full_data = json_decode(file_get_contents(sprintf('%s/%s-Peechak.json', $folder, $name)), true);
//     foreach ($full_data as $model => $data) {
//         Pluf_Test_Fixture::load($data, false);
//     }
//     foreach ($models as $model) {
//         $schema->model = new $model();
//         $schema->createConstraints();
//     }
//     return true;
}
