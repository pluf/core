<?php
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('Pluf_Shortcuts_GetFormForModel');

/**
 * Manages groups
 *
 * @author maso
 * @author hadi
 *        
 */
class Group_Views extends Pluf_Views
{

    /**
     * Creates new group.
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public static function create($request, $match)
    {
        $group = new Pluf_Group();
        $form = Pluf_Shortcuts_GetFormForModel($group, $request->REQUEST, array());
        $group = $form->save(false);
        $group->tenant = $request->tenant->getId();
        $group->create();
        return new Pluf_HTTP_Response_Json($group);
    }

    /**
     * Returns list of groups with specified condition.
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public static function find($request, $match)
    {
        $pag = new Pluf_Paginator(new Pluf_Group());
        $pag->items_per_page = Group_Views::getListCount($request);
        $sql = new Pluf_SQL('tenant=%s', array(
            $request->tenant->id
        ));
        $pag->forced_where = $sql;
        $pag->list_filters = array(
            'tenant',
            'version'
        );
        $search_fields = array(
            'name',
            'description'
        );
        $list_display = array(
            'name' => __('name'),
            'description' => __('description')
        );
        $sort_fields = array(
            'id',
            'name',
            'description'
        );
        $pag->configure($list_display, $search_fields, $sort_fields);
        $pag->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($pag->render_object());
    }

    /**
     * Returns information of a group.
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public static function get($request, $match)
    {
        $group = Pluf_Shortcuts_GetObjectOr404('Pluf_Group', $match['id']);
        return new Pluf_HTTP_Response_Json($group);
    }

    /**
     * Updates information of a group.
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public static function update($request, $match)
    {
        $model = Pluf_Shortcuts_GetObjectOr404('Pluf_Group', $match['id']);
        $form = Pluf_Shortcuts_GetFormForUpdateModel($model, $request->REQUEST, array());
        $request->user->setMessage(sprintf(__('Group data has been updated.'), (string) $model));
        return new Pluf_HTTP_Response_Json($form->save());
    }

    /**
     * Deletes a group.
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public static function delete($request, $match)
    {
        $model = Pluf_Shortcuts_GetObjectOr404('Pluf_Group', $match['id']);
        $modelCopy = new Pluf_Group($match['id']);
        $modelCopy->id = 0;
        if ($model->delete()) {
            return new Pluf_HTTP_Response_Json($modelCopy);
        }
        throw new Pluf_HTTP_Error500('Unexpected error while removing group: ' . $modelCopy->name);
    }

    static function getListCount($request)
    {
        $count = 50;
        if (array_key_exists('_px_ps', $request->GET)) {
            $count = $request->GET['_px_ps'];
            if ($count == 0 || $count > 50) {
                $count = 50;
            }
        }
        return $count;
    }
}
