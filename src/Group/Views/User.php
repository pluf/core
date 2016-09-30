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
        throw new Pluf_Exception_NotImplemented();
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
        throw new Pluf_Exception_NotImplemented();
    }

    /**
     * Returns information of a user of a group.
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public static function get($request, $match)
    {
        throw new Pluf_Exception_NotImplemented();
    }

    /**
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public static function delete($request, $match)
    {
        throw new Pluf_Exception_NotImplemented();
    }
}
