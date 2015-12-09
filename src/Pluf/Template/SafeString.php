<?php
/* -*- tab-width: 4; indent-tabs-mode: nil; c-basic-offset: 4 -*- */
/*
# ***** BEGIN LICENSE BLOCK *****
# This file is part of Plume Framework, a simple PHP Application Framework.
# Copyright (C) 2001-2007 Loic d'Anterroches and contributors.
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
 * A string already escaped to display in a template.
 */
class Pluf_Template_SafeString
{
    public $value = '';

    function __construct($mixed, $safe=false)
    {
        if (is_object($mixed) and 'Pluf_Template_SafeString' == get_class($mixed)) {
            $this->value = $mixed->value;
        } else {
            $this->value = ($safe) ? $mixed : htmlspecialchars($mixed, ENT_COMPAT, 'UTF-8');
        }
    }

    function __toString()
    {
        return $this->value;
    }

    public static function markSafe($string)
    {
        return new Pluf_Template_SafeString($string, true);
    }
}