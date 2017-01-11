<?php
$cfg = array();

/*
 * ----------------------------------------------------------------------------
 * Basic Configuration
 * ----------------------------------------------------------------------------
 */
$cfg['general_domain'] = 'localhost';
$cfg['general_from_email'] = 'info@dpq.co.ir';
$cfg['general_admin_email'] = array(
        'info@localhost'
);

/*
 * نرم‌افزار ممکن است از ماژولهای متفاوتی استفاده کند. در اینجا فهرست تمام
 * ماژول‌های مورد نیاز تعیین می‌شود. به این نکته توجه داشته باشید که تمام
 * ماژولها باید در مسیر همنام و مسیرهای قابل دسترسی برای سیستم موجود باشند.
 * معمولا یک مسیر برای تمام این ماژولها در نظر گرفته می‌شود که در متغیر عمومی
 * $path_to_pluf قرار می‌گیرد.
 */
$cfg['installed_apps'] = array(
        'Pluf',
        'User',
        'Group',
        'Role',
        'Tenant',
//         'Spa',
//         'Monitor',
//         'SDP',
//         'SaaSDM',
//         'Message',
//         'Setting',
//         'Config',
        

//         'SaaS',
//         'CMS',
//         'SaaSNewspaper',
//         'Wiki',
//         'Bank',
);

/*
 * تمام میان افزارهایی که در نرم‌افزار نهایی به کار گرفته می‌شود در اینجا آورده
 * می‌شوند این میان افزارها به ترتیب روی هر تقاضا و نتیجه اجرا می‌شوند.
 */
$cfg['middleware_classes'] = array(
        // 'Pluf_Middleware_Csrf',
        'Pluf_Middleware_Session',
        'Pluf_Middleware_Translation',
        // Tenant loading
        'Pluf_Middleware_TenantEmpty',
        'Pluf_Middleware_TenantFromHeader',
        // 'Pluf_Middleware_TenantFromDomain',
        // 'Pluf_Middleware_TenantFromRequestMatch',
        // 'Pluf_Middleware_TenantFromSession',
        'Pluf_Middleware_TenantFromConfig',
        'Seo_Middleware_Spa',
        'Pluf_Middleware_Api'
);

/*
 * در فرآیند توسعه نیاز است که خروجی‌های مناسبی برای خطا‌ها ایجاد شود و یا اینکه
 * الگوها به صورت مداوم ایجاد شوند. با فعال کردن این گزینه می‌توانید سیستم را به
 * حالت رفع خطا وارد کنید.
 */
$cfg['debug'] = true;
$cfg['migrate_allow_web'] = true;

/*
 * مسیر ذخیره فایل‌های آپلود شده برای کانتنت‌ها
 */
$cfg['upload_path'] = SRC_BASE . '/tenant';

$cfg['time_zone'] = 'Europe/Berlin';
$cfg['encoding'] = 'UTF-8';

/*
 * ----------------------------------------------------------------------------
 * Security section
 * ----------------------------------------------------------------------------
 */
/*
 * یک رشته تصادفی بلند به عنوان کلید اصلی سیستم تعیین می‌شود این کلید برای
 * کارهای امنیتی سیستم استفاده می‌شود و باید به شکل امنی ایجاد شده باشد. یک روش
 * ساده برای ایجاد این کلید استفاده از دستور زیر در سیستم‌عامل لینوکس است.
 *
 * dd if=/dev/urandom bs=1 count=64 2>/dev/null | base64 -w 0
 */
$cfg['secret_key'] = 'WMaTo4uv3uFl6MIl0Dm3Ek';

/*
 * زمانی که یک کاربر ایجاد می‌شود حالت آن را به صورت پیش فرض به حالت فعال تبدیل
 * می‌کند.
 */
$cfg['user_signup_active'] = true;
$cfg['user_avatar_default'] = SRC_BASE . '/var/avatar.svg';
$cfg['user_avatra_max_size'] = 2097152;

/*
 * روش‌های متفاوتی برای تعیین احراز اصالت وجود دارد. در اینجا تعیین می‌شود که
 * کدام روش احراز اصالت به کار گرفته شود. روش‌های تعیین شده به ترتیب در نظر
 * گرفته خواهند شد.
 */
$cfg['auth_backends'] = array(
        'Pluf_Auth_ModelBackend'
);

$cfg['pluf_use_rowpermission'] = true;

/*
 * ----------------------------------------------------------------------------
 * Logger section
 * ----------------------------------------------------------------------------
 */

$cfg['log_delayed'] = true;
$cfg['log_handler'] = 'Pluf_Log_File';
$cfg['log_level'] = Pluf_Log::ERROR;

$cfg['pluf_log_file'] = SRC_BASE . '/var/logs/pluf.log';

/*
 * ----------------------------------------------------------------------------
 * Data Base section
 * ----------------------------------------------------------------------------
 */
/*
 * نوع و نسخه پایگاه داده باید تعیین شود تا اتصال به آن بدون مشکل انجام شود.
 * معمولا
 * در نرم‌افزارها از پایگاه داده MySql‌ استفاده می‌کنیم برای همین ضمانتی برای
 * پایگاه‌های
 * دیگر وجود ندارد.
 */
$cfg['db_version'] = '5.5.33';
$cfg['db_engine'] = 'MySQL';

/*
 * برای دسترسی به پایگاه داده یک نام کاربری، گذرواژه، آدرس پایگاه داده و نام آن
 * مورد نیاز است. در این قسمت ابتدا تمام اطلاعات مورد نیاز برای اتصال به پایگاه
 * داده تعیین می‌شود.
 */
$cfg['db_login'] = 'root';
$cfg['db_password'] = '';
$cfg['db_server'] = 'localhost';
$cfg['db_database'] = 'test';

/*
 * گاهی نیاز است که در یک پایگاه داده چندین نرم‌افزار را نصب کرد از این رو به هر
 * یک از این نرم‌افزارهای کاربردی می‌توان یک پیش وند اضافه کرد و بدون نگرانی از
 * تداخل آنها، از آنها استفاده کرد.
 */
$cfg['db_table_prefix'] = '';

/*
 * ----------------------------------------------------------------------------
 * EMail section
 * ----------------------------------------------------------------------------
 */
/*
 * دو روش برای ارسال ایمیل در نظر گرفته شده است. در اینجا باید یکی از این روش‌ها
 * انتخاب شود. بسته به اینکه روش انتخاب شده چیست تنظیم‌های آن نیز متفاوت می‌شود.
 * روش‌های مورد حمایت عبارتند از:
 *
 * - mail
 * - sendmail
 * - smtp
 *
 * روشی که به عنوان پیش فرض استفاده می‌شود sendmail است.
 */
$cfg['mail_backend'] = 'sendmail';

/*
 * اگر روش sendmail است تنظیم‌های زیر برای آن در نظر گرفته شده است:
 */
$cfg['mail_sendmail_path'] = '/usr/bin/sendmail';
$cfg['sendmail_args'] = '-i';

/*
 * تنظیم‌هایی که برای روش smtp در نظر گرفته شده نیز در زیر آورده شده است:
 */
$cfg['mail_host'] = 'localhost';
$cfg['mail_port'] = 25;
$cfg['mail_auth'] = FALSE;
$cfg['mail_username'] = 'info@dpq.co.ir';
$cfg['mail_password'] = 'password';
$cfg['mail_localhost'] = 'localhost';
$cfg['mail_timeout'] = null;
$cfg['mail_verp'] = FALSE;
$cfg['mail_debug'] = FALSE;
$cfg['mail_persist'] = TRUE;

/*
 * ----------------------------------------------------------------------------
 * Template Section
 * ----------------------------------------------------------------------------
 */
/*
 * مسیر پرونده‌های موقت جایی است که تمام داده‌هایی غیر لازم ساخته می‌شود. برای
 * نمونه الگوها در این مسیر سر هم شده و نمایش اصلی ایجاد می‌شود. در این مسیر
 * باید امکان نوشتن فراهم باشد در غیر این صورت اجرا با خطا روبرو خواهد شد.
 */
$cfg['tmp_folder'] = SRC_BASE . '/var/tmp';

/*
 * در تمام مسیرهایی که در زیر تعیین می‌شود، الگوهای مناسب قرار گرفته شده و بر
 * اساس
 * آنها الگوها جستجو و یافت می‌شوند.
 * برای نمونه اگر الگو در مسیر اول پیدا شده به عنوان نتیجه برگردانده می‌شود در
 * غیر
 * این صورت مسیرهای بعدی دنبال می‌شود.
 */
$cfg['template_folders'] = array(
        SRC_BASE . '/src/templates',
        PLUF_BASE . '/SaaS/templates',
        PLUF_BASE . '/Seo/templates',
);

/*
 * زبان‌های مورد حمایت را تعیین می‌کند که همگی به صورت سمبل تعریف می‌شوند. زبان
 * اصلی
 * اولین زبانی در نظر گرفته می‌شود که در این فهرست آمده است.
 */
$cfg['languages'] = array(
        'fa',
        'en'
);

/*
 * تگهایی را تعیین می‌کند که علاوه بر تگ‌های استاندارد باید در زبان الگوها به
 * کار
 * گرفته شود. برای اطلاع بیشتر در مورد تگ‌ها و نحوه تعریف آنها مسیر زیر را
 * ببیند:
 *
 * https://github.com/phoenix-scholars/Pluf/tree/master/document
 */
$cfg['template_tags'] = array(
        'SaaSConfig' => 'SaaS_Template_Configuration',
        'now' => 'Pluf_Template_Tag_Now',
        'cfg' => 'Pluf_Template_Tag_Cfg',
        'spaView' => 'SaaS_Template_SapMainView'
);

/*
 * ----------------------------------------------------------------------------
 * Wiki
 * ----------------------------------------------------------------------------
 */
/*
 * مسیر مخزن‌های ثابت ویکی را تعیین می‌کند. در این مخزن‌ها مستندها بر اساس
 * قراردادها
 * مدیویکی نوشته می‌شوند و در پوشه‌هایی به نام زبانهای متفاوت ذخیره سازی
 * می‌شوند. تمام
 * مسیرها به ترتیب جستجو می‌شوند.
 */
$cfg['wiki_repositories'] = array(
        SRC_BASE . '/etc/wiki'
);

/*
 * ----------------------------------------------------------------------------
 * SaaS
 * ----------------------------------------------------------------------------
 */
$cfg['saas_mimetypes_db'] = SRC_BASE . '/etc/mime.types';
/*
 * فعال بودن لایه رایگان در سیستم را تعیین می‌کند. در صورتی که این مدل تجاری
 * فعال
 * باشد، نرم‌افزارهای بر اساس سطح دسترسی می‌توانند از نمایش‌های موجود استفاده
 * کنند
 * در غیر این صورت سیستم آنها را بلاکه می‌کند.
 */
$cfg['saas_freemium_enable'] = true;

/*
 * بالاترین سطح دسترسی در مدل فریمیوم را تعیین می‌کند. بسیاری از کاربردها مانند
 * ایجاد
 * یک تنظمی جدید تنها در بالاترین لایه نرم افزار در دسترس است.
 */
$cfg['saas_freemium_full'] = 5;

/*
 * برنامه‌های کاربردی در مخزن‌های متفاوتی قرار دارند. در این مسیر تمام
 * مخزن‌ها فهرست شده تا سیستم در صورت نیاز آنها را بازیابی و در اختیار
 * کاربران قرار دهد.
 */
$cfg['saas_spa_repository'] = SRC_BASE . '/spa';

// TODO: maso, 1395: اضافه کردن مسیر تمام مخازن نرم افزاری

$cfg['saas_spa_package'] = "/spa.json";
$cfg['saas_spa_view'] = '/main.html';
$cfg['saas_spa_default'] = 'test';

$cfg['saas_tenant_default'] = 'main';
$cfg['saas_tenant_match'] = array();

/*
 * Enable or disable multitenant mode.
 */
$cfg['multitenant'] = true;

/*
 * ----------------------------------------------------------------------------
 * SaaS Bank
 * ----------------------------------------------------------------------------
 */
/*
 * در مدل مرکزی، پشتوانه‌های پرداخت توسط مدیریت کل سامانه ایجاد و مدیریت می‌شود
 * در
 * حالی که در مدل غیر متمرکز هر ملک سامانه‌های پرداخت خود را به صورت مستقل
 * مدیریت
 * می‌کند.
 *
 * به صورت پیش فرض مدیریت پرداخت‌ها به صورت مرکزی کنترل می‌شود مگر اینکه
 * تنظیم‌های
 * سیستم روش غیر متمرکز را تعیین کند.
 */
$cfg['saas_bank_centeral'] = true;

/*
 * در فرآیند توسعه نیاز هست که فرآنید ایجاد یک پرداخت و مدیرت آن به صورت افلاین
 * انجام شود. با این تنظیم‌متورهای پروداخت به صورت پیش فرض به این کار می‌پردازند
 * و داده‌های فرضی ایجاد می‌کنند.
 */
$cfg['saas_bank_debug'] = true;

return $cfg;

