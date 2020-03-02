<?php
// $var = include 'mysql.conf.php';
// $var = include 'pluf.config.php';
$var = include 'sqlite.conf.php';

// Enable logger
$var['log_level'] = \Pluf\Log::INFO;
$var['log_delayed'] = false;
$var['log_handler'] = '\Pluf\Log\Console';


return $var;
