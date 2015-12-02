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

    public function get ($request, $match)
    {
        // GET data
        $app = new SaaS_Application($match[1]);
        $resource = new SaaS_Resource($match[2]);
        // Check permission
        if ($app->id != $resource->application) {
            throw new Pluf_Exception('association fail');
        }
        SaaS_Precondition::userCanAccessApplication($request, $app);
        SaaS_Precondition::userCanAccessResource($request, $resource);
        // Do
        return new Pluf_HTTP_Response_Json($resource);
    }

    public function update ($request, $match)
    {
        // GET data
        $app = new SaaS_Application($match[1]);
        $resource = new SaaS_Resource($match[2]);
        // Check permission
        if ($app->id != $resource->application) {
            throw new Pluf_Exception('association fail');
        }
        SaaS_Precondition::userCanAccessApplication($request, $app);
        SaaS_Precondition::userCanUpdateResource($request, $resource);
        // Do
        $params = array(
                'application' => $app,
                'user' => $request->user,
                'resource' => $resource
        );
        $form = new SaaS_Form_ResourceUpdate(
                array_merge($request->REQUEST, $request->FILES), $params);
        $res = $form->update();
        return new Pluf_HTTP_Response_Json($res);
    }

    public function delete ($request, $match)
    {
        // GET data
        $app = new SaaS_Application($match[1]);
        $resource = new SaaS_Resource($match[2]);
        // Check permission
        if ($app->id != $resource->application) {
            throw new Pluf_Exception('association fail');
        }
        SaaS_Precondition::userCanAccessApplication($request, $app);
        SaaS_Precondition::userCanDeleteResource($request, $resource);
        
        $temp = new SaaS_Resource($match[2]);
        $temp->delete();
        // Do
        return new Pluf_HTTP_Response_Json($resource);
    }

    public function download ($request, $match)
    {
        // GET data
        $app = new SaaS_Application($match[1]);
        $resource = new SaaS_Resource($match[2]);
        // Check permission
        if ($app->id != $resource->application) {
            throw new Pluf_Exception('association fail');
        }
        SaaS_Precondition::userCanAccessApplication($request, $app);
        SaaS_Precondition::userCanAccessResource($request, $resource);
        // Do
        $resource->downloads += 1;
        $resource->update();
        $response = new Pluf_HTTP_Response_File($resource->getAbslotePath(), 
                $resource->getMimeType());
        $response->headers['Content-Disposition'] = 'attachment; filename="' .
                 $resource->file . '"';
        return $response;
    }

    public function find ($request, $match)
    {
        // GET data
        $app = new SaaS_Application($match[1]);
        // Check permission
        SaaS_Precondition::userCanAccessApplication($request, $app);
        // Do find
        $pag = new Pluf_Paginator(new SaaS_Resource());
        $sql = new Pluf_SQL('application=%s', 
                array(
                        $app->id
                ));
        $pag->forced_where = $sql;
        $list_display = array(
                'flie' => __('file'),
                'description' => __('description')
        );
        $search_fields = array(
                'file',
                'description'
        );
        $sort_fields = array(
                'creation_dtime'
        );
        $pag->configure($list_display, $search_fields, $sort_fields);
        $pag->action = array();
        $pag->items_per_page = $this->getListCount($request);
        $pag->no_results_text = __('no resource is found');
        $pag->sort_order = array(
                'creation_dtime',
                'DESC'
        );
        $pag->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($pag->render_object());
    }

    /**
     * تعداد گزینه‌های یک لیست را تعیین می‌کند.
     *
     * TODO: maso, 1394: این تعداد می‌تواند برای کاربران متفاوت باشد.
     *
     * @param unknown $request            
     * @return number
     */
    private function getListCount ($request)
    {
        $count = 5;
        if (array_key_exists('_px_count', $request->REQUEST)) {
            $count = $request->REQUEST['_px_count'];
            if ($count > 20 || $count < 1) {
                $count = 20;
            }
        }
        return $count;
    }
}