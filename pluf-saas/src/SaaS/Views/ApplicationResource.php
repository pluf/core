<?php

/**
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class SaaS_Views_ApplicationResource
{

    public function create ($request, $match)
    {
        // GET data
        $app = new SaaS_Application($match[1]);
        // Check permission
        SaaS_Precondition::userCanUpdateApplication($request, $app);
        // Do update
        $params = array(
                'application' => $app,
                'user' => $request->user
        );
        $form = new SaaS_Form_ResourceCreate(
                array_merge($request->REQUEST, $request->FILES), $params);
        $res = $form->save();
        return new Pluf_HTTP_Response_Json($res);
    }
}