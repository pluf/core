<?php
$cfg = array();

$cfg['db_engine'] = '\Pluf\Db\MySQLEngine';
$cfg['db_schema'] = '\Pluf\Db\MySQLSchema';
$cfg['db_version'] = '5.4.1';

$cfg['db_login'] = 'root';
$cfg['db_password'] = 'root';
$cfg['db_server'] = '127.0.0.1';
$cfg['db_database'] = 'test';

$cfg['db_schema_table_prefix'] = rand() . '_'; 

return $cfg;

