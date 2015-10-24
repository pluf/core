# یک نمونه پیچیده

به نمونه‌ای که در ادامه آورده شده است توجه کنید:

	<?php
	$base = Pluf::f('myapp_base');
	// we consider that $base = '/base/index.php';
	return array(
             array(
                   'regex' => '#^/hello/$#',
                   'base' => $base,
                   'model' => 'Hello_Views',
                   'method' => 'hello',
                   'name' => 'hello_main',
                   ),
             array(
                   'regex' => '#^/hello/(.*)$#',
                   'base' => $base,
                   'model' => 'Hello_Views',
                   'method' => 'helloByName',
                   ),
             array(
                   'regex' => '#^(.*)$#',
                   'base' => $base,
                   'model' => 'Hello_Views',
                   'method' => 'catchAll',
                   'name' => 'hello_catch_all',
                   ),
             );

You have here a little more complex example which is illustrating the priority to create a catch all view and the use of capturing groups in the regular expression.

You can see that the last regular expression will catch all the urls not catcheb by the 2 first. It is the last one in the list.

Now, where the fun comes in, the capturing groups. The result of the match is available to the view.

	<?php
	class Hello_Views
	{
		public function hello($request, $match)
		{
		    return new Pluf_HTTP_Response('Hello World!');
		}
		
		public function helloByName($request, $match)
		{
		    $name = $match[1];
		    return new Pluf_HTTP_Response('Hello '.$name.'!');
		}
		
		public function hello($request, $match)
		{
		    Pluf::loadFunction('Pluf_HTTP_URL_urlForView');
		    $url = Pluf_HTTP_URL_urlForView('hello_main');
		    return new Pluf_HTTP_Response_Redirect($url);
		}
	}

If you look at the method helloByName, you can see that if you access this view using the url http://localhost/base/index.php/hello/John, this will output Hello John!

The last view, illustrate the use of named view, you can easily redirect to a given view, without the need to know the URL, in fact, all the views are URLs independent as they are only defined in the urls.php file.

You can also access a view URL like this:

	$url = Pluf_HTTP_URL_urlForView('Hello_Views::hello');

If you want to provide the URL to say hello to Pamela, you can do the following:

	$url = Pluf_HTTP_URL_urlForView('Hello_Views::helloByName',
                                array('Pamela'));

The substitution of Pamela back in the URL to create /base/index.php/hello/Pamela will be done automatically.
URLs and Applications

The power of Pluf comes from the ability to break a big web application project into small applications glued together with a configuration file and an urls.php file.

Let say your friend Joe created a nice little Joe/Tag application with views to tag arbitrary objects and list the tagged objects. You have Joe/Tag/conf/urls.php with the definition of the URLs.

You can easily hook this application to be available from the /tags/ root.

	<?php
	$base = Pluf::f('myapp_base');
	// we consider that $base = '/base/index.php';
	return array(
             array(
                   'regex' => '#^/tags/#',
                   'base' => $base,
                   'sub' => include 'path/to/Joe/Tag/conf/urls.php',
                   ),
             array(
                   'regex' => '#^/hello/(.*)$#',
                   'base' => $base,
                   'model' => 'Hello_Views',
                   'method' => 'helloByName',
                   ),
             array(
                   'regex' => '#^(.*)$#',
                   'base' => $base,
                   'model' => 'Hello_Views',
                   'method' => 'catchAll',
                   'name' => 'hello_catch_all',
                   ),
             );

You can see the use of sub, which will load the URLs of the Joe/Tag application within the /tags/ subfolder. Note that the regular expression matches just the start of the string, without the $ at the end.