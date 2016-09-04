<?php
Pluf::loadFunction('SaaSBank_Shortcuts_GetEngineOr404');

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
        // $params = array ();
        // return Pluf_Shortcuts_RenderToResponse ( 'index.html', $params,
        // $request );
    }

    /**
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
        $receipt->save();
        return new Pluf_HTTP_Response_Json($receipt);
    }

    /**
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public function get ($request, $match)
    {
        // $params = array ();
        // return Pluf_Shortcuts_RenderToResponse ( 'index.html', $params,
        // $request );
    }

    /**
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public function update ($request, $match)
    {
        // $params = array ();
        // return Pluf_Shortcuts_RenderToResponse ( 'index.html', $params,
        // $request );
    }

    /**
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public function delete ($request, $match)
    {
        // $params = array ();
        // return Pluf_Shortcuts_RenderToResponse ( 'index.html', $params,
        // $request );
    }
}
