# مدیریت سیستم

مدیریت سیستم نیز ابزاری برای مدیریت پرداخت‌ها نیازمند است.  در اینجا این ابزاها معرفی می‌شود.

## فهرست تمام پرداخت‌ها

	/payment/list


# آپارتمان

یکی دیگر از نقش‌هایی که با پرداخت‌ها سرکار دارد، مدیرت آپارتمان است. این ابزارها در این بخش تعریف شده است.

## فهرست پرداخت‌ها

فراخوانی زیر برای فهرست کردن تمام پرداختهای یک ساختمان در نظر گرفته شده است:

	/apartment/payment/list

این فراخوانی باید با متد GET به کار گرفته شود. 

در این فراخوانی از تکنیک صفحه بندی استفاده شده است که در بسته Pluf به صورت کامل تشریح شده است. بر اساس مستندهای صفحه بندی خصوصیت‌های زیر برای فیلتر کردن در نظر گرفته شده:

- id
- part
- amount

فیلدهای زیر برای جستجو در نظر گرفته شده است:

- title
- description
- amount

فیلدهای زیر هم برای مرتب سازی نتایج به کار گرفته شده است:

- title
- part
- amount
- creation_dtime

## اضافه کردن پرداخت برای همه واحد‌ها

ایجاد پرداخت برای همه واحدها با استفاده از واسط زیر انجام می‌شود:

	/apartment/payment/create

این فراخوانی با متد POST باید انجام شود. پارامترهایی که برای این فراخوانی می‌توان به کار برد عبارتند از:

- title*
- description
- amount*

فیلدهایی که با ستاره تعیین شده‌اند باید تعیین شوند وگرنه خطای زمان اجرا تولید خواهد شد. پس از اینکه پرداخت برای واحدها ایجاد شد، نتجه به صورت یک آرایه از پرداختها تولید می‌شود. در کد زیر یک نمونه آورده شده است:

	[
	    {
	        "id": 9,
	        "part": 1,
	        "amount": 12,
	        "title": "new payment",
	        "description": "",
	        "creation_dtime": "2015-06-06 19:23:11",
	        "modif_dtime": "2015-06-06 19:23:11",
	        "access_count": 0
	    },
	    {
	        "id": 10,
	        "part": 2,
	        "amount": 12,
	        "title": "new payment",
	        "description": "",
	        "creation_dtime": "2015-06-06 19:23:11",
	        "modif_dtime": "2015-06-06 19:23:11",
	        "access_count": 0
	    },
	   ...
	]

## اضافه کردن پرداخت برای یک واحد

با استفاده از فراخوانی زیر می‌توان یک پرداخت را تنها برای یک واحد ایجاد کرد.

	/apartment/part/{part id}/payment/create

این فراخوانی باید با استفاده از متد POST انجام شود. پارامترهایی که برای این فراخوانی در نظر گرفته شده‌اند عبارتند از:

- title*
- description
- amount*

پارامترهایی که با علامت ستار مشخص شده‌اند برای ایجاد یک پرداخت الزامی هستند. در نتیجه این فراخوانی اطلاعات کامل پرداخت ایجاد شده ارسال خواهد شد. یک نمونه از خروجی این فراخوانی در زیر آورده شده است.

	{
	    "id": 44,
	    "part": 2,
	    "amount": 300,
	    "title": "title of new",
	    "description": "",
	    "creation_dtime": "2015-06-07 07:21:05",
	    "modif_dtime": "2015-06-07 07:21:05",
	    "access_count": 0
	}

## تکمیل پرداخت

تنها مالک آپارتمان می‌تواند این کار را انجام دهد.

تکمیل پرداخت تنها برای یک واحد ممکن است و نمی‌توان آن را به صورت جمعی اجرا کرد. برای این کار فراخوانی زیر در نظر گرفته شده است.

	/apartment/part/{part id}/payment/{payment id}

این فراخوانی باید با متد POST انجام شود که در آن خصوصیت‌های زیر قابل ویرایش است.

- description
- verified

که به ترتیب توضیحات و وضعیت پرداخت آن را تعیین می‌کند. وضعیت پرداخت با استفاده از مقدار بولی و یا مقادیر ۰ و ۱ مشخص می‌شود.

در نتیجه این تغییرها پرداخت اصلاح شده و تمام نتایج اصلاح برای کاربر ارسال می‌شود. یک نمونه از داده‌های ارسال شده در زیر اورده شده است:

	{
	    "id": 1,
	    "receipts": "",
	    "verified": true,
	    "part": 1,
	    "amount": 12,
	    "title": "new payment",
	    "description": "",
	    "creation_dtime": "2015-06-08 20:01:49",
	    "modif_dtime": "2015-06-08 20:02:29"
	}


تنها مدیریت آپارتمان می‌تواند اطلاعات یک پرداخت را ویرایش کند.

## ویرایش یک پرداخت

تکمیل و ویرایش پرداخت به صورت هم زمان مدیریت می‌شود. برای اطلاع در مورد نحوه ویرایش پرداخت به بخش انجام پرداخت مراجعه کنید.

## حذف موقت یک پرداخت

در عمل نمی‌توان یک پرداخت را از سیستم حذف کرد. در اینجا حذف به معنی آرشیو کردن یک پرداخت است. برای ارشیو کردن یا حذف یک پرداخت فراخوانی زیر در نظر گرفته شده است:

	/apartment/part/{part id}/payment/{payment id}

این فراخوانی با استفاده از متد DELETE باید به کار گرفته شود. با این کار پرداخت یک برچست حذف خورده و دیگر در نمایش‌ها و جستجوها نمایش داده نمی‌شود:

	{
	    "id": 1,
	    "receipts": "",
	    "verified": false,
	    "deleted": true,
	    "part": 1,
	    "amount": 12,
	    "title": "new payment",
	    "description": "",
	    "creation_dtime": "2015-06-08 20:34:10",
	    "modif_dtime": "2015-06-08 20:39:21"
	}

# واحدها

سایر افرادی که به سیستم مراجعه می‌کنند در قالب کاربرانی هستند که پرداختها را انجام داده و یا آنها را مشاهد می‌کنند. در اینجا ابزارهای مورد نیاز برای این کار معرفی شده است.

## فهرست پرداخت‌ها

فهرست پرداخت‌ها آپارتمان کاملا شبیه به فهرست پرداختهای ساختمان است با این تفاوت که تنها پرداخت‌های یک آپارتمان را به دست می‌دهد. این کار برای زمانی مناسب است که می‌خواهیم برنامه کاربردی را تنها برای یک آپارتمان داشته باشیم. فراخوانی زیر برای این کار در نظر گرفته شده است:

	/apartment/part/{part id}/payment/list

این فراخوانی تنها باید با متد GET استفاده شود. راهکاری که در اینجا استفاده شده است صفحه بندی است که در بسته Pluf معرفی شده است.بندی خصوصیت‌های زیر برای فیلتر کردن در نظر گرفته شده:

- id
- amount
- verified

فیلدهای زیر برای جستجو در نظر گرفته شده است:

- title
- description
- amount

فیلدهای زیر هم برای مرتب سازی نتایج به کار گرفته شده است:

- title
- amount
- creation_dtime
- modif_dtime

یک نمونه از نتیجه این فراخوانی در زیر آورده شده است:

	{
	    "0": {
	        "id": 34,
	        "part": 2,
	        "amount": 670000,
	        "title": "other for you",
	        "description": "",
	        "creation_dtime": "2015-06-06 19:48:25",
	        "modif_dtime": "2015-06-06 19:48:25",
	        "apartment": "1"
	    },
	    "1": {
	        "id": 26,
	        "part": 2,
	        "amount": 670000,
	        "title": "new payment 2 in admin payment",
	        "description": "",
	        "creation_dtime": "2015-06-06 19:48:10",
	        "modif_dtime": "2015-06-06 19:48:10",
	        "apartment": "1"
	    },
		...
	}
	
در اینجا نیز از روش صفحه بندی استفاده شده است برای اطلاعات بیشتر در مورد صفحه بندی به مستند pluf مراجعه کنید.

## پرداخت برخط

در فاز بعد تکمیل می‌شود.