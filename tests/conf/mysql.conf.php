<?php
$cfg = array();
$cfg['db_engine'] = 'MySQL';
$cfg['db_version'] = '5.4.1';

$cfg['db_login'] = 'root';
$cfg['db_password'] = '';
$cfg['db_server'] = 'localhost';

$cfg['db_database'] = 'test';
$cfg['db_table_prefix'] = 'px_test_' . rand();

return $cfg;

