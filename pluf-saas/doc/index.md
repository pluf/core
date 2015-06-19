# نرم افزار به عنوان سرویس

در این بسته ابزارهای مناسب برای این کار انجام شده است.

# نرم‌افزارها

نرم افزارها بر اساس سطح دسترسی به چهار سطح تقسیم می‌شوند:

- page
- member
- owner
- admin

## page

همه به آن دسترسی دارند. 

در مسیر زیر قرار می‌گیرند:

	templates/page

دو نرم‌افزار پیش فرض زیر نیز در نظر گرفته شده اند:

	templates/index.html
	templates/application.html

سایر صفحه‌هایی که مربوط به سایت اصلی است و هر کسی می‌تواند آنها را مشاهده کند به صورت زیر ایجاد می‌شوند

	templates/{page name}

این صفحه‌ها با آدرس زیر قابل دسترسی هستند

	/page/{page name}


## member

	templates/member

## owner

	templates/owner

## admin

	templates/admin

