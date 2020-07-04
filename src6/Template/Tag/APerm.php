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
 */
class APerm extends \Pluf\Template\Tag
{

    /**
     *
     * @param
     *            string Variable to get the permission
     * @param
     *            User
     * @param
     *            string Permission string
     * @param
     *            mixed Optional Pluf_Model if using row level permission (null)
     */
    function start($var, $user, $perm, $object = null)
    {
        $this->context->set($var, $user->hasPerm($perm, $object));
    }
}
