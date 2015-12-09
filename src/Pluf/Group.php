<?php

/**
 * مدل داده‌ای یک گروه را ایجاد می‌کند.
 * 
 * @author maso<mostafa.barmshory@dpq.co.ir>
 *
 */
class Pluf_Group extends Pluf_Model
{
    public $_model = 'Pluf_Group';

    function init()
    {
        $this->_a['verbose'] = __('group');
        $this->_a['table'] = 'groups';
        $this->_a['model'] = 'Pluf_Group';
        $this->_a['cols'] = array(
                             // It is mandatory to have an "id" column.
                            'id' =>
                            array(
                            	'type' => 'Pluf_DB_Field_Sequence',
                            	'blank' => true, 
                            	),
			        		'version' =>
			        		array(
			        			'type' => 'Pluf_DB_Field_Integer',
			        			'blank' => true,
			        			),
                            'name' =>
                            array(
                                  'type' => 'Pluf_DB_Field_Varchar',
                                  'blank' => false,
                                  'size' => 50,
                                  'verbose' => __('name'),
                                  ),
                            'description' => 
                            array(
                                  'type' => 'Pluf_DB_Field_Varchar',
                                  'blank' => false,
                                  'size' => 250,
                                  'verbose' => __('description'),
                                  ),
                            'permissions' =>
                            array(
                                  'type' => 'Pluf_DB_Field_Manytomany', 
                                  'blank' => true,
                                  'model' => 'Pluf_Permission',
                                  ),
                            );
        if (Pluf::f('pluf_custom_group',false)) $this->extended_init();
    }

    /**
     * Hook for extended class
     */
    function extended_init()
    {
        return;
    }

    function __toString()
    {
        return $this->name;
    }

    /**
     * Predelete to drop the row level permissions.
     */
    function preDelete()
    {
        if (Pluf::f('pluf_use_rowpermission', false)) {
            $_rpt = Pluf::factory('Pluf_RowPermission')->getSqlTable();
            $sql = new Pluf_SQL('owner_class=%s AND owner_id=%s',
                                array($this->_a['model'], $this->_data['id']));
            $this->_con->execute('DELETE FROM '.$_rpt.' WHERE '.$sql->gen());
        }
    }
}
