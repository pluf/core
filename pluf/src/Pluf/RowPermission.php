<?php
/**
 * ساختار داده‌ای گواهی‌ها
 * 
 * با استفاده از این ساختار داده‌ای تعیین می‌شود که کاربران چه گواهی‌هایی
 * در سیستم دارند.
 * 
 * 
 * @author maso
 *
 */
class Pluf_RowPermission extends Pluf_Model
{

    public $_model = 'Pluf_RowPermission';

    function init ()
    {
        $this->_a['table'] = 'rowpermissions';
        $this->_a['model'] = 'Pluf_RowPermission';
        $this->_a['cols'] = array(
                // It is mandatory to have an "id" column.
                'id' => array(
                        'type' => 'Pluf_DB_Field_Sequence',
                        // It is automatically added.
                        'blank' => true
                ),
                'version' => array(
                        'type' => 'Pluf_DB_Field_Integer',
                        'blank' => true
                ),
                'model_id' => array(
                        'type' => 'Pluf_DB_Field_Integer',
                        'blank' => false,
                        'verbose' => __('model ID')
                ),
                'model_class' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 50,
                        'verbose' => __('model class')
                ),
                'owner_id' => array(
                        'type' => 'Pluf_DB_Field_Integer',
                        'blank' => false,
                        'verbose' => __('owner ID')
                ),
                'owner_class' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 50,
                        'verbose' => __('owner class'),
                        'help_text' => __(
                                'For example Pluf_User or Pluf_Group.')
                ),
                'negative' => array(
                        'type' => 'Pluf_DB_Field_Boolean',
                        'blank' => false,
                        'default' => false,
                        'verbose' => __('do not have the permission')
                ),
                'permission' => array(
                        'type' => 'Pluf_DB_Field_Foreignkey',
                        'model' => 'Pluf_Permission',
                        'blank' => false,
                        'verbose' => __('permission')
                )
        );
        $this->_a['idx'] = array(
                'permission_combo_idx' => array(
                        'type' => 'unique',
                        'col' => 'model_id, model_class, owner_id, owner_class, permission'
                )
        );
        $t_perm = $this->_con->pfx . 'permissions';
        $this->_a['views'] = array(
                'join_permission' => array(
                        'select' => $this->getSelect() . ', ' . $t_perm .
                                 '.code_name AS code_name, ' . $t_perm .
                                 '.application AS application ',
                                'join' => 'LEFT JOIN ' . $t_perm . ' ON ' .
                                 $t_perm . '.id=permission',
                                'props' => array(
                                        'code_name' => 'code_name',
                                        'application' => 'application'
                                )
                )
        );
    }

    public static function add ($owner, $object, $perm, $negative = false)
    {
        if (! is_object($perm)) {
            // Find matching permission
            $found = Pluf_Permission::getFromString($perm);
            if (false === $found) {
                throw new Exception(
                        sprintf('The permission %s does not exist.', $perm));
            }
            $perm = $found;
        }
        Pluf_RowPermission::remove($owner, $object, $perm);
        $nperm = new Pluf_RowPermission();
        $nperm->owner_id = $owner->id;
        $nperm->owner_class = $owner->_a['model'];
        $nperm->model_id = $object->id;
        $nperm->model_class = $object->_a['model'];
        $nperm->permission = $perm;
        $nperm->negative = $negative;
        $nperm->create();
        return true;
    }

    public static function remove ($owner, $object, $perm)
    {
        if (! is_object($perm)) {
            $found = Pluf_Permission::getFromString($perm);
            if (false === $found) {
                throw new Exception(
                        sprintf('The permission %s does not exist.', $perm));
            }
            $perm = $found;
        }
        $growp = new Pluf_RowPermission();
        $sql = new Pluf_SQL(
                'owner_id=%s AND owner_class=%s AND model_id=%s AND model_class=%s AND permission=%s', 
                array(
                        $owner->id,
                        $owner->_a['model'],
                        $object->id,
                        $object->_a['model'],
                        $perm->id
                ));
        $perms = $growp->getList(array(
                'filter' => $sql->gen()
        ));
        foreach ($perms as $p) {
            $p->delete();
        }
        return true;
    }
}
