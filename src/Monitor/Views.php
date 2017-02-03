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

class Monitor_Views
{

    public static function findBean ($request, $match)
    {
        $content = new Pluf_Paginator(new Pluf_Monitor());
        // $sql = new Pluf_SQL('tenant=%s',
        // array(
        // $request->tenant->id
        // ));
        // $content->forced_where = $sql;
        $content->model_view = 'beans';
        $content->list_filters = array(
                'bean',
                'property',
                'title'
        );
        $list_display = array(
                'title' => __('title'),
                'bean' => __('bean name'),
                'property' => __('property'),
                'description' => __('description')
        );
        $search_fields = array(
                'title',
                'description',
                'bean',
                'property'
        );
        $sort_fields = array(
                'id',
                'name',
                'title',
                'bean',
                'property',
                'creation_date',
                'modif_dtime'
        );
        $content->configure($list_display, $search_fields, $sort_fields);
        $content->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($content->render_object());
    }

    public static function findProperty ($request, $match)
    {
        $content = new Pluf_Paginator(new Pluf_Monitor());
        $sql = new Pluf_SQL('bean=%s', 
                array(
                        $match['monitor']
                ));
        $content->forced_where = $sql;
        $content->model_view = 'properties';
        $content->list_filters = array(
                'bean',
                'property',
                'title'
        );
        $list_display = array(
                'title' => __('title'),
                'bean' => __('bean name'),
                'property' => __('property'),
                'description' => __('description')
        );
        $search_fields = array(
                'title',
                'description',
                'bean',
                'property'
        );
        $sort_fields = array(
                'id',
                'name',
                'title',
                'bean',
                'property',
                'creation_date',
                'modif_dtime'
        );
        $content->configure($list_display, $search_fields, $sort_fields);
        $content->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($content->render_object());
    }

    public static function call ($request, $match)
    {
        if (! isset($match['monitor'])) {
            throw new Exception(
                    'The monitor was not provided in the parameters.');
        }
        if (! isset($match['property'])) {
            throw new Exception(
                    'The property was not provided in the parameters.');
        }
        // Set the default
        $sql = new Pluf_SQL('bean=%s AND property=%s', 
                array(
                        $match['monitor'],
                        $match['property']
                ));
        $model = new Pluf_Monitor();
        $model = $model->getOne(
                array(
                        'filter' => $sql->gen()
                ));
        return new Pluf_HTTP_Response_Json($model->invoke($request, $match));
    }
}