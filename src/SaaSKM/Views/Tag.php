<?php
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');
Pluf::loadFunction('Pluf_Shortcuts_GetFormForModel');
Pluf::loadFunction('SaaSKM_Shortcuts_GetTagOr404');

/**
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 */
class SaaSKM_Views_Tag
{

    public function find ($request, $match)
    {
        $count = 20;
        $pag = new Pluf_Paginator(new SaaSKM_Tag());
        $sql = new Pluf_SQL('tenant=%s',
                array(
                        $request->tenant->id
                ));
        $pag->forced_where = $sql;
        $list_display = array(
                'title' => __('title'),
                'description' => __('description'),
                'color' => __('color')
        );
        $search_fields = array();
        $sort_fields = array(
                'creation_date'
        );
        $pag->configure($list_display, $search_fields, $sort_fields);
        $pag->action = array(
                'SaaSKM_Views_Tag::get'
        );
        $pag->items_per_page = $count;
        $pag->no_results_text = __('queue is empty');
        $pag->sort_order = array(
                'creation_dtime',
                'DESC'
        );
        $pag->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($pag->render_object());
    }

    public function create ($request, $match)
    {
        $parent = $this->internalGetRootCategory($request, $match);
        $extra = array(
                'user' => $request->user,
                'parent' => $parent
        );
        $form = new KM_Form_CategoryCreate(
                array_merge($request->REQUEST, $request->FILES), $extra);
        $cat = $form->save();
        return new Pluf_HTTP_Response_Json($cat);
    }

    public function update ($request, $match)
    {
        $cat = KM_Shortcuts_GetCategoryOr404($match[1]);
        $extra = array(
                'user' => $request->user,
                'parent' => null,
                'category' => $cat
        );
        $form = new KM_Form_CategoryUpdate(
                array_merge($request->REQUEST, $request->FILES), $extra);
        $cat = $form->update();
        return new Pluf_HTTP_Response_Json($cat);
    }

    public function delete ($request, $match)
    {
        $cat = KM_Shortcuts_GetCategoryOr404($match[1]);
        $d = new KM_Category($cat->id);
        $d->delete();
        return new Pluf_HTTP_Response_Json($cat);
    }

    public function get ($request, $match)
    {
        $cat = KM_Shortcuts_GetCategoryOr404($match[1]);
        return new Pluf_HTTP_Response_Json($cat);
    }
}