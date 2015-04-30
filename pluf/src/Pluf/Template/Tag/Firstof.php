<?php
/* -*- tab-width: 4; indent-tabs-mode: nil; c-basic-offset: 4 -*- */
/*
# ***** BEGIN LICENSE BLOCK *****
# This file is part of Plume Framework, a simple PHP Application Framework.
# Copyright (C) 2001-2010 Loic d'Anterroches and contributors.
#
# Plume Framework is free software; you can redistribute it and/or modify
# it under the terms of the GNU Lesser General Public License as published by
# the Free Software Foundation; either version 2.1 of the License, or
# (at your option) any later version.
#
# Plume Framework is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU Lesser General Public License for more details.
#
# You should have received a copy of the GNU Lesser General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
#
# ***** END LICENSE BLOCK ***** */

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
 *     {$var1|safe}
 * {elseif $var2}
 *     {$var2|safe}
 * {elseif $var3}
 *     {$var3|safe}
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
class Pluf_Template_Tag_Firstof extends Pluf_Template_Tag
{
    /**
     * @see Pluf_Template_Tag::start()
     * @param string $token Variables to test.
     * @param string $fallback Literal string to used when all passed variables are false.
     * @throws InvalidArgumentException If no argument is provided.
     */
    public function start($tokens = array(), $fallback = null)
    {
        if (!is_array($tokens) || 0 === count($tokens)) {
            throw new InvalidArgumentException(
                '`firstof` tag requires at least one array as argument'
            );
        }
        $result = (string) $fallback;

        foreach ($tokens as $var) {
            if ($var) {
                $result = Pluf_Template::markSafe((string) $var);
                break;
            }
        }

        echo $result;
    }
}
