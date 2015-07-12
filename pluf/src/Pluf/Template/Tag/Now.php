<?php

/**
 * Template tag <code>now</code>.
 *
 * Displays the date, formatted according to the given string.
 *
 * Sample usage:
 *     <code>It is {now "jS F Y H:i"}</code>
 *
 * Based on concepts from the Django now template tag.
 *
 * @link http://php.net/date for all the possible values.
 */
class Pluf_Template_Tag_Now extends Pluf_Template_Tag
{

    /**
     *
     * @see Pluf_Template_Tag::start()
     * @param string $token
     *            Format to be applied.
     */
    public function start ($token)
    {
        echo date($token);
    }
}
