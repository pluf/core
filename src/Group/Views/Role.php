<?php
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('Pluf_Shortcuts_GetFormForModel');

/**
 * Manages roles of a group.
 *
 * @author maso
 *        
 */
class Group_Views_Role extends Pluf_Views
{

    /**
     * Adds a role to list of roles of a group.
     * Id of added role should be specified in request.
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public static function add($request, $match)
    {
        throw new Pluf_Exception_NotImplemented();
    }

    /**
     * Returns list of roles of a group.
     * Returned list can be customized with some filter, condition and sort.
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public static function find($request, $match)
    {
        throw new Pluf_Exception_NotImplemented();
    }

    /**
     * Returns information of a role of a group.
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public static function get($request, $match)
    {
        throw new Pluf_Exception_NotImplemented();
    }

    /**
     * Deletes a role from roles of a group.
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public static function delete($request, $match)
    {
        throw new Pluf_Exception_NotImplemented();
    }
}
