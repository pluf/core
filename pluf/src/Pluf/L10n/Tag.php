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
 * Template tag to display the country/language from the code.
 *
 * @param string Iso code
 * @param string ('country') or 'lang'
 * @param bool Print the result (true)
 */
class Pluf_L10n_Tag extends Pluf_Template_Tag
{
    function start($code, $what='country', $echo=true)
    {
        if ($what == 'country') {
            $cn = Pluf_L10n::getCountryCodes(true);
        } else {
            $cn = Pluf_L10n::getNativeLanguages();
        }
        if (!empty($cn[$code])) {
            if ($echo) {
                echo $cn[$code];
            } else {
                return $cn[$code];
            }
        }
    }
}
