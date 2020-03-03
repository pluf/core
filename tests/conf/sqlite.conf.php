<?php
/*
 *
 * Database configuration.
 *
 * The database defined here will be directly accessible from Pluf::db() of 
 * course it is still possible to open any other number of database connections 
 * through \Pluf\DB.
 */
$cfg = array();


$cfg['db_engine'] = 'SQLite';
$cfg['db_version'] = '5.0';


$cfg['db_login'] = 'testpluf';
$cfg['db_password'] = 'testpluf';
$cfg['db_server'] = 'localhost';

$cfg['db_database'] = dirname(__FILE__) . '/../tmp/dp.test.sqlite.db';

//
// Must be shared by all the installed_apps and the core framework.
// That way you can have several installations of the core framework.
//
$cfg['db_table_prefix'] = 'px_test_' . rand();


return $cfg;

