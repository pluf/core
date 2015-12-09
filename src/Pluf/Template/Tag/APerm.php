<?php

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
     *
     * @param
     *            string Variable to get the permission
     * @param
     *            Pluf_User
     * @param
     *            string Permission string
     * @param
     *            mixed Optional Pluf_Model if using row level permission (null)
     */
    function start ($var, $user, $perm, $object = null)
    {
        $this->context->set($var, $user->hasPerm($perm, $object));
    }
}
