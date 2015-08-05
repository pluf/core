<?php

/**
 * ساختارهای پیام سیستم را ایجاد می‌کند
 * 
 * این پیام‌ها توسط سیستم ایجاد شده و برای کاربران ارسال می‌شود. این پیام‌ها
 * تغییراتی که مربوط به داده‌ها و یا موجودیت‌های کاربر باشد صادر می‌شود و باید
 * به مرور زمان از بین برود.
 * 
 * @author maso
 *
 */
class Pluf_Message extends Pluf_Model
{

    public $_model = 'Pluf_Message';

    function init ()
    {
        $this->_a['table'] = 'messages';
        $this->_a['model'] = 'Pluf_Message';
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
                'user' => array(
                        'type' => 'Pluf_DB_Field_Foreignkey',
                        'model' => Pluf::f('pluf_custom_user', 'Pluf_User'),
                        'blank' => false,
                        'verbose' => __('user')
                ),
                'message' => array(
                        'type' => 'Pluf_DB_Field_Text',
                        'blank' => false
                )
        );
    }

    function __toString ()
    {
        return $this->message;
    }
}
