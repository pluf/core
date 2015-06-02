# صندوق پیام

صندوق پیام راهکاری را برای انتقال پیام بین کاربران یک سیستم را فراهم می‌کند. در این بسته مدل داده‌ای و سیاست‌های کار با صندوق پستی ارائه شده است.

مبنای کار بسته کاملا مبتنی بر مدل REST است.

این بسته به تمام بسته‌های زیر وابستگی دارد:

- Pluf
- User


# مدل داده

هر پیام شامل اطلاعات زیر است:

- شناسه
- حالت
- برچسب
- فرستنده
- عنوان
- بدنه پیام

## شناسه 

به هر پیام یک شناسه یکتا داده می‌شود که یک عدد صحیح است

## حالت

حالت یک پیام یکی از موارد زیر است:

- NEW
- READED
- DELETED


## فرستنده

شناسه یک کاربر است که پیام را برای شما ارسال کرده است.

## عنوان

یک متن ساده است که عنوان پیام را تعیین می‌کند.


## بدنه

بدنه یک شئی JSON است که خود شامل موارد زیر است:

- سرآیند
- متن

یک نمونه از ساختار داده‌ای بدنه پیام در زیر آورده شده است.

	{
		'header':{
			'mimeType': 'plain/txt'
		},
		'message': 'Message body text'
	}
