<?php
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');

class SDP_Views_Category
{

    public static function assets($request, $match)
    {
        $category = Pluf_Shortcuts_GetObjectOr404('SDP_Category', $match['categoryId']);
        if ($category->tenant != $request->tenant->id) {
            throw new Pluf_Exception();
        }
        $asset = new SDP_Asset();
        $assetTable = $asset->_a['table'];
        $assocTable = 'sdp_asset_sdp_category_assoc';
        $asset->_a['views']['myView'] = array(
            'select' => $asset->getSelect(),
            'join' => 'LEFT JOIN ' . $assocTable . ' ON ' . $assetTable . '.id=' . $assocTable . '.sdp_asset_id'
        );
        
        $page = new Pluf_Paginator($asset);
        $sql = new Pluf_SQL('sdp_category_id=%s', array(
            $category->id
        ));
        $page->forced_where = $sql;
        $page->model_view = 'myView';
        $page->list_filters = array(
            'id',
            'name',
            'parent'
        );
        $search_fields = array(
            'name',
            'description'
        );
        $sort_fields = array(
            'id',
            'name',
            'parent',
            'creation_date',
            'modif_dtime'
        );
        $page->configure(array(), $search_fields, $sort_fields);
        $page->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($page->render_object());
    }

    public static function addAsset($request, $match)
    {
        $category = Pluf_Shortcuts_GetObjectOr404('SDP_Category', $match['categoryId']);
        if ($category->tenant != $request->tenant->id) {
            throw new Pluf_Exception();
        }
        if (isset($match['assetId'])) {
            $assetId = $match['assetId'];
        } else {
            $assetId = $request->REQUEST['assetId'];
        }
        $asset = Pluf_Shortcuts_GetObjectOr404('SDP_Asset', $assetId);
        $category->setAssoc($asset);
        return new Pluf_HTTP_Response_Json($asset);
    }

    public static function removeAsset($request, $match)
    {
        $category = Pluf_Shortcuts_GetObjectOr404('SDP_Category', $match['categoryId']);
        if ($category->tenant != $request->tenant->id) {
            throw new Pluf_Exception();
        }
        if (isset($match['assetId'])) {
            $assetId = $match['assetId'];
        } else {
            $assetId = $request->REQUEST['assetId'];
        }
        $asset = Pluf_Shortcuts_GetObjectOr404('SDP_Asset', $assetId);
        $category->delAssoc($asset);
        return new Pluf_HTTP_Response_Json($asset);
    }
}