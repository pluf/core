# مروری بر تمام فیلدها

هر ساختار داده‌ای از یک مجموعه داده‌ای ایجاد می‌شود که می‌تواند شامل فیلدهای متفاوت از انواع متفاوت باشد. در این بخش مدلها و فیلدهای داده‌ای که در این سکو حمایت می‌شود آورده شده است.

    
## Pluf_DB_Field_Varchar

String of character of a maximum given length.

## Pluf_DB_Field_Integer

Integer number.

## Pluf_DB_Field_Sequence

Autoincrement number, mandatory for each model with the name id.

## Pluf_DB_Field_Text

Raw text, not limited in length.

## Pluf_DB_Field_Foreignkey

A many to one relationship.

## Pluf_DB_Field_Manytomany

A many to many relationship.

## Pluf_DB_Field_Datetime

A date and time field.

## Pluf_DB_Field_Date

A date only field.

## Pluf_DB_Field_Boolean

A boolean field.

## Pluf_DB_Field_Email

An email field.

## Pluf_DB_Field_Float

A float field.

## پرونده

یکی دیگر از فیلدهایی که برای مدل داده‌ای در نظر گرفته شده است پرونده است. تمام پرونده‌هایی که در سیستم بارگذاری می‌شوند در یک مسیر ذخیره شده و آدرس آنها در یک جدول نگهداری می‌شود. این داده‌ها به عنوان یک فیلد به یک ساختار داده‌ای اضافه می‌شود. کلاس معادل با این فیلد عبارت است از:

	Pluf_DB_Field_File



## Pluf_DB_Field_File

The file is stored on the file system and the database only stores the subdirectory of the upload_root folder and the filename.

Parameters for the field:

TODO: Put the parameters.