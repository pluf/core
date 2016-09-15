<?php
set_include_path (
	PLUF_BASE . PATH_SEPARATOR .
	SRC_BASE . '/src' . PATH_SEPARATOR . 
	get_include_path () . PATH_SEPARATOR );
require 'Pluf.php';
Pluf::start ( SRC_BASE . '/src/config.php' );
Pluf_Dispatcher::loadControllers ( SRC_BASE . '/src/urls.php' );
Pluf_Dispatcher::dispatch ( Pluf_HTTP_URL::getAction () );
