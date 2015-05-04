# آدرس‌ها و منابع

در این سکو شما می‌توانید آزادانه URL مورد نظر خود را برای هر نمایش ایجاد کنید. برای نمونه دسته‌ای از آدرس‌هایی قابل تعریف در این سکو آورده شده است

    / - The simplest :-)
    /article/
    /somewhere/pluf-is-great - Does not end with a trailing slash!
    /what/about/a/pdf-file.pdf - You can have extensions.
    /img/dynamic/image.png
    etc.

به بیان دقیق‌تر، از آنجا که ادرس‌ها با استفاده از عبارت‌های منظم تعریف می‌شود شما قادر خواهید بود که هر طور که می‌خواهید ساختار آدرس را برای لایه نمایش تعریف کنید.

تمام آدرس‌ها به صورت یک آرایه در یک پرونده تعریف شده و به عنوان نتیجه برگردانده می‌شود.
تمام آدرس‌های سیستم به صورت معمولد در یک پرونده با آدرس زیر تعریف می‌شود:

	YourApp/conf/urls.php 


## یک نمونه ساده

مدل زیر یک آدرس ایجاد می‌کند که در آن تنهای یک تابع فراخوانی می‌شود.

	<?php
	return array(
		array(
			'regex' => '#^/hello/$#',
			'base' => '',
			'model' => 'Hello_Views',
			'method' => 'hello'
		)
	);

در این نمونه تنها یک آدرس به عنوان نگاشت ایجاد شده است که شامل پارامترهای متفاوتی است. این پارامترها با کلیدهای خاصی در نظر گرفته می شود که عبارتند از:

- regex: This regular expression will be evaluated against the server PATH_INFO variable using preg_match and if it matches, the corresponding view will be called.
- base: As you very often install your PHP application in a subfolder in your document root, you can define it here. If you are not using mod_rewrite you must put /yourfolder/index.php or /index.php.
- model and method: The class and the method defining the view.
- name: Optionally, you can refer to a view with a name. For example, the view of your login form should have the name login_view. This is really important to use unique names for your application, especially if you want to distribute your application.
- sub: Subtree of views. This is used if you include in your application the views of other applications. See the section on URLs and applications below.

Important note: The regex delimiter must be the hash (#) character.

The order in which the URLs are defined is the order in which they will be matched.
Complex Example

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