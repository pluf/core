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

/**
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class Bank_Form_ReceiptNew extends Pluf_Form
{

    /**
     * ملکی که متور به آن تعلق دارد
     *
     * @var unknown
     */
    var $tenant;

    /*
     *
     */
    public function initFields ($extra = array())
    {
        $this->tenant = $extra['tenant'];
        // $this->engine = $extra['engine'];
        
        $this->fields['amount'] = new Pluf_Form_Field_Integer(
                array(
                        'required' => true,
                        'label' => 'amtoun'
                ));
        
        $this->fields['title'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => true,
                        'label' => 'title'
                ));
        
        $this->fields['description'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => true,
                        'label' => 'description'
                ));
        
        $this->fields['email'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => 'email'
                ));
        $this->fields['phone'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => false,
                        'label' => 'phone'
                ));
        $this->fields['callbackURL'] = new Pluf_Form_Field_Varchar(
                array(
                        'required' => true,
                        'label' => 'callbackURL'
                ));
        $this->fields['backend'] = new Pluf_Form_Field_Integer(
                array(
                        'required' => true,
                        'label' => 'backend'
                ));
    }

    function clean_backend ()
    {
        $backend = Pluf::factory('Bank_Backend', 
                $this->cleaned_data['backend']);
        if ($backend->isAnonymous()) {
            throw new Pluf_Exception('backend not found');
        }
        // XXX: maso, 1395: گرفتن پشتوانه
        return $backend->id;
    }

    /**
     *
     * @param string $commit            
     * @throws Pluf_Exception
     * @return Bank_Backend
     */
    function save ($commit = true)
    {
        if (! $this->isValid()) {
            // TODO: maso, 1395: باید از خطای مدل فرم استفاده شود.
            throw new Pluf_Exception(
                    'Cannot save a receipt from an invalid form.');
        }
        // Set attributes
        $receipt = new Bank_Receipt();
        $receipt->setFromFormData($this->cleaned_data);
        $receipt->tenant = $this->tenant;
        $receipt->secure_id = $this->getSecureKey();
        // موجودیت قرار گیرد.
        if ($commit) {
            if (! $receipt->create()) {
                throw new Pluf_Exception('fail to create the recipt.');
            }
        }
        return $receipt;
    }

    /**
     * یک کد جدید برای موجودیت ایجاد می‌کند.
     *
     * @return unknown
     */
    private function getSecureKey ()
    {
        $recipt = new Bank_Receipt();
        while (1) {
            $key = sha1(
                    microtime() . rand(0, 123456789) . Pluf::f('secret_key'));
            $sess = $recipt->getList(
                    array(
                            'filter' => 'secure_id=\'' . $key . '\''
                    ));
            if (count($sess) == 0) {
                break;
            }
        }
        return $key;
    }
}

