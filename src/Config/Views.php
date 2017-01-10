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
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('Pluf_Shortcuts_GetFormForModel');

/**
 * لایه نمایش مدیریت گروه‌ها را به صورت پیش فرض ایجاد می‌کند
 *
 * @author maso
 *        
 */
class Config_Views extends Pluf_Views
{

    /**
     * مقدار یک خصوصیت را تعیین می‌کند.
     *
     * @param Pluf_HTTP_Request $request            
     * @param array $match            
     */
    public function get ($request, $match)
    { // Set the default
        $sql = new Pluf_SQL('tenant=%s AND type=%s AND saas_configuration.key=%s', 
                array(
                        $request->tenant->id,
                        SaaS_ConfigurationType::SYSTEM,
                        $match['key']
                ));
        $model = new SaaS_Configuration();
        $model = $model->getOne(
                array(
                        'filter' => $sql->gen()
                ));
        if (! isset($model)) {
            $model = new SaaS_Configuration();
        }
        return new Pluf_HTTP_Response_Json($model);
    }

    /**
     * یک تنظیم را به روز می‌کند.
     *
     * در صورتی که تنظیم موجود نباشد آن را ایجاد کرده و مقدار تیعیین شده را
     * در آن قرار می‌دهد.
     *
     * @param Pluf_HTTP_Request $request            
     * @param array $match            
     */
    public function update ($request, $match)
    { // Set the default
        $sql = new Pluf_SQL('tenant=%s AND type=%s AND saas_configuration.key=%s', 
                array(
                        $request->tenant->id,
                        SaaS_ConfigurationType::SYSTEM,
                        $match['key']
                ));
        $model = new SaaS_Configuration();
        $model = $model->getOne(
                array(
                        'filter' => $sql->gen()
                ));
        if (! isset($model)) {
            $model = new SaaS_Configuration();
            $form = Pluf_Shortcuts_GetFormForModel($model, $request->REQUEST);
            $model = $form->save(false);
            $model->tenant = $request->tenant;
            $model->type = SaaS_ConfigurationType::SYSTEM;
            $model->key = $match['key'];
            $model->create();
        } else {
            $form = Pluf_Shortcuts_GetFormForModel($model, $request->REQUEST);
            $model = $form->save();
        }
        return new Pluf_HTTP_Response_Json($model);
    }

    /**
     * مقدار یک خصوصیت را تعیین می‌کند.
     *
     * @param Pluf_HTTP_Request $request            
     * @param array $match            
     */
    public function delete ($request, $match)
    {
        $sql = new Pluf_SQL('tenant=%s AND type=%s AND saas_configuration.key=%s', 
                array(
                        $request->tenant->id,
                        SaaS_ConfigurationType::SYSTEM,
                        $match['key']
                ));
        $model = new SaaS_Configuration();
        $model = $model->getOne(
                array(
                        'filter' => $sql->gen()
                ));
        if (! isset($model)) {
            $model = new SaaS_Configuration();
        } else {
            $model->delete();
        }
        return new Pluf_HTTP_Response_Json($model);
    }
}
