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

- regex: این عبارت منظم روی آدرس منبع ورودی با استفاده از تابع preg_match اجرا می‌شود و در صورتی که آدرس منبع منطبق با عبارت منظم بود آنگاه نمایش معادل با آن فراخوانی می‌شود.
- base: در بسیاری از موارد شما نیاز به نصب نرم‌افزار در زیر پوشه‌هایی از سایت دارد در این حال با استفاده از این متغیر می‌توانید آدرس دهی همه نمایش‌ها با به یک آدرس دلخواه انتقال دهید. می‌توان گفت این متغیر به عنوان پیشوندی برای آدرس‌های سایت در نظر گرفته می‌شود که در انطباق عبارت منظم استفاده نمی‌شود.

- model and method: این دو خصوصیت به ترتیب کلاس و متد معادل با لایه نمایش را تعیین می‌کنند.
- name: در بسیاری از موارد استفاده از یک نام یکتا برای لایه نمایش بسیار بهینه است جایی که ممکن است مسیر نمایش و یا کلاس و متد آن تغییر کند. با استفاده از این خصوصیت دیگر نیاز نیست که در کل سیستم فراخوانی لایه نمایش را تغییر دهید. به بیان ساده نام یک ارجاع مجازی به یک نمایش ایجاد می‌کند که البته باید به صورت یکتا در کل سیستم تعریف شده باشد.
- sub: زمانی که از برنامه‌هایی دیگر به عنوان زیر برنامه‌هایی در سیستم خود استفاده می‌کنید این متغییر به کار گرفته می‌شود. در ادامه استفاده از این متغیر به صورت کامل تشریح شده است.

نکته مهمی که باید همواره به آن توجه داشته باشید این است که عبارت منظم ایجاد شده باید بین دو علامت # قرار بگیرد.

نکته دیگری که باید به آن توجه داشته باشید این است که ترتیب در نظر گرفتن نمایش‌ها ترتیب قرار گرفتن آنها در فهرست نمایش‌ها است.

## یک نمونه پیچیده

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