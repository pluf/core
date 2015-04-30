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
 * Display a configuration variable.
 *
 * It filter to prevent some configuration variables to be displayed.
 *
 */
class Pluf_Template_Tag_Cfg extends Pluf_Template_Tag
{
    /**
     * Display the configuration variable.
     * 
     * @param string Configuration variable.
     * @param mixed Default value to return display ('').
     * @param bool Display the value (true).
     * @param string Prefix to set to the variable if not displayed ('cfg_').
     */
    function start($cfg, $default='', $display=true, $prefix='cfg_')
    {
        if ($cfg != 'secret_key'
            or 0 !== strpos($cfg, 'db_')) {
            if ($display) {
                echo Pluf::f($cfg, $default);
            } else {
                $this->context->set($prefix.$cfg, Pluf::f($cfg, $default));
            }
        }
    }
}
