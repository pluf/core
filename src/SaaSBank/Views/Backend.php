<?php
Pluf::loadFunction('SaaSBank_Shortcuts_GetEngineOr404');

/**
 *
 * @author maso <mostafa.barmsohry@dpq.co.ir>
 *        
 */
class SaaSBank_Views_Backend
{

    /**
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public function find ($request, $match)
    {
        $pag = new Pluf_Paginator(new SaaSBank_Backend());
        $pag->configure(array(), 
                array( // search
                        'title',
                        'description'
                ), 
                array( // sort
                        'id',
                        'title',
                        'creation_dtime'
                ));
        $pag->action = array();
        $pag->items_per_page = 20;
        $pag->model_view = 'global';
        $pag->sort_order = array(
                'creation_dtime',
                'DESC'
        );
        $pag->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($pag->render_object());
    }

    /**
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public function get ($request, $match)
    {}

    /**
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public function create ($request, $match)
    {}

    /**
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public function delete ($request, $match)
    {}

    /**
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public function update ($request, $match)
    {}
}
