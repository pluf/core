# Template tag <code>firstof</code>.

Outputs the first variable passed that is not false, without escaping.
Outputs nothing if all the passed variables are false.

Sample usage:

	{firstof array($var1, $var2, $var3)}

This is equivalent to:

	{if $var1}
	    {$var1|safe}
	{elseif $var2}
	    {$var2|safe}
	{elseif $var3}
	    {$var3|safe}
	{/if}

You can also use a literal string as a fallback value in case all
passed variables are false:

	{firstof array($var1, $var2, $var3), "fallback value"}

Based on concepts from the Django firstof template tag.