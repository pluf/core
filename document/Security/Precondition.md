
# پیش شرط‌ها

فراخوانی هر متد از لایه نمایش نیازمند یک سری پیش شرط‌ها است. با استفاده از این پیش شرط‌ها می‌توان امنیت را در لایه نمایش فراهم کرد.

هر پیش شرط با استفاده از یک رشته آدرس دهی می‌شود. این رشته به صورت زیر است:

	{Class name}::{method name}

این پیش شرط در حقیقت یک رشته است که یک متد از یک کلاس را آدرس دهی می‌کند.

## تعریف پیش شرط

برای هر کنترل می‌توان فهرستی از پیش شرط‌ها را تعیین کرد:

	
    array( // controller
        'regex' => '#^/controller/path$#',
        'model' => 'ControllerClass',
        'method' => 'controllerMethod',
        'precond' => 'precondition'
    ),

برای نمونه پیش شرط مدیریت سیستم به صورت زیر است:

	
    array( // controller
        'regex' => '#^/controller/path$#',
        'model' => 'ControllerClass',
        'method' => 'controllerMethod',
        'precond' => 'Pluf_Precondition::adminRequired'
    ),


مقدار پیش شرط می‌تواند یک یا یک آرایه از پیش شرطها باشد.

	
    array( // controller
        'regex' => '#^/controller/path$#',
        'model' => 'ControllerClass',
        'method' => 'controllerMethod',
        'precond' => array(
        	'Pluf_Precondition::adminRequired',
        	'Pluf_Precondition::ssl',
        )
    ),

## نوشتن یک پیش شرط

پیش شرط در حقیقت یک متد ایستا است که در یک کلاس تعریف شده است برای نمونه:

	class Pluf_Precondition
	{
	    static public function loginRequired ($request)
	    {
	    	//check conditions
	    }
	    ..
	}

### پارامترها

به صورت پیش فرض ساختار تقاضای کاربر برای پیش شرط‌ها ارسال می‌شود.

برخی پیش شرطها پارامترهایی را نیاز دارند. در این حالت پیش شرط به صورت آرایه تعریف می‌شود که در ادامه آن پارامترهای آن آورده می‌شود:

    array( // controller
	    'regex' => '#^/controller/path$#',
	    'model' => 'ControllerClass',
	    'method' => 'controllerMethod',
	    'precond' => array(
	    	'Pluf_Precondition::adminRequired',
	    	array(
				'Pluf_Precondition::hasPerm',
				'{permission id}'
			),
	    )
    ),

## پیش شرط‌های سیستم

دسته‌ای از پیش شرط‌ها در سیستم تعریف شده است:

- Pluf_Precondition::loginRequired
- Pluf_Precondition::staffRequired
- Pluf_Precondition::adminRequired
- Pluf_Precondition::hasPerm
- Pluf_Precondition::sslRequired

### loginRequired

کاربر حتما باید لاگین کرده باشد در غیر این صورت یک خطا صادر می‌شود.

### staffRequired

کاربر حتما باید کارمند سیستم باشد در غیر این صورت خطا صادر می‌شود.

### hasPerm

کابر باید یک مجوز خاص را داشته باشد. 

این پیش شرط نیاز به یک پارامتر دارد

### sslRequired

