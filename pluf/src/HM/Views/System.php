<?php
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');
Pluf::loadFunction('Pluf_Shortcuts_RenderToResponse');
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('Pluf_Shortcuts_GetFormForModel');

/**
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class HM_Views_System
{

    /**
     * پیش نیازهای ایجاد بازخورد.
     * 
     * @var unknown
     */
    public $createFeedback_precond = array(
            'Pluf_Precondition::loginRequired'
    );

    /**
     * یک پیام جدید برای کاربر ریشه ایجاد می‌کند.
     * 
     * @param unknown $request
     * @param unknown $match
     */
    public function createFeedback ($request, $match)
    {
        $admin = new Pluf_User();
        $admin = $admin->getUser('admin');
        if ($admin->isAnonymous()) {
            throw Pluf_Exception('admin not found');
        }

        $params = array(
                'owner' => $request->user,
                'object' => $admin
        );
        $messageForm = new Inbox_Form_Message(array_merge($request->REQUEST, $request->FILES), 
                $params);
        
        $message = $messageForm->save();
        return new Pluf_HTTP_Response_Json($message);
    }
}