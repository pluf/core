<?php
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');

class SDP_Views_Asset
{

    public static function create ($request, $match)
    {
        $extra = array(
                'request' => $request
        );
        // Create asset and get its ID
        $form = new SDP_Form_AssetCreate($request->REQUEST, $extra);
        $asset = $form->save();
        
        // Upload asset file and extract information about it (by updating
        // asset)
        $extra['asset'] = $asset;
        $form = new SDP_Form_AssetUpdate(
                array_merge($request->REQUEST, $request->FILES), $extra);
        try {
            $asset = $form->update();
        } catch (Pluf_Exception $e) {
            $asset->delete();
            throw $e;
        }
        
        return new Pluf_HTTP_Response_Json($asset);
    }

    /**
     *
     * @param Pluf_HTTP_Request $request            
     * @param unknown $match            
     * @return Pluf_HTTP_Response_Json
     */
    public static function find ($request, $match)
    {
        $assetPaginator = new Pluf_Paginator(new SDP_Asset());
        $assetPaginator->list_filters = array(
                'id',
                'name',
                'size',
                'download',
                'driver_type',
                'driver_id',
                'creation_dtime',
                'modif_dtime',
                'type',
                'mime_type',
                'price',
                'parent'
        );
        $list_display = array(
                'name',
                'type',
                'size',
                'price',
                'download',
                'driver_type',
                'driver_id',
                'parent'
        );
        
        $search_fields = array(
                'name',
                'driver_type',
                'driver_id',
                'type',
                'description',
                'mime_type'
        );
        $sort_fields = array(
                'id',
                'name',
                'size',
                'download',
                'driver_type',
                'driver_id',
                'creation_dtime',
                'modif_dtime',
                'type',
                'mime_type',
                'price',
                'parent'
        );
        $assetPaginator->configure($list_display, $search_fields, $sort_fields);
        $assetPaginator->items_per_page = SDP_Shortcuts_NormalizeItemPerPage($request);
        $assetPaginator->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($assetPaginator->render_object());
    }

    public static function get ($request, $match)
    {
        // تعیین داده‌ها
        $asset = Pluf_Shortcuts_GetObjectOr404('SDP_Asset', $match["id"]);
        // حق دسترسی
        // CMS_Precondition::userCanAccessContent($request, $content);
        // اجرای درخواست
        return new Pluf_HTTP_Response_Json($asset);
    }

    public static function update ($request, $match)
    {
        // تعیین داده‌ها
        $asset = Pluf_Shortcuts_GetObjectOr404('SDP_Asset', $match["id"]);
        // حق دسترسی
        // CMS_Precondition::userCanUpdateContent($request, $content);
        // اجرای درخواست
        $extra = array(
                'request' => $request,
                'asset' => $asset
        );
        $form = new SDP_Form_AssetUpdate(
                array_merge($request->REQUEST, $request->FILES), $extra);
        $asset = $form->update();
        return new Pluf_HTTP_Response_Json($asset);
    }

    public static function delete ($request, $match)
    {
        // تعیین داده‌ها
        $asset = Pluf_Shortcuts_GetObjectOr404('SDP_Asset', $match["id"]);
        // دسترسی
        // CMS_Precondition::userCanDeleteContent($request, $content);
        // اجرا
        $asset_copy = Pluf_Shortcuts_GetObjectOr404('SDP_Asset', $asset->id);
        $asset_copy->path = "";
        
        $asset->delete();
        
        return new Pluf_HTTP_Response_Json($asset_copy);
    }

    public static function updateFile ($request, $match)
    {
        // GET data
        $asset = Pluf_Shortcuts_GetObjectOr404('SDP_Asset', $match["id"]);
        // Check permission
        // Precondition::userCanAccessApplication($request, $app);
        // Precondition::userCanAccessResource($request, $content);
        
        if (array_key_exists('file', $request->FILES)) {
            $extra = array(
                    'asset' => $asset
            );
            $form = new SDP_Form_ContentUpdate(
                    array_merge($request->REQUEST, $request->FILES), $extra);
            $asset = $form->update();
            // return new Pluf_HTTP_Response_Json($content);
        } else {
            
            // Do
            $myfile = fopen($asset->path . '/' . $asset->id, "w") or
                     die("Unable to open file!");
            $entityBody = file_get_contents('php://input', 'r');
            fwrite($myfile, $entityBody);
            fclose($myfile);
            $asset->file_size = filesize($asset->path . '/' . $asset->id);
            $asset->update();
        }
        return new Pluf_HTTP_Response_Json($asset);
    }
    
    // *******************************************************************
    // Tags of Asset
    // *******************************************************************
    public static function tags ($request, $match)
    {
        $asset = Pluf_Shortcuts_GetObjectOr404('SDP_Asset', $match['assetId']);
        $tag = new SDP_Tag();
        $tagTable = $tag->_a['table'];
        $assocTable = 'sdp_asset_sdp_tag_assoc';
        $tag->_a['views']['myView'] = array(
                'select' => $tag->getSelect(),
                'join' => 'LEFT JOIN ' . $assocTable . ' ON ' . $tagTable .
                         '.id=' . $assocTable . '.sdp_tag_id'
        );
        
        $paginator = new Pluf_Paginator($tag);
        $sql = new Pluf_SQL('sdp_asset_id=%s', 
                array(
                        $asset->id
                ));
        $paginator->forced_where = $sql;
        $paginator->model_view = 'myView';
        $paginator->list_filters = array(
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
                'creation_dtime',
                'modif_dtime'
        );
        $paginator->configure(array(), $search_fields, $sort_fields);
        $paginator->items_per_page = SDP_Shortcuts_NormalizeItemPerPage($request);
        $paginator->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($paginator->render_object());
    }

    public static function addTag ($request, $match)
    {
        $asset = Pluf_Shortcuts_GetObjectOr404('SDP_Asset', $match['assetId']);
        if (isset($match['tagId'])) {
            $tagId = $match['tagId'];
        } else {
            $tagId = $request->REQUEST['tagId'];
        }
        $tag = Pluf_Shortcuts_GetObjectOr404('SDP_Tag', $tagId);
        $asset->setAssoc($tag);
        return new Pluf_HTTP_Response_Json($tag);
    }

    public static function removeTag ($request, $match)
    {
        $asset = Pluf_Shortcuts_GetObjectOr404('SDP_Asset', $match['assetId']);
        if (isset($match['tagId'])) {
            $tagId = $match['tagId'];
        } else {
            $tagId = $request->REQUEST['tagId'];
        }
        $tag = Pluf_Shortcuts_GetObjectOr404('SDP_Tag', $tagId);
        $asset->delAssoc($tag);
        return new Pluf_HTTP_Response_Json($tag);
    }
    // *******************************************************************
    // Categories of Asset
    // *******************************************************************
    public static function categories ($request, $match)
    {
        $asset = Pluf_Shortcuts_GetObjectOr404('SDP_Asset', $match['assetId']);
        $category = new SDP_Category();
        $categoryTable = $category->_a['table'];
        $assocTable = 'sdp_asset_sdp_category_assoc';
        $category->_a['views']['myView'] = array(
                'select' => $category->getSelect(),
                'join' => 'LEFT JOIN ' . $assocTable . ' ON ' . $categoryTable .
                         '.id=' . $assocTable . '.sdp_category_id'
        );
        
        $paginator = new Pluf_Paginator($category);
        $sql = new Pluf_SQL('sdp_asset_id=%s', 
                array(
                        $asset->id
                ));
        $paginator->forced_where = $sql;
        $paginator->model_view = 'myView';
        $paginator->list_filters = array(
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
                'creation_dtime',
                'modif_dtime'
        );
        $paginator->configure(array(), $search_fields, $sort_fields);
        $paginator->items_per_page = SDP_Shortcuts_NormalizeItemPerPage($request);
        $paginator->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($paginator->render_object());
    }

    public static function addCategory ($request, $match)
    {
        $asset = Pluf_Shortcuts_GetObjectOr404('SDP_Asset', $match['assetId']);
        if (isset($match['categoryId'])) {
            $categoryId = $match['categoryId'];
        } else {
            $categoryId = $request->REQUEST['categoryId'];
        }
        $category = Pluf_Shortcuts_GetObjectOr404('SDP_Category', $categoryId);
        $asset->setAssoc($category);
        return new Pluf_HTTP_Response_Json($category);
    }

    public static function removeCategory ($request, $match)
    {
        $asset = Pluf_Shortcuts_GetObjectOr404('SDP_Asset', $match['assetId']);
        if (isset($match['categoryId'])) {
            $categoryId = $match['categoryId'];
        } else {
            $categoryId = $request->REQUEST['categoryId'];
        }
        $category = Pluf_Shortcuts_GetObjectOr404('SDP_Category', $categoryId);
        $asset->delAssoc($category);
        return new Pluf_HTTP_Response_Json($category);
    }
    
    // *******************************************************************
    // Relations of Asset
    // *******************************************************************
    public static function relations ($request, $match)
    {
        $asset = Pluf_Shortcuts_GetObjectOr404('SDP_Asset', $match['assetId']);
        $relatedAsset = new SDP_Asset();
        $relatedAssetTable = $relatedAsset->_a['table'];
        $assocTable = 'sdp_assetrelation';
        $relatedAsset->_a['views']['myView'] = array(
                'select' => $relatedAsset->getSelect(),
                'join' => 'LEFT JOIN ' . $assocTable . ' ON ' .
                         $relatedAssetTable . '.id=' . $assocTable . '.end'
        );
        
        $page = new Pluf_Paginator($relatedAsset);
        $sql = new Pluf_SQL('start=%s', 
                array(
                        $asset->id
                ));
        $page->forced_where = $sql;
        $page->model_view = 'myView';
        $page->list_filters = array(
                'id',
                'name',
                'size',
                'download',
                'driver_type',
                'driver_id',
                'creation_dtime',
                'modif_dtime',
                'type',
                'mime_type',
                'price',
                'parent'
        );
        $search_fields = array(
                'name',
                'driver_type',
                'driver_id',
                'type',
                'description',
                'mime_type'
        );
        $sort_fields = array(
                'id',
                'name',
                'size',
                'download',
                'driver_type',
                'driver_id',
                'creation_dtime',
                'modif_dtime',
                'type',
                'mime_type',
                'price',
                'parent'
        );
        $page->configure(array(), $search_fields, $sort_fields);
        $page->items_per_page = SDP_Shortcuts_NormalizeItemPerPage($request);
        $page->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($page->render_object());
    }

    public static function addRelation ($request, $match)
    {
        $asset = Pluf_Shortcuts_GetObjectOr404('SDP_Asset', $match['assetId']);
        if (isset($match['endId'])) {
            $endId = $match['endId'];
        } else {
            $endId = $request->REQUEST['endId'];
        }
        $endAsset = Pluf_Shortcuts_GetObjectOr404('SDP_Asset', $endId);
        $request->REQUEST['start'] = $asset->getId();
        $request->REQUEST['end'] = $endAsset->getId();
        $form = Pluf_Shortcuts_GetFormForModel(new SDP_AssetRelation(), 
                $request->REQUEST, array());
        return new Pluf_HTTP_Response_Json($form->save());
    }

    public static function removeRelation ($request, $match)
    {
        $asset = Pluf_Shortcuts_GetObjectOr404('SDP_Asset', $match['assetId']);
        if (isset($match['endId'])) {
            $endId = $match['endId'];
        } else {
            $endId = $request->REQUEST['endId'];
        }
        $endAsset = Pluf_Shortcuts_GetObjectOr404('SDP_Asset', $endId);
        $relation = new SDP_AssetRelation();
        $relationList = $relation->getList(
                array(
                        'filter' => array(
                                'start=' . $asset->id,
                                'end=' . $endAsset->id
                        )
                ));
        $relateListCopy = array();
        foreach ($relationList as $rel) {
            $val = clone $rel;
            array_push($relateListCopy, $val);
            $rel->delete();
        }
        return new Pluf_HTTP_Response_Json($relateListCopy);
    }
}