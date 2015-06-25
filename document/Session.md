# مقدمه

به ازای هر ارتباطی که با سیستم ایجاد می‌شود یک نشست ایجاد می‌شود. در این نشست می‌توان داده‌های متفاوت را ذخیره کرد و در رابطه‌های بعد از آنها استفاده کرد.

داده‌های نشست سمت کاربر ارسال نمی‌شود. از این رو می‌توان گفت که امنیت آن کاملا تامین شده است. تنها داده‌ای که برای کاربران ارسال می‌شود کلید نشست است که باید توسط کاربر نگهداری شود.

ذخیره و بازیابی داده‌های نشست توسط خود سیستم مدیریت می‌شود. به محض اینکه یک نشست ایجاد شود داده‌ها آن نیز ذخیره و بازیابی خواهد شد.

بهترین و امن‌ترین روش برای نگه‌داری داده‌های نشست استفاده از همین روش است. 

## دخیره داده

هر اتصال به سیستم یک نشست دارد که در متغیری به نام session ذخیره می‌شود. برای ذخیره کردن یک داده در نشست مثل زیر عمل می‌شود:

	$request->session->setData("key", "value");

## بازیابی داده‌ها

برای بازیابی داده‌ها باید به صورت زیر عمل کرد:

	$request->session->setData("key", "value");
