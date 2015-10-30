<?php
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('Wiki_Shortcuts_GetBookOr404');
Pluf::loadFunction('Wiki_Shortcuts_GetBookListCount');

class Wiki_Views_User
{

    public function interestedIn ($request, $match)
    {
        throw new Pluf_Exception('Not implemented');
    }
}