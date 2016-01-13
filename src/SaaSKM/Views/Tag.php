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
        // Precondition
        SaaSKM_Precondition::userCanAccessTags($request);
        
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
        $search_fields = array(
                'tag_key',
                'tag_value',
                'tag_title',
                'tag_description'
        );
        $sort_fields = array(
                'tag_key',
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
        SaaSKM_Precondition::userCanCreateTag($request);
        $extra = array(
                'tenant' => $request->tenant
        );
        $form = new SaaSKM_Form_TagCreate(
                array_merge($request->REQUEST, $request->FILES), $extra);
        return new Pluf_HTTP_Response_Json($form->save());
    }

    public function update ($request, $match)
    {
        // Input
        $tag = SaaSKM_Shortcuts_GetTagOr404($match[1]);
        // Access
        SaaSKM_Precondition::userCanUpdateTag($request, $tag);
        // DO
        $extra = array(
                'tag' => $tag
        );
        $form = new SaaSKM_Form_TagUpdate(
                array_merge($request->REQUEST, $request->FILES), $extra);
        return new Pluf_HTTP_Response_Json($form->update());
    }

    public function delete ($request, $match)
    {
        // Input
        $tag = SaaSKM_Shortcuts_GetTagOr404($match[1]);
        // Access
        SaaSKM_Precondition::userCanDeleteTag($request, $tag);
        // DO
        $d = SaaSKM_Shortcuts_GetTagOr404($match[1]);
        $d->delete();
        return new Pluf_HTTP_Response_Json($tag);
    }

    public function get ($request, $match)
    {
        // Input
        $tag = SaaSKM_Shortcuts_GetTagOr404($match[1]);
        // Access
        SaaSKM_Precondition::userCanAccessTags($request, $tag);
        // DO
        return new Pluf_HTTP_Response_Json($tag);
    }
}