<?php

/**
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class SaaSKM_TagRow extends Pluf_Model
{

    function init ()
    {
        $this->_a['table'] = 'saaskm_tagrow';
        $this->_a['model'] = 'saaskm_tagrow';
        $this->_model = 'SaaSKM_TagRow';
        $this->_a['cols'] = array(
                'id' => array(
                        'type' => 'Pluf_DB_Field_Sequence',
                        'blank' => true
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
                'creation_dtime' => array(
                        'type' => 'Pluf_DB_Field_Datetime',
                        'blank' => true,
                        'verbose' => __('creation date')
                ),
                'modif_dtime' => array(
                        'type' => 'Pluf_DB_Field_Datetime',
                        'blank' => true,
                        'verbose' => __('modification date')
                ),
                /*
                 * relations 
                 */
                'tag' => array(
                        'type' => 'Pluf_DB_Field_Foreignkey',
                        'model' => 'SaaSKM_Tag',
                        'blank' => false,
                        'verbose' => __('tag')
                ),
                'tenant' => array(
                        'type' => 'Pluf_DB_Field_Foreignkey',
                        'model' => 'SaaS_Application',
                        'blank' => false,
                        'verbose' => __('tenant'),
                        'help_text' => __('Related tenant.')
                )
        );
        
        $this->_a['idx'] = array(
                'tagrow_combo_idx' => array(
                        'type' => 'unique',
                        'col' => 'tag, owner_id, owner_class, tenant'
                )
        );
        $t_perm = $this->_con->pfx . 'saaskm_tag';
        $this->_a['views'] = array(
                'join_tag' => array(
                        'select' => $this->getSelect() . ', ' . $t_perm .
                                 '.tag_key AS tag_key, ' . $t_perm .
                                 '.tag_value AS tag_value ' . $t_perm .
                                 '.tag_title AS tag_title ' . $t_perm .
                                 '.tag_description AS tag_description ',
                                'join' => 'LEFT JOIN ' . $t_perm . ' ON ' .
                                 $t_perm . '.id=tag_id',
                                'props' => array(
                                        'tag_key' => 'tag_key',
                                        'tag_value' => 'tag_value',
                                        'tag_title' => 'tag_title',
                                        'tag_description' => 'tag_description'
                                )
                )
        );
    }

    /**
     * \brief پیش ذخیره را انجام می‌دهد
     *
     * @param $create حالت
     *            ساخت یا به روز رسانی را تعیین می‌کند
     */
    function preSave ($create = false)
    {
        if ($this->isAnonymous()) {
            $this->creation_dtime = gmdate('Y-m-d H:i:s');
        }
        $this->modif_dtime = gmdate('Y-m-d H:i:s');
    }

    public static function add ($tenant, $owner, $tag, $force = false)
    {
        if (! is_object($tag)) {
            // Find matching tag
            $found = SaaSKM_Tag::getFromString($tenant, $tag, $force);
            if (false === $found) {
                throw new Pluf_Exception(
                        sprintf('The tag %s does not exist.', $tag));
            }
            $perm = $found;
        }
        SaaSKM_TagRow::remove($tenant, $owner, $perm);
        $nperm = new SaaSKM_TagRow();
        $nperm->owner_id = $owner->id;
        $nperm->owner_class = $owner->_a['model'];
        $nperm->tenant = $tenant;
        $nperm->tag = $perm;
        $nperm->create();
        return true;
    }

    public static function remove ($tenant, $owner, $tag)
    {
        if (! is_object($tag)) {
            $found = SaaSKM_Tag::getFromString($tenant, $tag);
            if (false === $found) {
                throw new Pluf_Exception(
                        sprintf('The tag %s does not exist.', $tag));
            }
            $tag = $found;
        }
        $growp = new SaaSKM_TagRow();
        $sql = new Pluf_SQL(
                'owner_id=%s AND owner_class=%s AND tag=%s AND tenant=%s', 
                array(
                        $owner->id,
                        $owner->_a['model'],
                        $tag->id,
                        $tenant->id
                ));
        $rows = $growp->getList(
                array(
                        'filter' => $sql->gen()
                ));
        foreach ($rows as $p) {
            $p->delete();
        }
        return true;
    }
}