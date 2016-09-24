<?php
/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. http://dpq.co.ir
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
 *
 * @author maso
 *        
 */
class Role_Views_User extends Pluf_Views
{

    /**
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function find ($request, $match)
    {
        $per = new Pluf_Permission($match['id']);
        // XXX: maso, 1395: این فراخوانی رو برای تست نوشتم. خیلی تغییر نیاز داره
        $pag = new Pluf_Paginator(new Pluf_User());
        
        $sql = new Pluf_SQL('tenant=%s AND permission=%s AND owner_class=%s', 
                array(
                        $request->tenant->id,
                        $per->id,
                        // XXX: maso, 1395: user type is getting from config
                        'Pluf_User'
                ));
        $pag->forced_where = $sql;
        $pag->model_view = 'user_permission';
        $pag->list_filters = array(
                'administrator',
                'staff',
                'active'
        );
        $pag->configure(array(), 
                array( // search
                        'id'
                ), 
                array( // sort
                        'id'
                ));
        $pag->action = array();
        $pag->sort_order = array(
                'id',
                'DESC'
        );
        $pag->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($pag->render_object());
    }

    /**
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function create ($request, $match)
    {
        $model = new Pluf_Permission();
        $form = Pluf_Shortcuts_GetFormForModel($model, $request->REQUEST, 
                array());
        return new Pluf_HTTP_Response_Json($form->save());
    }

    /**
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function update ($request, $match)
    {
        $model = Pluf_Shortcuts_GetObjectOr404('Pluf_Permission', $match['id']);
        $form = Pluf_Shortcuts_GetFormForModel($model, $request->REQUEST, 
                array());
        return new Pluf_HTTP_Response_Json($form->save());
    }

    /**
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function get ($request, $match)
    {
        return new Pluf_HTTP_Response_Json(
                Pluf_Shortcuts_GetObjectOr404('Pluf_Permission', $match['id']));
    }

    /**
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function delete ($request, $match)
    {
        $model = Pluf_Shortcuts_GetObjectOr404('Pluf_Permission', $match['id']);
        $model2 = Pluf_Shortcuts_GetObjectOr404('Pluf_Permission', $match['id']);
        $model->delete();
        return new Pluf_HTTP_Response_Json($model2);
    }
}
