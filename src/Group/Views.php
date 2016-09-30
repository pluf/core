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
    public static function create ($request, $match)
    {
        throw new Pluf_Exception_NotImplemented();
    }
    
    /**
     * Returns list of groups with specified condition.
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public static function find ($request, $match)
    {
        throw new Pluf_Exception_NotImplemented();
    }

    /**
     * Returns information of a group.
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public static function get ($request, $match)
    {
        throw new Pluf_Exception_NotImplemented();
    }
    
    /**
     * Deletes a group.
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public static function delete ($request, $match)
    {
        throw new Pluf_Exception_NotImplemented();
    }
}
