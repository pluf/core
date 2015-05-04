# رابطه میان مدل‌ها

نمی‌توان تصور کرد که هیچ رابطه‌ای میان داده‌ها وجود ندارد تا جایی که یک پایگاه داده بدون وجود رابطه داده‌ای بی معنی خواهد بود. به صورت کلی می‌توان گفت که دو نوع رابطه وجود دار که عبارتند از: چند به یک و چند به چند. زمانی که یک موجودیت مرجع باشد و تمام موجودیت‌های دیگر با آن در رابطه باشند رابطه چند به یک است. برای نمونه تمام پیام‌های یک صندوق پستی تنها با یک فرد در رابطه هستند. این نوع رابطه‌ها با قرار دادن یک کلید خرجی به مرجع در موجودیت‌ها ایجاد می‌شود. نوع فیلد کلید خارجی Pluf_DB_Field_Foreignkey در نظر گرفته شده است.

اما گونه‌ای خاص از رابطه‌ها، رابطه‌های چند به چند است که در آن یک موجودیت می‌تواند چندین موجودیت مرجع داشته باشد. برای نمونه هر کالا می‌تواند برچسب‌های متفاوتی داشته باشد و یا اینکه یک برچسب برای کالاهای متفاوتی به کار گرفته شده باشد. نوع Pluf_DB_Field_Manytomany برای تعریف یک رابطه چند به چند در نظر گرفته شده است.

در این بخش رابطه‌های معرفی شده را به صورت کامل تشریح خواهیم کرد و نشان می‌دهیم که چگونه از این رابطه‌ها در عمل استفاده کنیم.

## رابطه چند به یک

در نمونه‌ای که برای این بسته آورده شده است شما می‌توانید مشاهده کنید که یک گزینه به یک فهرست در رابطه است. این بدان معنا است که هر گزینه باید یک کلید خارجی از نوع Pluf_DB_Field_Foreignkey داشته باشد که مقدار آن کلید اصلی فهرست مربوطه است.

اگر به دقت به تعریف مدل داده‌ای Todo_Item، که تعریف مدل داده‌ای یک گزینه از فهرست است، نگاه کنید متوجه خواهید شده که در تابع init() کلید خارجی به صورت زیر تعریف شده است.

	'list' =>	array(
	  'type' => 'Pluf_DB_Field_Foreignkey',
	  'blank' => false,
	  'model' => 'Todo_List',
	  'verbose' => __('in list'),
	  'help_text' => __('To easily ... in lists.'),
	 ),

این فیلد تعیین می‌کند که مدل داده‌ای Todo_Item به صورت مستقیم با یک مدل داده‌ای به نام Todo_List در رابطه است. این فیلد رابطه چند به یک یک فهرست و یک گزینه را ایجاد می‌کند.
این مدل تعریف رابطه چند به یک نه تنها سادگی تعریف مدل داده‌ای را ایجاد می‌کند بلکه ابزارهای منابسی نیز برای مدیریت رابطه فراهم می‌کند.

### فراخوانی‌های خودکار

به محض اینکه در مدل داده‌ای یک رابطه چند به یک بین دو موجودیت ایجاد کنید، دسته‌ای از فراخوانی‌ها به صورت خودکار به مدل داده‌ای اضافه می‌شود. این فراخوانی‌ها بر اساس خصوصیت‌های ذاتی رابطه به مدل داده‌ای اضافه می‌شود. برای نمونه با به وجود آمدن رابطه چند به یک میان مدل داده‌ای Todo_Item و Todo_List شما نیاز به این دارید که فهرست تمام گزینه‌های موجود در Todo_List را در اختیار داشته باشید و یا اینکه تعیین کنید یک گزینه در کدام فهرست قرار دارد.

فراخوانی get_xxx() به صورت خودکار به تمام مدل‌هایی که شامل کلید خارجی می‌شوند اضافه می‌شود و امکان دستیابی به مدل داده‌ای مرجع xxx را فراهم می‌کند. برای نمونه مدل داده‌ای ایجاد شده برای Todo_Item شامل تابع زیر است:

	$list_id = = $item->list;
	$list = $item->get_list();

خط اول از نمونه بالا مقدار کلید خارجی را در اختیار می‌گذارد در حالی که خط دوم به صورت مستقیم به خود مدل داده‌ای دستیابی خواهد داشت.

فراخوانی مهم دیگری که به مرجع رابطه اضافه می‌شود به صورت (در این نمونه) به صورت زیر است که فهرست تمام گزینه‌های یک لیست را ایجاد می‌کند.

	$items = $list->get_todo_item_list();

این تابع با وجود اینکه هیچ فیلد داده‌ای در Todo_list در نظر گرفته نشده، به مدل داده‌ای اضافه شده است و این یکی از مهم‌ترین امکاناتی است که سادگی را برای این سکو فراهم کرده است. تعیین این توابع و اضافه شدن آن به مدل‌های داده‌ای همگی بر اساس تنظیم‌هایی است که در پرونده relation.php آورده می‌شود. این پرونده که در ادامه تشریح خواهد شد رابطه‌ها و نوع آن را به صورت کامل تعریف می‌کند.

در حالت کلی، همانگونه که در نمونه بالا اورده شده است، دست یابی به فهرست تمام موجودیت‌هایی که رابطه چند به یک با یک مرجع را دارند با استفاده از یک فراخوانی انجام می‌شود که شکل کلی آنبه صورت زیر است:

	get_ + lower case name of the related model + _list

نام این فراخوانی نیز می‌تواند با استفاده از تنظیم‌هایی که در پرونده relation.php آورده شده است تعیین شود. برای این کار از یک خصوصیت به نام relate_name استفاده می‌شود که محتوی آن یک نام است. برای نمونه اگر مقدار این خصوصیت به صورت items تعیین شود فراخوانی بالا به صورت زیر تعریف می‌شود:

	$items = $list->get_items_list();

این تکنیک منجر به خوانا شدن کد برنامه و کاهش هزینه نگهداری آن خواهد شد.

## رابطه چند به چند

The many to many relation is used when you want one item to belongs to many others non exclusively. For example you want to have an Article model to be in many Category models. But a Category should be able to have many Article model in it.

The many to many relation must be defined only in one of the two models. You should try to use the most logical. Here it is easier to say that an Article is part of many Category(s) so the many to many relation should be defined at the Article level.

This would be done the following way:

	 'categories' => 
	 array(
	  'type' => 'Pluf_DB_Field_Manytomany',
	  'blank' => false,
	  'model' => 'Category',
	  'verbose' => __('in categories'),
	 ),

### Automatically available methods

As the both a Category has many Article(s) and an Article many Category(s) only list() methods are available. These methods are:

	$article->get_categories_list();
	$category->get_article_list();

The name of the method on the article is get_categories_list() with a plural form for category because this is the name of the field. On the category it is get_article_list() with a singular for article because this is the lowercase name of the Article model. As for the many to one relation, it is possible to change this name by using the relate_name parameter.

If you want to associate an article to a category you have the following methods available:

	$article->setAssoc($category);
	$category->setAssoc($article);

You can see that a many to many relation is symetric. To remove an association you simply use the delAssoc() method:

	$article->delAssoc($category);
	$category->delAssoc($article);

## Registration of a model

Or the MyApp/relations.php file. To be able to dynamically have a method to list the Todo_Item in a Todo_List the relations between the models has to be defined. It is technically possible to load all the models and go through the definition of each model and from that build the relation map. Technically possible but not very efficient.

As presented, when you use the Plume Framework you create a folder for your application which is in fact the name of the application. In the case of the test application, the name is Todo. The content of the Todo folder. You will see a relations.php file. The content is very simple:

	$m = array();
	$m['Todo_Item'] = array('relate_to' => array('Todo_List'));
	return $m;

What it says is that the Todo_Item relate to the Todo_List. If an item could have been part of many lists through a many to many relation the definition would have been:

	$m = array();
	$m['Todo_Item'] = array('relate_to_many' => array('Todo_List'));
	return $m;

It is very important to define this file correctly. This file is used at the initialization of Pluf when calling:

	Pluf::start('./configuration.php');

Pluf will automatically look at the installed_apps configuration variable and load all the corresponding relations.php files. For example the test app configuration contains:

	$cfg['installed_apps'] = array('Pluf', 'Todo');

It means that the Todo/relations.php file will be loaded and the relations between the Todo_Item and the Todo_List will be found. For a more complex example you can see the relations of the core models of Pluf in Pluf/relations.php.