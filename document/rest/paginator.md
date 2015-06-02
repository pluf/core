# صفحه بندی

یکی از مهم‌ترین ابزارهایی که برای صفحه بندی داده‌ها استفاده می‌شود کلاس Paginator است. این ابزار پارامترهای متفاوتی را حمایت می‌کند که منجر به افزایش کارایی آن می‌شود. در این مستند این پارامترها و تنظیم‌ها از نظر فراخوانی REST تشریح می‌شود.

## پارامترهای عمومی

در زیر فهرست پارامترهای عمومی آورده شده است. این پارامترها مستقل از اینکه چگونه صفحه بندی را ایجاد کرده‌اید کارایی داشته و می‌توانند در تمام واسطه‌ها به کار گرفته شوند.

+ \_px\_q : یک کویری است که انتظار داریم روی داده‌ها اجرا شود.
+ \_px\_p : صفحه جاری را تعیین می‌کند.
+ \_px\_sk : کلیدی را تعیین می‌کند که مرتب سازی باید بر اساس آن انجام شود.
+ \_px\_so : ترتیب مرتب سازی را تعیین می‌کند.
+ \_px\_fk : کلید فیلتر کردن را تعیین می‌کند.
+ \_px\_fv : مقدار فیلتر کرد را تعیین می‌کند.

نکته: بهتر است این پارامترها را به عنوان پارامترهای رزرو شده سیستم در نظر بگیرید و آنها را در کاربردهای دیگر به کار نبرید.


## کوری جستجو

یک فهرست از داده‌هایی که قابلی جستجو را دارند در پیاده سازی تعیین می‌شود. کاربران می‌توانند یک سری کلمات مورد نظر خود را برای جستجو در تمام فیلدهای جستجو تعیین کنند. برای نمونه فرض کنید که فیلد جستجو description تعریف شده باشد در این حالت عبارت زیر تمام اشیائی را تعیین می‌کند که در توضیح آنها داده‌ای به نام data1 وجود دارد:

	_px_q=data1

مقادیر با استفاده از فضای خالی از یکدیگر جدا می‌شوند برای نمونه تمام واژه‌های زیر در داده‌ها جستجو می‌شوند:

	ـpx_q=data1 data2 data3 ... datan

## مدیریت صفحه‌ها

تمام داده‌هایی که از جستجو به دست آمده است در تعدادی صفحه شکسته می‌شود. با تعیین شماره صفحه تنها داده‌های مربوط به آن صفحه در اختیار قرار خواهد گرفت. برای نمونه کد زیر صفحه دوم را در اختیار می‌گذارد:

	_px_p=2


## مرتب سازی داده‌ها

داده‌های خروجی را می‌توان به دو روش مرتب کرد که عبارتند از:

+ d: DESC
+ a: ASC

در صورتی که روش مرتب سازی تعیین نشده باشد روش ASC به عنوان روش مرتب سازی در نظر گرفته خواهد شد.

مرتب سازی بر اساس یکی از داده‌هایی ممکن است که در تنظیم‌های صفحه بند آمده باشد. کلید و روش مرتب سازی به صورت زیر تعیین می‌شود:

	‌ـpx_sk=title
	_px_sk=d


## فیلتر کردن داده‌ها

دوتا متغیر نیز برای فیلتر کردن داده‌ها به کار گرفته می‌شود که یکی کلید فیلتر و  دیگری مقدار فیلتر را تعیین می‌کند. برای نمونه ترکیب زیر را در نظر بگیرید:

	_px_fk=community
	_px_fk=1

خروجی این فیلتر تنها داده‌هایی است که خصوصیت تعیین شده در بالا مقدار تهی داشته باشد.