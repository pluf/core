<?php
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');

/**
 * لایه دسترسی به پیام‌ها
 * 
 * سیستم بر اساس رویداده‌ها پیام‌هایی را برای کاربران ایجاد می‌کند. این 
 * نمایش امکان دسترسی به این پیام‌ها را برای کاربر فراهم می‌کند.
 * 
 * @author maso<mostafa.barmshory@dpq.co.ir>
 *
 */
class Message_Views
{

    /**
     *
     * @param Pluf_HTTP_Request $request            
     * @param array $match            
     * @return Pluf_HTTP_Response_Json
     */
    public static function find ($request, $match)
    {
        $content = new Pluf_Paginator(new Pluf_Message());
        $sql = new Pluf_SQL('tenant=%s AND user=%s', 
                array(
                        $request->tenant->id,
                        $request->user->id
                ));
        $content->forced_where = $sql;
        $content->list_filters = array();
        $list_display = array(
                'message' => __('message')
        );
        $search_fields = array(
                'message'
        );
        $sort_fields = array(
                'creation_dtime'
        );
        $content->configure($list_display, $search_fields, $sort_fields);
        $content->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($content->render_object());
    }

    /**
     *
     * @param Pluf_HTTP_Request $request            
     * @param array $match            
     * @return Pluf_HTTP_Response_Json
     */
    public static function get ($request, $match)
    {
        $message = Pluf_Shortcuts_GetObjectOr404('Pluf_Message', 
                $match['messageId']);
        Message_Security::canAccessMessage($request, $message);
        return new Pluf_HTTP_Response_Json($message);
    }

    /**
     *
     * @param Pluf_HTTP_Request $request            
     * @param array $match            
     * @return Pluf_HTTP_Response_Json
     */
    public static function delete ($request, $match)
    {
        $message = Pluf_Shortcuts_GetObjectOr404('Pluf_Message', 
                $match['messageId']);
        Message_Security::canAccessMessage($request, $message);
        $message->delete();
        return new Pluf_HTTP_Response_Json($message);
    }
}