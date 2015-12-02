<?php
Pluf::loadFunction('User_Shortcuts_UpdateLeveFor');
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');

Pluf::loadFunction('KM_Shortcuts_GetLabelOr404');

Pluf::loadFunction('Jayab_Shortcuts_locationBound');
Pluf::loadFunction('Jayab_Shortcuts_GetLocationOr404');

/**
 *
 * @author maso
 *        
 */
class Jayab_Views_Tag
{

    public function find ($request, $match)
    {
        $count = 10;
        $count = Jayab_Shortcuts_locationCount($request, $count);
        
        // maso, 1394: گرفتن فهرست مناسبی از پیام‌ها
        // Paginator to paginate messages
        $pag = new Pluf_Paginator(new Jayab_Tag());
        $pag->list_filters = array(
                'tag_key',
                'tag_value'
        );
        $list_display = array(
                'tag_key' => __('tag key'),
                'tag_value' => __('tag value'),
                'description' => __('description')
        );
        $search_fields = array(
                'tag_key',
                'tag_value',
                'description'
        );
        $sort_fields = array(
                'tag_key',
                'tag_value',
                'creation_date',
                'modif_dtime'
        );
        $pag->configure($list_display, $search_fields, $sort_fields);
        $pag->items_per_page = $count;
        $pag->sort_order = array(
                'creation_dtime',
                'DESC'
        );
        $pag->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($pag->render_object());
    }

    public function create ($request, $match)
    {
        $extra = array();
        $form = new Jayab_Form_Tag(
                array_merge($request->REQUEST, $request->FILES), $extra);
        $cuser = $form->save();
        $request->user->setMessage(
                sprintf(__('the tag %s has been created.'), (string) $cuser->id));
        // Return response
        return new Pluf_HTTP_Response_Json($cuser);
    }

    public function update ($request, $match)
    {
        throw new Pluf_Exception("Not implemented");
    }

    /**
     * اطلاعات یک مکان را دریافت می‌کند
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public function get ($request, $match)
    {
        $tag = Jayab_Shortcuts_GetTagOr404($match[1]);
        return new Pluf_HTTP_Response_Json($tag);
    }

    public function getByTag ($request, $match)
    {
        $sqlSelect = new Pluf_SQL('tag_key=%s AND tag_value=%s', 
                array(
                        $request->REQUEST['tag_key'],
                        $request->REQUEST['tag_value']
                ));
        $tag = Pluf::factory('Jayab_Tag')->getOne(
                array(
                        'filter' => $sqlSelect->gen()
                ));
        if ($tag == null) {
            throw new Pluf_HTTP_Error404();
        }
        return new Pluf_HTTP_Response_Json($tag);
    }

    /**
     * اطلاعات یک مکان را حذف می‌کند
     *
     * @param unknown $request            
     * @param unknown $match            
     * @return Pluf_HTTP_Response_Json
     */
    public function delete ($request, $match)
    {
        throw new Pluf_Exception("Not implemented");
    }
}