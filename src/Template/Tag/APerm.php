<?php
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
     *            mixed Optional Model if using row level permission (null)
     */
    function start($var, $user, $perm, $object = null)
    {
        $this->context->set($var, $user->hasPerm($perm, $object));
    }
}
