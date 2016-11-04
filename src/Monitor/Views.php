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
        return new Pluf_HTTP_Response_Json($model->invoke($request));
    }
}