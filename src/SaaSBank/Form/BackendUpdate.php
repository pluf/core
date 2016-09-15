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
class SaaSBank_Form_BackendUpdate extends Pluf_Form
{

    
    /**
     * نوع متور پرداخت را تعیین می‌کند.
     * 
     * @var unknown
     */
    var $backend;

    /*
     * 
     */
    public function initFields ($extra = array())
    {
        $this->backend = $extra['backend'];
        
        $engin =  $this->backend->get_engine();
        $params = $engin->getParameters();
        foreach ($params['children'] as $param) {
            $options = array(
                    // 'required' => $param['required']
                    'required' => false
            );
            $field = null;
            switch ($param['type']) {
                case 'Integer':
                    $field = new Pluf_Form_Field_Integer($options);
                    break;
                case 'String':
                    $field = new Pluf_Form_Field_Varchar($options);
                    break;
            }
            $this->fields[$param['name']] = $field;
        }
    }

    /**
     * یک نمونه جدید از متور پرداخت ایجاد می‌کند.
     *
     * بر اساس داده‌هایی که توسط کاربر تعیین شده است یک متور جدید پرداخت ایجاد
     * می‌کند و آن را به متورهای پرداخت ملک اضافه می‌کند.
     *
     * @param string $commit            
     * @throws Pluf_Exception
     * @return SaaSBank_Backend
     */
    function update ($commit = true)
    {
        if (! $this->isValid()) {
            // TODO: maso, 1395: باید از خطای مدل فرم استفاده شود.
            throw new Pluf_Exception(
                    __('Cannot save the backend from an invalid form.'));
        }
        // Set attributes
        $this->backend->setFromFormData($this->cleaned_data);
        // TODO: maso, 1395: تنها پارامترهایی اضافه باید به صورت کد شده در
        // موجودیت قرار گیرد.
        if ($commit) {
            if (! $this->backend->update()) {
                throw new Pluf_Exception(__('Fail to create the backend.'));
            }
        }
        return $this->backend;
    }
}

