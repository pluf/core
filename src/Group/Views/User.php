<?php
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('Pluf_Shortcuts_GetFormForModel');

/**
 * Manages users of a group.
 *
 * @author maso
 * @author hadi
 *        
 */
class Group_Views_User extends Pluf_Views
{

    /**
     * Adds new user to list of users of a group.
     * Id of added user should be specified in request.
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public static function add($request, $match)
    {
        $group = Pluf_Shortcuts_GetObjectOr404('Pluf_Group', $match['groupId']);
        if (array_key_exists('user', $request->REQUEST)) {
            $user = Pluf_Shortcuts_GetObjectOr404('Pluf_User', $request->REQUEST['user']);
        } elseif (array_key_exists('login', $request->REQUEST)) {
            $user = new Pluf_User();
            $user = $user->getOne(array(
                'filter' => 'login="' . $request->REQUEST['login'].'"'
            ));
            if (! isset($user) || $user->isAnonymous()) {
                throw new Pluf_HTTP_Error404(__('User not found'));
            }
        }
        $group->setAssoc($user);
        return new Pluf_HTTP_Response_Json(array(
            'group_id' => $group->id,
            'group_name' => $group->name,
            'user_id' => $user->id,
            'user_login' => $user->login,
            'user_first_name' => $user->first_name,
            'user_last_name' => $user->last_name
        ));
    }

    /**
     * Returns list of users of a group.
     * Resulted list can be customized by using filters, conditions and sort rules.
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public static function find($request, $match)
    {
        $pag = new Pluf_Paginator(new Pluf_User());
        $pag->items_per_page = Group_Views::getListCount($request);
        $sql = new Pluf_SQL('pluf_group_id=%s', array(
            $match['groupId']
        ));
        $pag->forced_where = $sql;
        $pag->list_filters = array(
            'administrator',
            'staff',
            'active'
        );
        $search_fields = array(
            'login',
            'first_name',
            'last_name',
            'email'
        );
        $list_display = array(
            'login' => __('login'),
            'first_name' => __('first name'),
            'last_name' => __('last name'),
            'email' => __('email')
        );
        $sort_fields = array(
            'id',
            'login',
            'first_name',
            'last_name',
            'date_joined',
            'last_login'
        );
        $pag->model_view = 'user_group';
        $pag->configure($list_display, $search_fields, $sort_fields);
        $pag->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($pag->render_object());
    }

    /**
     * Returns information of a user of a group.
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public static function get($request, $match)
    {
        $group = Pluf_Shortcuts_GetObjectOr404('Pluf_Group', $match['groupId']);
        $userModel = new Pluf_User();
        $param = array(
            'view' => 'user_group',
            'filter' => array(
                'id=' . $match['userId'],
                'pluf_group_id=' . $group->id
            )
        );
        $users = $userModel->getList($param);
        if($users->count() == 0){
            throw new Pluf_Exception_DoesNotExist('Group has not such user');
        }
        return new Pluf_HTTP_Response_Json($users);
    }

    /**
     * Deletes a user from a group.
     * Id of deleted user should be specified in the match.
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public static function delete($request, $match)
    {
        $group = Pluf_Shortcuts_GetObjectOr404('Pluf_Group', $match['groupId']);
        $user = Pluf_Shortcuts_GetObjectOr404('Pluf_User', $match['userId']);
        $group->delAssoc($user);
        return new Pluf_HTTP_Response_Json(array(
            'group_id' => $group->id,
            'group_name' => $group->name,
            'user_id' => $user->id,
            'user_login' => $user->login,
            'user_first_name' => $user->first_name,
            'user_last_name' => $user->last_name
        ));
    }
}
