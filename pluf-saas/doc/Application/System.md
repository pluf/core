
# سیستم کلی

صحفه‌های عمومی سیستم و آنهایی که در رابطه با سیستم کلی است در دسته‌های زیر قرار می‌گیرند.

- index
- general
- admin

## index

یکی از نرم‌افزارهای عمومی است.

هرکسی که وارد صفحه اصلی سیستم می‌شود این نرم‌افزار را می‌بیند.

پرونده معادل به این نرم‌افزار در مسیر زیر قرار دارد:

	templates/index.html

## general

دسته‌ای از نرم‌افزارها نیز به صورت سیستمی ایجاد می‌شوند و در اختیار کاربران قرار می‌گیرند مانند راهنمای سیستم. 

این نرم‌افزارهای عمومی در مسیر زیر ایجاد می‌شوند:

	templates/general/{page name}

دسترسی به این نرم‌افزارها نیز با مسیر زیر است:

	/general/{page name}

این صفحه‌ها برای هر فردی قابل دسترسی است.


## admin

دسته‌ای از صفحه‌ها برای مدیریت کلی سیستم است که تنها توسط مدیریت سیستم قابل دست رسی است.

این صفحه‌ها در مسیر زیر ایجاد می‌شود.

	templates/admin/{page name}

این صفحه‌ها با آدرس زیر قابل دسترسی است.

	/admin/{page name}
