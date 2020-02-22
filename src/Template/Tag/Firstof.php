<?php
namespace Pluf\Template\Tag;

use InvalidArgumentException;
use Pluf\Template\Tag;
use Pluf\Template;

/**
 * Template tag <code>firstof</code>.
 *
 * Outputs the first variable passed that is not false, without escaping.
 * Outputs nothing if all the passed variables are false.
 *
 * Sample usage:
 *
 * <code>{firstof array($var1, $var2, $var3)}</code>
 *
 * This is equivalent to:
 *
 * <code>
 * {if $var1}
 * {$var1|safe}
 * {elseif $var2}
 * {$var2|safe}
 * {elseif $var3}
 * {$var3|safe}
 * {/if}
 * </code>
 *
 * You can also use a literal string as a fallback value in case all
 * passed variables are false:
 *
 * <code>{firstof array($var1, $var2, $var3), "fallback value"}</code>
 *
 * Based on concepts from the Django firstof template tag.
 */
class Firstof extends Tag
{

    /**
     *
     * @see Tag::start()
     * @param string $token
     *            Variables to test.
     * @param string $fallback
     *            Literal string to used when all passed variables are false.
     * @throws InvalidArgumentException If no argument is provided.
     */
    public function start($tokens = array(), $fallback = null)
    {
        if (! is_array($tokens) || 0 === count($tokens)) {
            throw new InvalidArgumentException('`firstof` tag requires at least one array as argument');
        }
        $result = (string) $fallback;

        foreach ($tokens as $var) {
            if ($var) {
                $result = Template::markSafe((string) $var);
                break;
            }
        }

        echo $result;
    }
}
