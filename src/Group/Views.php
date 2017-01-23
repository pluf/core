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
 * Manages groups
 *
 * @author maso
 * @author hadi
 *        
 */
class Group_Views extends Pluf_Views
{

    /**
     * Creates new group.
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public static function create($request, $match)
    {
        $group = new Pluf_Group();
        $form = Pluf_Shortcuts_GetFormForModel($group, $request->REQUEST, array());
        $group = $form->save(false);
        $group->create();
        return new Pluf_HTTP_Response_Json($group);
    }

    /**
     * Returns list of groups with specified condition.
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public static function find($request, $match)
    {
        $pag = new Pluf_Paginator(new Pluf_Group());
        $pag->items_per_page = Group_Views::getListCount($request);
        $pag->list_filters = array(
            'tenant',
            'version'
        );
        $search_fields = array(
            'name',
            'description'
        );
        $list_display = array(
            'name' => __('name'),
            'description' => __('description')
        );
        $sort_fields = array(
            'id',
            'name',
            'description'
        );
        $pag->configure($list_display, $search_fields, $sort_fields);
        $pag->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($pag->render_object());
    }

    /**
     * Returns information of a group.
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public static function get($request, $match)
    {
        $group = Pluf_Shortcuts_GetObjectOr404('Pluf_Group', $match['id']);
        return new Pluf_HTTP_Response_Json($group);
    }

    /**
     * Updates information of a group.
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public static function update($request, $match)
    {
        $model = Pluf_Shortcuts_GetObjectOr404('Pluf_Group', $match['id']);
        $form = Pluf_Shortcuts_GetFormForUpdateModel($model, $request->REQUEST, array());
        $model = $form->save();
        $request->user->setMessage(sprintf(__('Group data has been updated.'), (string) $model));
        return new Pluf_HTTP_Response_Json($model);
    }

    /**
     * Deletes a group.
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public static function delete($request, $match)
    {
        $model = Pluf_Shortcuts_GetObjectOr404('Pluf_Group', $match['id']);
        $modelCopy = new Pluf_Group($match['id']);
        $modelCopy->id = 0;
        if ($model->delete()) {
            return new Pluf_HTTP_Response_Json($modelCopy);
        }
        throw new Pluf_HTTP_Error500('Unexpected error while removing group: ' . $modelCopy->name);
    }

}
