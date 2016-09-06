<?php
Pluf::loadFunction('SaaSBank_Shortcuts_GetEngineOr404');

/**
 *
 * @author maso <mostafa.barmsohry@dpq.co.ir>
 *        
 */
class SaaSBank_Views_Backend
{
    // XXX: maso, 1395: add security
    /**
     * فهرست تمام پشتوانه‌ها رو تعیین می‌کنه.
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
        if (! Pluf::f('saas_bank_centeral', true)) {
            // XXX: maso, 1395: این بخش باید تست بشه
            $pag->forced_where = new Pluf_SQL('tenant=%s', 
                    array(
                            'tenant',
                            $request->tenant->id
                    ));
        }
        return new Pluf_HTTP_Response_Json($pag->render_object());
    }

    /**
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public function createParameter ($request, $match)
    {
        $type = 'not set';
        if (array_key_exists('type', $request->REQUEST)) {
            $type = $request->REQUEST['type'];
        }
        $engine = SaaSBank_Shortcuts_GetEngineOr404($type);
        return new Pluf_HTTP_Response_Json($engine->getParameters());
    }

    /**
     * یک نمونه جدید از متور پرداخت ایجاد می‌کند.
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public function create ($request, $match)
    {
        $type = 'not set';
        if (array_key_exists('type', $request->REQUEST)) {
            $type = $request->REQUEST['type'];
        }
        $engine = SaaSBank_Shortcuts_GetEngineOr404($type);
        $params = array(
                'tenant' => $request->tenant,
                'engine' => $engine
        );
        $form = new SaaSBank_Form_BackendNew($request->REQUEST, $params);
        $backend = $form->save();
        return new Pluf_HTTP_Response_Json($backend);
    }

    /**
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public function get ($request, $match)
    {
        $backend = SaaSBank_Shortcuts_GetBankOr404($match['id']);
        return new Pluf_HTTP_Response_Json($backend);
    }

    /**
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public function delete ($request, $match)
    {
        $backend = SaaSBank_Shortcuts_GetBankOr404($match['id']);
        $backend->delete();
        return new Pluf_HTTP_Response_Json($backend);
    }

    /**
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public function update ($request, $match)
    {
        $backend = SaaSBank_Shortcuts_GetBankOr404($match['id']);
        $params = array(
                'backend' => $backend
        );
        $form = new SaaSBank_Form_BackendUpdate($request->REQUEST, $params);
        $backend = $form->update();
        return new Pluf_HTTP_Response_Json($backend);
    }
}
