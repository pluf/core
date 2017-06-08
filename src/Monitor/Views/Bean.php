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
Pluf::loadFunction('Monitor_Shortcuts_UserLevel');
Pluf::loadFunction('Monitor_Shortcuts_BeansToPrometheus');

class Monitor_Views_Bean
{

    /**
     *
     * @param Pluf_HTTP_Request $request            
     * @param array $match            
     * @return unknown
     */
    public function find ($request, $match)
    {
        $content = new Pluf_Paginator(new Pluf_Monitor());
        $sql = new Pluf_SQL('level>=%s', 
                array(
                        Monitor_Shortcuts_UserLevel($request)
                ));
        $content->forced_where = $sql;
        if (key_exists('_px_format', $request->REQUEST)) {
            switch ($request->REQUEST['_px_format']) {
                case 'text/prometheus':
                    break;
                default:
                    $content->model_view = 'beans';
            }
        }
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
        if (key_exists('_px_format', $request->REQUEST)) {
            switch ($request->REQUEST['_px_format']) {
                case 'text/prometheus':
                    return Monitor_Shortcuts_BeansToPrometheus(
                    $content->render_object(), $request, $match);
            }
        }
        return $content->render_object();
    }
}