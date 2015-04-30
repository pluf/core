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
     * @see Pluf_Template_Tag::start()
     * @param string $token Format to be applied.
     */
    public function start($token)
    {
        echo date($token);
    }
}
