<?php
$cfg = array();

$cfg['db_engine'] = '\Pluf\Db\MySQLEngine';
$cfg['db_schema'] = '\Pluf\Db\MySQLSchema';
$cfg['db_version'] = '5.4.1';

$cfg['db_login'] = 'root';
$cfg['db_password'] = '';
$cfg['db_server'] = 'localhost';
$cfg['db_database'] = 'test';

$cfg['db_schema_table_prefix'] = 'pluf_core_unit_tests_' . rand() . '_'; 

return $cfg;

