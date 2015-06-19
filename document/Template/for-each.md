



	<ul>
		{foreach $projects as $p}
			<li><a 
				href="{url 'IDF_Views_Project::home', array($p.shortname)}">
					{$p}
				</a>
			</li>
		{/foreach}
	</ul>