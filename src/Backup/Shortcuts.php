<?php

/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. (http://dpq.co.ir)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * ایجاد پشتیبان
 *
 * !! You need also to backup Pluf if you want the full backup. !!
 *
 * @param
 *            string Path to the folder where to store the backup
 * @param
 *            string Name of the backup (null)
 * @return int The backup was correctly written
 *        
 */
function Backup_Shortcuts_BackupRun ($folder, $name = null)
{
    if (! is_dir($folder)) {
        if (false == @mkdir($folder, 0777, true)) {
            throw new Pluf_Form_Invalid(
                    'An error occured when creating the file path.');
        }
    }
    $apps=Pluf::f('installed_apps');
    $db = Pluf::db();
    foreach ($apps as $app){
        if (false == ($file = Pluf::fileExists($app . '/module.json'))) {
            continue;
        }
        $myfile = fopen($file, "r") or die("Unable to open module.json!");
        $json = fread($myfile, filesize($file));
        fclose($myfile);
        $moduel = json_decode($json, true);
        if(!array_key_exists('model', $moduel)){
            continue;
        }
        $models = $moduel['model'];
        // Now, for each table, we dump the content in json, this is a
        // memory intensive operation
        $to_json = array();
        foreach ($models as $model) {
            $to_json[$model] = Pluf_Test_Fixture::dump($model, false);
        }
        file_put_contents(sprintf('%s/%s.json', $folder, $app), 
                json_encode($to_json), LOCK_EX);
    }
    return true;
}

/**
 * بازیابی پشتیبان
 *
 * @param string Path to the backup folder
 * @param string Backup name
 * @return bool Success
 */
// function KM_Migrations_Backup_restore($folder, $name)
// {

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
// }
