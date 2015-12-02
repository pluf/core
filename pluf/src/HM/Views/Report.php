<?php
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');
Pluf::loadFunction('Pluf_Shortcuts_RenderToResponse');
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('Pluf_Shortcuts_GetFormForModel');

/**
 * لایه نمایش برای واحد را ایجاد می‌کند
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class HM_Views_Report
{

    /**
     *
     * @var unknown
     */
    public $partCorrelation_precond = array(
            'Pluf_Precondition::adminRequired'
    );

    /**
     *
     * @param unknown $request            
     * @param unknown $match            
     * @return Pluf_HTTP_Response_Json
     */
    public function partCorrelation ($request, $match)
    {
        // Paginator to paginate messages
        $pag = new Pluf_Paginator(new HM_Part());
//         $pag->forced_where = new Pluf_SQL('count > %s',
//                 array(
//                         5
//                 ));
        $pag->list_filters = array(
                'reporter',
                'community'
        );
        $list_display = array(
                'title' => __('part title')
        );
        $search_fields = array(
                'id',
                'title',
                'message'
        );
        $sort_fields = array(
                'id',
                'title',
                'creation_dtime',
                'modif_dtime'
        );
        $pag->configure($list_display, $search_fields, $sort_fields);
        $pag->model_view = $match[1].'_correlation';
        $pag->items_per_page = 10;
        $pag->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($pag->render_object());
    }

}