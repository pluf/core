<?php
Pluf::loadFunction('SaaSBank_Shortcuts_GetEngineOr404');
Pluf::loadFunction('SaaSBank_Shortcuts_GetReceiptOr404');

/**
 *
 * @author maso <mostafa.barmsohry@dpq.co.ir>
 *        
 */
class SaaSBank_Views_Receipt
{

    /**
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public function find ($request, $match)
    {
        $pag = new Pluf_Paginator(new SaaSBank_Receipt());
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
        $pag->sort_order = array(
                'creation_dtime',
                'DESC'
        );
        $pag->setFromRequest($request);
        $pag->forced_where = new Pluf_SQL('tenant=%s', 
                array(
                        $request->tenant->id
                ));
        return new Pluf_HTTP_Response_Json($pag->render_object());
    }

    /**
     * پرداخت جدیدی ایجاد می‌کند.
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public function create ($request, $match)
    {
        $extra = array(
                'tenant' => $request->tenant
        );
        $form = new SaaSBank_Form_ReceiptNew(array_merge($request->REQUEST), 
                $extra);
        $receipt = $form->save(false);
        $backend = $receipt->get_backend();
        $engine = $backend->get_engine();
        $engine->create($receipt);
        $receipt->create();
        return new Pluf_HTTP_Response_Json($receipt);
    }

    /**
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public function get ($request, $match)
    {
        $receipt = SaaSBank_Shortcuts_GetReceiptOr404($match['id']);
        return new Pluf_HTTP_Response_Json($receipt);
    }

    /**
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public function getBySecureId ($request, $match)
    {
        $receipt = new SaaSBank_Receipt();
        $sql = new Pluf_SQL('secure_id=%s', 
                array(
                        $match['secure_id']
                ));
        $receipt = $receipt->getOne($sql->gen());
        return new Pluf_HTTP_Response_Json($receipt);
    }

    /**
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public function update ($request, $match)
    {
        $receipt = SaaSBank_Shortcuts_GetReceiptOr404($match['id']);
        $backend = $receipt->get_backend();
        $engine = $backend->get_engine();
        if ($engine->update($receipt)) {
            $receipt->update();
        }
        return new Pluf_HTTP_Response_Json($receipt);
    }

    /**
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public function updateBySecureId ($request, $match)
    {
        $receipt = new SaaSBank_Receipt();
        $sql = new Pluf_SQL('secure_id=%s', 
                array(
                        $match['secure_id']
                ));
        $receipt = $receipt->getOne($sql->gen());
        $backend = $receipt->get_backend();
        $engine = $backend->get_engine();
        if ($engine->update($receipt)) {
            $receipt->update();
        }
        return new Pluf_HTTP_Response_Json($receipt);
    }

    /**
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public function delete ($request, $match)
    {
        $receipt = SaaSBank_Shortcuts_GetReceiptOr404($match['id']);
        $receipt->delete();
        return new Pluf_HTTP_Response_Json($receipt);
    }
}
