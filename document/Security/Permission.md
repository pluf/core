# مجوز

مجوز یک ساختار داده‌ای در سیستم است که دسترسی‌ها به لایه نمایش را کنترل می‌کند

## تعریف یک مجوز

    // Install the permissions
    $perm = new Pluf_Permission();
    $perm->name = 'Project membership';
    $perm->code_name = 'project-member';
    $perm->description = 'Permission given to project members.';
    $perm->application = 'IDF';
    $perm->create();

 
## استفاده از مجوزها


# دادن مجوز