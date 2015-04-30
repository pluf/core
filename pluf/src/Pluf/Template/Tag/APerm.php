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
 * Assign a permission to a template variable.
 *
 * This template tag is available by default under the name
 * aperm. Example of usage:
 *
 * <code>
 * {aperm 'can_drive', $user, 'MyApp.can_drive'}
 * {aperm 'can_drive_big_car', $user, 'MyApp.can_drive', $bigcar}
 * {if $can_drive}Can drive!{/if}
 * </code>
 *
 */
class Pluf_Template_Tag_APerm extends Pluf_Template_Tag
{
    /**
     * @param string Variable to get the permission
     * @param Pluf_User
     * @param string Permission string
     * @param mixed Optional Pluf_Model if using row level permission (null)
     */
    function start($var, $user, $perm, $object=null)
    {
        $this->context->set($var, $user->hasPerm($perm, $object));
    }
}
