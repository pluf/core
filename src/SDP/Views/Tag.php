<?php
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');

class SDP_Views_Tag
{

    public static function assets($request, $match)
    {
        $tag = Pluf_Shortcuts_GetObjectOr404('SDP_Tag', $match['tagId']);
        if ($tag->tenant != $request->tenant->id) {
            throw new Pluf_Exception();
        }
        $asset = new SDP_Asset();
        $assetTable = $asset->_a['table'];
        $assocTable = 'sdp_asset_sdp_tag_assoc';
        $asset->_a['views']['myView'] = array(
            'select' => $asset->getSelect(),
            'join' => 'LEFT JOIN ' . $assocTable . ' ON ' . $assetTable . '.id=' . $assocTable . '.sdp_asset_id'
        );
        
        $page = new Pluf_Paginator($asset);
        $sql = new Pluf_SQL('sdp_tag_id=%s', array(
            $tag->id
        ));
        $page->forced_where = $sql;
        $page->model_view = 'myView';
        $page->list_filters = array(
            'id',
            'name'
        );
        $search_fields = array(
            'name',
            'description'
        );
        $sort_fields = array(
            'id',
            'name',
            'creation_date',
            'modif_dtime'
        );
        $page->configure(array(), $search_fields, $sort_fields);
        $page->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($page->render_object());
    }

    public static function addAsset($request, $match)
    {
        $tag = Pluf_Shortcuts_GetObjectOr404('SDP_Tag', $match['tagId']);
        if ($tag->tenant != $request->tenant->id) {
            throw new Pluf_Exception();
        }
        if (isset($match['assetId'])) {
            $assetId = $match['assetId'];
        } else {
            $assetId = $request->REQUEST['assetId'];
        }
        $asset = Pluf_Shortcuts_GetObjectOr404('SDP_Asset', $assetId);
        $tag->setAssoc($asset);
        return new Pluf_HTTP_Response_Json($asset);
    }

    public static function removeAsset($request, $match)
    {
        $tag = Pluf_Shortcuts_GetObjectOr404('SDP_Tag', $match['tagId']);
        if ($tag->tenant != $request->tenant->id) {
            throw new Pluf_Exception();
        }
        if (isset($match['assetId'])) {
            $assetId = $match['assetId'];
        } else {
            $assetId = $request->REQUEST['assetId'];
        }
        $asset = Pluf_Shortcuts_GetObjectOr404('SDP_Asset', $assetId);
        $tag->delAssoc($asset);
        return new Pluf_HTTP_Response_Json($asset);
    }
}