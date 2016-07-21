<?php
Pluf::loadFunction('SaaSDM_Shortcuts_GetSecureLinkOr404');

class SaaSDM_Views_Link
{

	public static function get($request, $match)
	{
		$link = new SaaSDM_Link($match['id']);
		return new Pluf_HTTP_Response_Json($link);
	}
	
	public static function find($request, $match)
	{
		$links = new Pluf_Paginator(new SaaSDM_Link());
        $sql = new Pluf_SQL('tenant=%s', array(
            $request->tenant->id
        ));
        $links->forced_where = $sql;
        $links->list_filters = array(
            'id',
            'secure_link'
        );
        $search_fields = array(
            'title',
            'file_name',
            'mime_type',
            'description'
        );
        $sort_fields = array(
            'id',
            'secure_link',
            'creation_date',
            'modif_dtime'
        );
        $links->configure(array(), $search_fields, $sort_fields);
        $links->items_per_page = 20;
        $links->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($links->render_object());
	}
	
	public static function download($request, $match)
	{
		
		$link = SaaSDM_Shortcuts_GetSecureLinkOr404($match['secure_link']);
		if($link->tenant != $request->tenant->id){
			// Error 404
		}
		// TODO: check link expiry
		$asset = $link->get_asset();
		
		// XXX: DO download
	}
}