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
 * فرم کلی ایجاد یک متور پرداخت جدید
 * 
 * این فرم به صورت خودکار پارامترهای مورد استفاده در متورهای پرداخت را تعیین کرده
 * و بر اساس آن فیلدهای دریافتی را فیلتر می‌کند. در نهای بر اساس این داده‌ها یک متور
 * پرداخت جدید ایجاد خواهد شد.
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class SaaSBank_Form_BackendNew extends Pluf_Form
{

    /**
     * ملکی که متور به آن تعلق دارد
     * 
     * @var unknown
     */
    var $tenant;

    
    /**
     * نوع متور پرداخت را تعیین می‌کند.
     * 
     * @var unknown
     */
    var $engine;

    /*
     * 
     */
    public function initFields ($extra = array())
    {
        $this->tenant = $extra['tenant'];
        $this->engine = $extra['engine'];
        
        $params = $this->engine->getParameters();
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
    function save ($commit = true)
    {
        if (! $this->isValid()) {
            // TODO: maso, 1395: باید از خطای مدل فرم استفاده شود.
            throw new Pluf_Exception(
                    __('Cannot save the backend from an invalid form.'));
        }
        // Set attributes
        $backend = new SaaSBank_Backend();
        $backend->setFromFormData($this->cleaned_data);
        $backend->tenant = $this->tenant;
        $backend->engine = $this->engine->getType();
        // TODO: maso, 1395: تنها پارامترهایی اضافه باید به صورت کد شده در
        // موجودیت قرار گیرد.
        if ($commit) {
            if (! $backend->create()) {
                throw new Pluf_Exception(__('Fail to create the backend.'));
            }
        }
        return $backend;
    }
}

