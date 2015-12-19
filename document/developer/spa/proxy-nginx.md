# دسترسی به سرور

اولی نیاز برای توسعه یک برنامه جدید درستی به قابلیت‌هایی است که توسط سرور ایجاد شده است.

سرور به صورت یک سرویس محلی شبه سازی می‌شود و نرم‌افزار ایجاد شده به صورت یک برنامه نصب شده روی یک مسیر موقت نگاشت می‌شود.

برای ادامه فرض‌های زیر را در نظر گرفته‌ایم:

- یک ملک روی سرور ایجاد کرده‌اید
- اطلاعات کاربری آن را دارید

## نصب

این نرم افزار برای سیستم‌های ویندوز و لینوکس پیاده سازی شده است.

http://nginx.com

### ویندوز

برای نصب مستند زیر رو مطالعه کنید:

- [Install on Windows](http://nginx.org/en/docs/windows.html)

### OpenSuse

برای نصب دستور زیر را وارد کنید.

	sudo zypper install nginx

## تنظیم‌ها


#user  nginx;

	worker_processes  1;
	pid /var/run/nginx.pid;
	events {
	    worker_connections  1024;
	    use epoll;
	}
	http {
	    include /etc/nginx/mime.types;
	    server {
	        listen 1212;
	        charset utf8;
	        location / {
	            root /home/maso/git/pluf-spa;
	        }
	        location /api {
	            proxy_pass http://localhost:1384;
	        }
	    }
	}


## اجرا

برای اجرای دستور زیر را وارد کنید

	sudo nginx -c /path/to/nginx.conf

اگر تنظیم‌ها تغییر کرده بود و نیاز به اجرای مجدد داشتید

	sudo nginx -s reload -c /path/to/nginx.conf