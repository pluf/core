# حلقه


## شکل ساده

فرم ساده به صورت زیر است

	{foreach $arrayVar as $forVal}
	...
	{/foreach}

## شکل کلی

فرم کامل به صورت زیر است که در آن کلید آرایه نیز تعیین می‌شود.

	{foreach $arrayVar as $forKey => $forVal}
	...
	{/foreach}


## یک نمونه ساده

در این نمونه فهرست تمام پروژه‌ها در خروجی نوشته شده و یک لینک برای آنها ایجاد می‌شود.

	<ul>
		{foreach $projects as $p}
			<li><a 
				href="{url 'IDF_Views_Project::home', array($p.shortname)}">
					{$p}
				</a>
			</li>
		{/foreach}
	</ul>
	
	
	
	