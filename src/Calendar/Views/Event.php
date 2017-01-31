<?php
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');

/**
 * @ingroup views
 * @brief این کلاس نمایش‌های اصلی سیستم را ایجاد می‌کند.
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *         @date 1394
 */
class Calendar_Views_Event
{

    /**
     * جستجوی صفحه‌ها را انجام می‌دهد
     *
     * @param unknown $request            
     * @param unknown $match            
     * @return Pluf_HTTP_Response_Json
     */
    public function find ($request, $match)
    {
        $calendar = Pluf_Shortcuts_GetObjectOr404('Calendar_Calendar', $match['calendarId']);
        // maso, 1394: گرفتن فهرست مناسبی از پیام‌ها
        $pag = new Pluf_Paginator(new Calendar_Event());
        $sql = new Pluf_SQL('calendar=%s', 
                array(
                        $calendar->id,
                ));
        $pag->forced_where = $sql;
        $pag->list_filters = array(
                'id',
                'title'
        );
        $list_display = array(
                'title' => __('title'),
                'description' => __('description')
        );
        $search_fields = array(
                'title',
                'description',
        );
        $sort_fields = array(
                'id',
                'title',
                'creation_date',
                'modif_dtime'
        );
        $pag->configure($list_display, $search_fields, $sort_fields);
        $pag->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($pag->render_object());
    }

    /**
     * یک صفحه جدید را ایجاد می‌کند
     *
     * @param Pluf_HTTP_Request $request            
     * @param unknown $match            
     * @return Pluf_HTTP_Response_Json
     */
    public function create ($request, $match)
    {
        $calendar = Pluf_Shortcuts_GetObjectOr404('Calendar_Calendar', $match['calendarId']);
        $object = new Calendar_Event();
        $form = Pluf_Shortcuts_GetFormForModel($object, $request->REQUEST);
        $object = $form->save(false);
        $object->calendar = $calendar;
        $object->create();
        // Return response
        return new Pluf_HTTP_Response_Json($object);
    }

    /**
     * یک صفحه را با شناسه تعیین می‌کند
     *
     * @param unknown $request            
     * @param unknown $match            
     * @return Pluf_HTTP_Response_Json
     */
    public function get ($request, $match)
    {
        $calendar = Pluf_Shortcuts_GetObjectOr404('Calendar_Calendar', $match['calendarId']);
        $event = Pluf_Shortcuts_GetObjectOr404('Calendar_Event', $match['eventId']);
        if($event->calendar !== $calendar->id){
            // XXX: maso, 2017: replace with not found exception
            throw  new Pluf_Exception('Not found');
        }
        return new Pluf_HTTP_Response_Json($event);
    }

    /**
     * صفحه را به روز می‌کند
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public function update ($request, $match)
    {
        $calendar = Pluf_Shortcuts_GetObjectOr404('Calendar_Calendar', $match['calendarId']);
        $object = Pluf_Shortcuts_GetObjectOr404('Calendar_Event', $match['eventId']);
        if($object->calendar !== $calendar->id){
            // XXX: maso, 2017: replace with not found exception
            throw  new Pluf_Exception('Not found');
        }
        $form = Pluf_Shortcuts_GetFormForModel($object, $request->REQUEST);
        $object = $form->save();
        return new Pluf_HTTP_Response_Json($object);
    }

    /**
     * صفحه را حذف می‌کند.
     *
     * @param unknown $request            
     * @param unknown $match            
     * @return Pluf_HTTP_Response_Json
     */
    public function delete ($request, $match)
    {
        $calendar = Pluf_Shortcuts_GetObjectOr404('Calendar_Calendar', $match['calendarId']);
        $object = Pluf_Shortcuts_GetObjectOr404('Calendar_Event', $match['eventId']);
        if($object->calendar !== $calendar->id){
            // XXX: maso, 2017: replace with not found exception
            throw  new Pluf_Exception('Not found');
        }
        $object->delete();
        return new Pluf_HTTP_Response_Json($object);
    }
}