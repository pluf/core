<?php
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('SaaS_Shortcuts_GetLibOr404');
Pluf::loadFunction('SaaS_Shortcuts_LibFindCount');

/**
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class SaaS_Views_Lib
{

    public function create ($request, $match)
    {
        $form = new SaaS_Form_LibCreate($request->REQUEST);
        $lib = $form->save();
        return new Pluf_HTTP_Response_Json($lib);
    }

    public function get ($request, $match)
    {
        // داده‌ها
        $lib = SaaS_Shortcuts_GetLibOr404($match[1]);
        // دسترسی
        SaaS_Precondition::userCanAccessLib($request, $lib);
        // اجرا
        return new Pluf_HTTP_Response_Json($lib);
    }

    public function download ($request, $match)
    {
        // داده‌ها
        $lib = SaaS_Shortcuts_GetLibOr404($match[1]);
        // دسترسی
        SaaS_Precondition::userCanAccessLib($request, $lib);
        // اجرا
        $lib->downloads += 1;
        $lib->update();
        $p = $lib->getAbslotePath();
        $response = new Pluf_HTTP_Response_File($p, $lib->getMimeType());
        $response->headers['Content-Disposition'] = 'attachment; filename="' .
                 $lib->name . '"';
        return $response;
    }

    public function update ($request, $match)
    {
        // داده‌ها
        $lib = SaaS_Shortcuts_GetLibOr404($match[1]);
        // دسترسی
        SaaS_Precondition::userCanUpdateLib($request, $lib);
        // اجرا
        
        $form = new SaaS_Form_LibUpdate($request->REQUEST, 
                array(
                        'lib' => $lib
                ));
        $lib = $form->update();
        return new Pluf_HTTP_Response_Json($lib);
    }

    public function delete ($request, $match)
    {
        // داده‌ها
        $lib = SaaS_Shortcuts_GetLibOr404($match[1]);
        // دسترسی
        SaaS_Precondition::userCanDeleteLib($request, $lib);
        // اجرا
        $tlib = new SaaS_Lib($lib->id);
        $tlib->delete();
        return new Pluf_HTTP_Response_Json($lib);
    }

    public function find ($request, $match)
    {
        $pag = new Pluf_Paginator(new SaaS_Lib());
        $list_display = array(
                'id' => __('application id'),
                'title' => __('title'),
                'creation_dtime' => __('create')
        );
        $search_fields = array();
        $sort_fields = array(
                'creation_dtime'
        );
        $pag->configure($list_display, $search_fields, $sort_fields);
        $pag->action = array();
        $pag->items_per_page = SaaS_Shortcuts_LibFindCount($request);
        $pag->no_results_text = __('No apartment is added yet.');
        $pag->sort_order = array(
                'creation_dtime',
                'DESC'
        );
        $pag->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($pag->render_object());
    }
}