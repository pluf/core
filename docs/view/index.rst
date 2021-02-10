

View is the main part of the framework where input requests are processed and 
the results generated.


.. code:: php
	// Create Request
	$request = Request::getInstance();
	
	// load dispatcher and create response
	$response = Dispatcher::getInstance()
		->setControllers(__DIR__ . 'urls.php')
		->dispatch($request);
	
	// render response
	$response->render();

