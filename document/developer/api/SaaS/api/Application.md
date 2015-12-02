# ایجاد

	/app/create
	Method: POST

پارامترهایی که برای ایجاد می‌شود:

- title
- description

در نتیجه این فراخوانی نرم افزار ساخته شده برگردانده می‌شود.

# دریافت

دریافت اطلاعات نرم‌افزار در دو حالت کلی زیر کاربرد دارد:

- نرم‌افزارهای سمت کاربر در کاوشگر اینترنتی کار می‌کند از این رو می‌خواهد به اطلاعات نرم‌افزار فعال دسترسی پیدا کند. 
- یک برنامه کاربردی با زبان‌های برنامه سازی متفاوت نوشته شده و شناسه نرم‌افزار را دارد اما اطالاعات کلی آن را می‌خواهد.

برای هردو این حالت‌ها واسطه‌هایی در نظر گرفته شده است.

## بر اساس شناسه

برای دریافت اطلاعات یک نرم‌افزار خاص که شناسه آن در دسترس است، فراخوانی زیر در نظر گرفته شده است:

	/app/{application id}

این فراخوانی نیز باید با متد GET استفاده شود. خروجی این فراخوانی نیز مانند نمونه‌ای است که در بالا آورده شده است.

## نرم‌افزار جاری

زمانی که کاربران به صفحه اصلی یک نرم‌افزار وارد می‌شوند اطلاعات آن به صورت کوکی در کاوشگر ذخیره می‌شود تا همواره آخرین نرم‌افزاری که به آن وارد شده‌اید مشخص باشد. در این حالت برنامه‌های داخلی نرم‌افزار می‌توانند اطلاعات نرم‌افزار جاری را دریافت کنند.

برای دریافت اطلاعات نرم‌افزار جاری فراخوانی زیر در نظر گرفته شده است:

	/app

این فراخوانی با متد GET باید فراخوانی شود. نمونه‌ای از خروجی این فراخوانی در زیر آورده شده است:

	{
	    "id": 1,
	    "level": 0,
	    "access_count": 0,
	    "validate": false,
	    "title": "Admin demo apartment",
	    "description": "Auto generated application",
	    "creation_dtime": "2015-06-21 08:07:14",
	    "modif_dtime": "2015-06-21 08:07:14"
	}

## تعیین جاری

این فراخوانی توی تست خیلی کاربرد دارد.


# به روز کردن

	/app/{application id}
	Method : POST

فهرست پارامترهایی مجاز:

- title
- description

# فهرست

روشی کلی برای جستجوی نرم افزارها است.

## کلی

هر کسی می‌تواند این فراخوانی را انجام دهد

این فراخوانی به صورت زیر است:

	/app/list

این کار باید با متد GET انجام شود.

یک نمونه از خروجی این فراخوانی به صورت زیر است:

	{  
	   "0":{  
	      "id":1,
	      "level":0,
	      "access_count":0,
	      "validate":false,
	      "title":"Admin demo apartment",
	      "description":"Auto generated application",
	      "creation_dtime":"2015-06-19 18:44:07",
	      "modif_dtime":"2015-06-19 18:44:07"
	   }
	}

## بر اساس کاربر

یکی از نیازها تعیین نرم‌افزارهایی است که کاربر با آنها در رابطه است. فراخوانی زیر برای این کار در نظر گرفته شده است:

	/app/user/list

این فراخوانی باید با متد GET انجام شود.

این فراخوانی نیز مانند فهرست تمام نرم‌افزارهای کاربردی از روش‌های صفحه بندی استفاده می‌کند و تمام خصوصیت‌های معرفی شده در آن را حمایت می‌کند.

نتیجه این فراخوانی نیز همانند فراخوانی فهرست کاربران است اما یک خصوصیت اضافه برای تعیین نوع دسترسی دارد.


# مدیریت اعضا

یک نرم افزار کاربری می‌توان عضویت‌هایی به شکل زیر داشته باشد:

- owner
- member
- authorized
- anonymous

## کاربران

تنها افرادی که در سیستم ثبت شده‌اند قادرند از این فراخوانی استفاده کنند.

فراخوانی زیر برای این کار در نظر گرفته شده است:

	/app/{application id}/users

یک نمونه خروجی این فراخوانی در زیر آورده شده است.

	{
	    "members": {},
	    "owners": {
	        "0": "admin"
	    },
	    "authorized": {}
	}

## مالک‌ها

### گرفتن مالک‌ها

	/app/{application id}/owners
	Method : GET

### اضافه کردن مالک

	/app/{application id}/owner/{user id}
	Method : POST

### حذف مالک

	/app/{application id}/owner/{user id}
	Method : DELETE

## اعضا

### فهرست عضو‌ها


	/app/{application id}/members
	Method : GET

### اضافه کردن عضو

	/app/{application id}/member/{user id}
	Method : POST

### حذف یک عضو

	/app/{application id}/member/{user id}
	Method : DELETE

## معتبرها

### فهرست معتبرها

	/app/{application id}/authorizeds
	Method : GET

### اضافه کردن فرد معتبر

	/app/{application id}/authorized/{user id}
	Method : POST

### حذف معتبر

	/app/{application id}/authorized/{user id}
	Method : DELETE

# مدیریت sap

## تعیین پیش فرض

	/app/{application id}/sap/default/{sap id}
	Method : POST

## اضافه کردن 

	/app/{application id}/sap/{sap id}
	Method : POST

## حذف کردن

	/app/{application id}/sap/{sap id}
	Method : DELETE

## تعیین دسترسی

## گرفتن دسترسی‌ها

	/app/{application id}/sap/{sap id}/permissions
	Method : GET

### دسترسی مالک

فعال کردن

	/app/{application id}/sap/{sap id}/permit/owner
	Method : POST

حذف کردن

	/app/{application id}/sap/{sap id}/permit/owner
	Method : DELETE

### دسرتسی عضو

اضافه کردن

	/app/{application id}/sap/{sap id}/permit/member
	Method : POST

حذف کردن

	/app/{application id}/sap/{sap id}/permit/member
	Method : DELETE
	
### دسترسی فرد معتبر

اضافه کردن

	/app/{application id}/sap/{sap id}/permit/authorized
	Method : POST

حذف کردن

	/app/{application id}/sap/{sap id}/permit/authorized
	Method : DELETE
	
### دسترسی سایرین

اضافه کردن

	/app/{application id}/sap/{sap id}/permit/anonymous
	Method : POST

حذف کردن

	/app/{application id}/sap/{sap id}/permit/anonymous
	Method : DELETE
	
# تنظیم‌ها

تنظیم‌ها بخش اساسی برای هر نرم‌افزار کاربردی هستند. 

تنظیم‌ها به سه دسته کلی تقسیم می شوند:

- عمومی
- اختصاصی
- سیستمی

تنظیم‌های عمومی قابل دسترس برای هم هستند اما تنها مالک می‌تواند آنها را ویرایش کنند.

تنظیم‌های اختصاصی تنها برای سیستم و مالک قابل دسترس است ولی مالک می‌تواند آنها را تنظیم کند.

تنظیم‌های سیستم تنها در سیستم قابل دسترسی هستند.

برای نمونه رنگ پس زمینه باید از نوع عمومی باشد، قابلیت مشاهده نرم‌افزار برای تمام افراد باید یک خصوصیت اختصاصی باشد و فعال بودن و یا نبودن یک نرم‌افزار یک خصوصیت سیستمی است.

در این مستند ابزارهای مناسب برای دستکاری این تنظمی ها معرفی شده است.

## فهرست

فراخوانی زیر برای دسترسی به فهرست تنظیم‌ها در نظر گرفته شده است:

	/saas/app/{applicaation id}/configs

این فراخوانی باید با متد GET به کار گرفته شود. خروجی بر اساس دسترسی‌های کاربر می‌تواند متفاوت باشد. برای نمونه اگر بدون ورود به سیستم این فراخوانی انجام شود تنها خصوصیت‌های عمومی را در اختیار قرار می‌دهد.

یک نمونه خروجی برای این فراخوانی در زیر امده است.

## گرفتن مقدار

	/saas/app/{applicaation id}/config/{name}
	Method : GET

## حذف مقدار

	/saas/app/{applicaation id}/config/{name}
	Method : DELETE

## به روز کردن

	/saas/app/{applicaation id}/configs
	Method : POST

مقدار هر تنظیم باید به صورت کلید مقدار در بده باشد.


# منابع

## اضافه کردن 

## حذف کردن

## گرفتن

## فهرست