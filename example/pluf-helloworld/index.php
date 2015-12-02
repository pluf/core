<?php
set_include_path(get_include_path().PATH_SEPARATOR.'../../src');
require 'Pluf.php';
Pluf::start(dirname(__FILE__).'/Hello/conf/hello.php');
Pluf_Dispatcher::loadControllers(Pluf::f('hello_urls'));
Pluf_Dispatcher::dispatch(Pluf_HTTP_URL::getAction()); 

