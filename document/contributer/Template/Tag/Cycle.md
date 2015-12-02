
# Template tag <code>cycle</code>.

Cycle among the given strings or variables each time this tag is 
encountered.

Within a loop, cycles among the given strings each time through the loop:

	{foreach $some_list as $obj}
	    <tr class="{cycle 'row1', 'row2'}">
	        ...
	    </tr>
	{/foreach}

You can use variables, too. For example, if you have two
template variables, $rowvalue1 and $rowvalue2, you can
cycle between their values like this:

	{foreach $some_list as $obj}
	    <tr class="{cycle $rowvalue1, $rowvalue2}">
	        ...
	    </tr>
	{/foreach}

You can mix variables and strings:

	{foreach $some_list as $obj}
	    <tr class="{cycle 'row1', rowvalue2, 'row3'}">
	        ...
	    </tr>
	{/foreach}

In some cases you might want to refer to the next value of a cycle
from outside of a loop. To do this, just group the arguments into
an array and give the {cycle} tag name last, like this:

	{cycle array('row1', 'row2'), 'rowcolors'}

From then on, you can insert the current value of the cycle
wherever you'd like in your template:

	<tr class="{cycle $rowcolors}">...</tr>
	<tr class="{cycle $rowcolors}">...</tr>

Based on concepts from the Django cycle template tag.