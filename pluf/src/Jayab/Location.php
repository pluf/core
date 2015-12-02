<?php

/**
 * ساختار داده‌ای یک مکان را تعیین می‌کند.
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class Jayab_Location extends Pluf_Model
{

    /**
     * @brief مدل داده‌ای را بارگذاری می‌کند.
     *
     * @see Pluf_Model::init()
     */
    function init ()
    {
        $this->_a['table'] = 'jayab_location';
        $this->_a['model'] = 'Jayab_Location';
        $this->_model = 'Jayab_Location';
        $this->_a['cols'] = array(
               'id' =>  array(
                        'type' => 'Pluf_DB_Field_Sequence',
                        'blank' => true
                ),
                'reporter' => array(
                        'type' => 'Pluf_DB_Field_Foreignkey',
                        'model' => 'Pluf_User',
                        'blank' => true
                ),
                'owner_id' => array(
                        'type' => 'Pluf_DB_Field_Integer',
                        'blank' => false,
                ),
                'owner_class' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 50,
                ),
                'community' => array(
                        'type' => 'Pluf_DB_Field_Boolean',
                        'blank' => false,
                ),
                'tag' => array(
                        'type' => 'Pluf_DB_Field_Manytomany',
                        'model' => 'Jayab_Tag',
                        'blank' => true,
                        'relate_name' => 'tag'
                ),
                'label' => array(
                        'type' => 'Pluf_DB_Field_Manytomany',
                        'model' => 'KM_Label',
                        'blank' => true,
                        'relate_name' => 'location'
                ),
                'category' => array(
                        'type' => 'Pluf_DB_Field_Manytomany',
                        'model' => 'KM_Category',
                        'blank' => true,
                        'relate_name' => 'categories'
                ),
                'name' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 250,
                ),
                'description' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 250,
                ),
                'latitude' => array(
                        'type' => 'Pluf_DB_Field_Float',
                        'blank' => true,
                ),
                'longitude' => array(
                        'type' => 'Pluf_DB_Field_Float',
                        'blank' => true,
                ),
                'creation_dtime' => array(
                        'type' => 'Pluf_DB_Field_Datetime',
                        'blank' => true,
                ),
                'modif_dtime' => array(
                        'type' => 'Pluf_DB_Field_Datetime',
                        'blank' => true,
                )
        );
        $this->_a ['views'] = array (
                'with_tag' => array (
                        'join' => 'LEFT JOIN ' . $this->_con->pfx . 'jayab_location_jayab_tag_assoc ON jayab_location.id=' . $this->_con->pfx . 'jayab_location_jayab_tag_assoc.jayab_location_id',
                        'select' => $this->getSelect () . ',' . $this->_con->pfx . 'jayab_location_jayab_tag_assoc.jayab_tag_id as tag_id',
                        'props' => array (
                                'tag_id' => 'tag_id'
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
        if ($this->id == '') {
            $this->creation_dtime = gmdate('Y-m-d H:i:s');
            $this->community = true;
        }
        $this->modif_dtime = gmdate('Y-m-d H:i:s');
    }

    /**
     * حالت کار ایجاد شده را به روز می‌کند
     *
     * @see Pluf_Model::postSave()
     */
    function postSave ($create = false)
    {
        //
    }

    /**
     * یک خلاصه از آرای داده شده باری مکان ایجاد می‌کند.
     *
     *
     * @param unknown $location            
     * @return multitype:NULL
     */
    public function getVoteSummery ()
    {
        $like = Pluf::factory('Jayab_Vote')->getList(
                array(
                        'filter' => array(
                                'location=' . $this->id,
                                'jayab_vote.like = 1'
                        ),
                        'count' => true
                ));
        $dislike = Pluf::factory('Jayab_Vote')->getList(
                array(
                        'filter' => array(
                                'location=' . $this->id,
                                'jayab_vote.like != 1'
                        ),
                        'count' => true
                ));
        return array(
                'like' => $like[0]['nb_items'],
                'dislike' => $dislike[0]['nb_items']
        );
    }

    /**
     * یک رای را تعیین می‌کند
     *
     * در صورتی که رای کاربر به مکان وجود نداشته باشد مقدار تهی را به عنوان
     * نتیجه برمی‌گرداند
     *
     * @param unknown $user            
     * @param unknown $location            
     * @return unknown|NULL
     */
    public function getUserVote ($user)
    {
        $p = array(
                'filter' => array(
                        'voter=' . $user->id,
                        'location=' . $this->id
                )
        );
        $votes = Pluf::factory('Jayab_Vote')->getList($p);
        if (sizeof($votes) > 0)
            return $votes[0];
        return null;
    }
}