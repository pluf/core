<?php
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');

class Monitor_Views
{

    public static function create ($request, $match)
    {
        // initial content data
        $extra = array(
                'user' => $request->user,
                'tenant' => $request->tenant,
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
        $content = new Pluf_Paginator(new Pluf_Monitor());
        // $sql = new Pluf_SQL('tenant=%s',
        // array(
        // $request->tenant->id
        // ));
        // $content->forced_where = $sql;
        $content->list_filters = array(
                'code_name',
                'application',
                'title'
        );
        $list_display = array(
                'title' => __('title'),
                'code_name' => __('code name'),
                'application' => __('application'),
                'description' => __('description')
        );
        $search_fields = array(
                'title',
                'description',
                'code_name',
                'application'
        );
        $sort_fields = array(
                'id',
                'name',
                'title',
                'code_name',
                'application',
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
        $sql = new Pluf_SQL('application=%s AND code_name=%s', 
                array(
                        $match['monitor'],
                        $match['property']
                ));
        $model = new Pluf_Monitor();
        $model = $model->getOne(
                array(
                        'filter' => $sql->gen()
                ));
        return new Pluf_HTTP_Response_Json($model->invoke($request));
    }
    
    // public static function update ($request, $match)
    // {
    // // تعیین داده‌ها
    // $content = CMS_Shortcuts_GetContentOr404($match['id']);
    // // حق دسترسی
    // // CMS_Precondition::userCanUpdateContent($request, $content);
    // // اجرای درخواست
    // $extra = array(
    // // 'user' => $request->user,
    // 'model' => $content,
    // 'tenant' => $request->tenant
    // );
    // // 'tenant' => $request->tenant
    
    // $form = new CMS_Form_ContentUpdate(
    // array_merge($request->REQUEST, $request->FILES), $extra);
    // $content = $form->save();
    // return new Pluf_HTTP_Response_Json($content);
    // }
    
    // public static function delete ($request, $match)
    // {
    // // تعیین داده‌ها
    // $content = CMS_Shortcuts_GetContentOr404($match['id']);
    // // دسترسی
    // // CMS_Precondition::userCanDeleteContent($request, $content);
    // // اجرا
    // $content2 = CMS_Shortcuts_GetContentOr404($content->id);
    // $content->delete();
    
    // // TODO: فایل مربوط به کانتنت باید حذف شود
    
    // return new Pluf_HTTP_Response_Json($content2);
    // }
    
    // public static function download ($request, $match)
    // {
    // // GET data
    // $app = $request->tenant;
    // $content = CMS_Shortcuts_GetContentOr404($match['id']);
    // // Check permission
    // // SaaS_Precondition::userCanAccessApplication($request, $app);
    // // SaaS_Precondition::userCanAccessResource($request, $content);
    
    // // Do
    // $content->downloads += 1;
    // $content->update();
    // $response = new Pluf_HTTP_Response_File($content->getAbsloutPath(),
    // $content->mime_type);
    // $response->headers['Content-Disposition'] = sprintf(
    // 'attachment; filename="%s"', $content->file_name);
    // return $response;
    // }
    
    // public static function updateFile ($request, $match)
    // {
    // // GET data
    // $app = $request->tenant;
    // $content = CMS_Shortcuts_GetContentOr404($match[1]);
    // // Check permission
    // // SaaS_Precondition::userCanAccessApplication($request, $app);
    // // SaaS_Precondition::userCanAccessResource($request, $content);
    
    // if (array_key_exists('file', $request->FILES)) {
    // // $extra = array(
    // // // 'user' => $request->user,
    // // 'content' => $content,
    // // 'tenant' => $request->tenant
    // // );
    // // $form = new CMS_Form_ContentUpdate(
    // // array_merge($request->REQUEST, $request->FILES), $extra);
    // // $content = $form->update();
    // // // return new Pluf_HTTP_Response_Json($content);
    // return CMS_Views::update($request, $match);
    // } else {
    // // Do
    // $myfile = fopen($content->getAbsloutPath(), "w") or
    // die("Unable to open file!");
    // $entityBody = file_get_contents('php://input', 'r');
    // fwrite($myfile, $entityBody);
    // fclose($myfile);
    // // $content->file_size = filesize(
    // // $content->file_path . '/' . $content->id);
    // $content->update();
    // }
    // return new Pluf_HTTP_Response_Json($content);
    // }
}