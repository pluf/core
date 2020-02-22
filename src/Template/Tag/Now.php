<?php
namespace Pluf\Template\Tag;

use Pluf\Template\Tag;

/**
 * Template tag <code>now</code>.
 *
 * Displays the date, formatted according to the given string.
 *
 * Sample usage:
 * <code>It is {now "jS F Y H:i"}</code>
 *
 * Based on concepts from the Django now template tag.
 *
 * @link http://php.net/date for all the possible values.
 */
class Now extends Tag
{

    /**
     *
     * @see Tag::start()
     * @param string $token
     *            Format to be applied.
     */
    public function start($token)
    {
        echo date($token);
    }
}
