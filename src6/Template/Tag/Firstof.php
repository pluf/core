<?php
/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. (http://dpq.co.ir)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
namespace Pluf\Template\Tag;

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
class Firstof extends \Pluf\Template\Tag
{

    /**
     *
     * @see \Pluf\Template\Tag::start()
     * @param string $token
     *            Variables to test.
     * @param string $fallback
     *            Literal string to used when all passed variables are false.
     * @throws \InvalidArgumentException If no argument is provided.
     */
    public function start($tokens = array(), $fallback = null)
    {
        if (! is_array($tokens) || 0 === count($tokens)) {
            throw new \InvalidArgumentException('`firstof` tag requires at least one array as argument');
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
