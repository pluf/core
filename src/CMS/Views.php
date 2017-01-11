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

class CMS_Views
{

    public static function create ($request, $match)
    {
        // initial content data
        $extra = array(
                'user' => $request->user,
                'model' => new CMS_Content()
        );
                   
        // Create content and get its ID
        $form = new CMS_Form_ContentCreate($request->REQUEST, $extra);
        
        // Upload content file and extract information about it (by updating
        // content)
        $extra['model'] = $form->save();
        $form = new CMS_Form_ContentUpdate(
                array_merge($request->REQUEST, $request->FILES), $extra);
        try {
            $content = $form->save();
        } catch (Pluf_Exception $e) {
            $content = $extra['model'];
            $content->delete();
            throw $e;
        }
        return new Pluf_HTTP_Response_Json($content);
    }

    public static function find ($request, $match)
    {
        $content = new Pluf_Paginator(new CMS_Content());
        $content->list_filters = array(
                'id',
        		'name',
                'title',
                'file_name',
                'mime_type'
        );
        $list_display = array(
                'title' => __('title'),
                'file_name' => __('file_name'),
                'mime_type' => __('mime_type'),
                'description' => __('description')
        );
        $search_fields = array(
        		'name',
                'title',
                'file_name',
                'mime_type',
                'description'
        );
        $sort_fields = array(
                'id',
        		'name',
                'title',
                'file_name',
                'file_size',
                'mime_type',
                'downloads',
                'creation_date',
                'modif_dtime'
        );
        $content->configure($list_display, $search_fields, $sort_fields);
        $content->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($content->render_object());
    }

    public static function get ($request, $match)
    {
        // تعیین داده‌ها
        if (array_key_exists('id', $match)) {
            $content = Pluf_Shortcuts_GetObjectOr404('CMS_Content', $match['id']);
            // XXX: maso, 1395: محتوی در ملک باشد
        } else {
            $content = CMS_Shortcuts_GetNamedContentOr404($match['name']);
        }
        // اجرای درخواست
        return new Pluf_HTTP_Response_Json($content);
    }

    public static function update ($request, $match)
    {
        // تعیین داده‌ها
        $content = Pluf_Shortcuts_GetObjectOr404($match['id']);
        // اجرای درخواست
        $extra = array(
                'model' => $content
        );
        $form = new CMS_Form_ContentUpdate(
                array_merge($request->REQUEST, $request->FILES), $extra);
        $content = $form->save();
        return new Pluf_HTTP_Response_Json($content);
    }

    public static function delete ($request, $match)
    {
        // تعیین داده‌ها
        $content = Pluf_Shortcuts_GetObjectOr404('CMS_Content', $match['id']);
        // دسترسی
        // CMS_Precondition::userCanDeleteContent($request, $content);
        // اجرا
        $content2 = Pluf_Shortcuts_GetObjectOr404('CMS_Content', $content->id);
        $content->delete();
        
        // TODO: فایل مربوط به کانتنت باید حذف شود
        
        return new Pluf_HTTP_Response_Json($content2);
    }

    public static function download ($request, $match)
    {
        // GET data
        $content = Pluf_Shortcuts_GetObjectOr404('CMS_Content', $match['id']);
        // Do
        $content->downloads += 1;
        $content->update();
        $response = new Pluf_HTTP_Response_File($content->getAbsloutPath(), 
                $content->mime_type);
        $response->headers['Content-Disposition'] = sprintf(
                'attachment; filename="%s"', $content->file_name);
        return $response;
    }

    public static function updateFile ($request, $match)
    {
        // GET data
        $content = Pluf_Shortcuts_GetObjectOr404('CMS_Content', $match[1]);
        if (array_key_exists('file', $request->FILES)) {
            // $extra = array(
            // // 'user' => $request->user,
            // 'content' => $content,
            // 'tenant' => $request->tenant
            // );
            // $form = new CMS_Form_ContentUpdate(
            // array_merge($request->REQUEST, $request->FILES), $extra);
            // $content = $form->update();
            // // return new Pluf_HTTP_Response_Json($content);
            return CMS_Views::update($request, $match);
        } else {
            // Do
            $myfile = fopen($content->getAbsloutPath(), "w") or
                     die("Unable to open file!");
            $entityBody = file_get_contents('php://input', 'r');
            fwrite($myfile, $entityBody);
            fclose($myfile);
            // $content->file_size = filesize(
            // $content->file_path . '/' . $content->id);
            $content->update();
        }
        return new Pluf_HTTP_Response_Json($content);
    }
}