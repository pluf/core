<?php

/**
 * ساختار داده‌ای یک مکان را تعیین می‌کند.
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class SaaSKM_Tag extends Pluf_Model
{

    /**
     * @brief مدل داده‌ای را بارگذاری می‌کند.
     *
     * @see Pluf_Model::init()
     */
    function init ()
    {
        $this->_a['table'] = 'saaskm_tag';
        $this->_a['model'] = 'SaaSKM_Tag';
        $this->_model = 'SaaSKM_Tag';
        $this->_a['cols'] = array(
                'id' => array(
                        'type' => 'Pluf_DB_Field_Sequence',
                        'blank' => true
                ),
                'tag_key' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 25
                ),
                'tag_value' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 25
                ),
                'tag_title' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 50
                ),
                'tag_description' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 250
                ),
                'tag_metainfo' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 250
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
                 * رابطه‌ها
                 */
                'tenant' => array(
                        'type' => 'Pluf_DB_Field_Foreignkey',
                        'model' => 'SaaS_Application',
                        'blank' => false,
                        'verbose' => __('tenant'),
                        'help_text' => __('Related tenant.')
                )
        );
        $this->_a['idx'] = array(
                'tag_combo_idx' => array(
                        'type' => 'unique',
                        'col' => 'tag_key, tag_value, tenant'
                )
        );
        
        $this->_a['views'] = array(
                'join_row' => array(
                        'select' => $this->getSelect() . ',' . $this->_con->pfx .
                                 'saaskm_tagrow.owner_class as owner_class' . ',' .
                                 $this->_con->pfx .
                                 'saaskm_tagrow.owner_id as owner_id',
                                'props' => array(
                                        'owner_class' => 'owner_class',
                                        'owner_id' => 'owner_id'
                                ),
                                'join' => 'LEFT JOIN ' . $this->_con->pfx .
                                 'saaskm_tagrow ON saaskm_tagrow.tag=' .
                                 $this->_con->pfx . 'saaskm_tag.id'
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

    /**
     * تک معادل با رشته ورودی را تعیین می‌کند.
     *
     * هر تگ به صورت منحصربه فرد با استفاده از یک رشته قابل نمایش است. ساختار
     * کلی تعریف یک تگ با رشته به صورت زیر است:
     *
     * {tag key}.{tag value}
     *
     * این فراخوانی این عبارت را دریافت کرده و تگ معادل با آن را تعیین می‌کند.
     * در صورتی
     * که این تگ موجود نباشد مقدار نا درستی به عنوان خروجی ارسال می‌شود.
     *
     * @param
     *            tag رشته‌ای که تگ را تعیین می‌کند برای نمونه 'aminity.bank'
     * @param
     *            tenant ملک معادل
     * @return false|SaaSKM_Tag The matching permission or false.
     */
    public static function getFromString ($tenant, $tag, $create = false)
    {
        list ($key, $value) = explode('.', trim($tag));
        $sql = new Pluf_SQL('tag_key=%s AND tag_value=%s AND tenant=%s', 
                array(
                        $key,
                        $value,
                        $tenant->id
                ));
        $tags = Pluf::factory('SaaSKM_Tag')->getList(
                array(
                        'filter' => $sql->gen()
                ));
        if ($tags->count() >= 1) {
            return $tags[0];
        }
        if ($create) {
            $temp = new SaaSKM_Tag();
            $temp->tag_key = $key;
            $temp->tag_value = $value;
            $temp->tag_title = 'title';
            $temp->tag_description = 'tag description';
            $temp->tenant = $tenant;
            if ($temp->create()) {
                return $temp;
            }
        }
        return false;
    }
}