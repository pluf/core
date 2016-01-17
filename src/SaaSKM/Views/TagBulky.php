<?php
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');
Pluf::loadFunction('Pluf_Shortcuts_GetFormForModel');
Pluf::loadFunction('SaaSKM_Shortcuts_GetTagOr404');

/**
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 */
class SaaSKM_Views_TagBulky
{

    public function create ($request, $match)
    {
        foreach ($request->FILES as $file) {
            if (is_readable($file['tmp_name'])) {
                $myfile = fopen($file['tmp_name'], "r") or
                         die("Unable to open file!");
                $json = fread($myfile, filesize($file['tmp_name']));
                fclose($myfile);
                $gosm = json_decode($json, true);
                { // Load all tags
                    foreach ($gosm as $node) {
                        // create location
                        $tag = new SaaSKM_Tag();
                        $tag->setFromFormData($node);
                        $tag->tenant = $request->tenant;
                        $tag->create();
                    }
                }
            }
        }
        return new Pluf_HTTP_Response_Json(new ArrayObject(array()));
        
//         $extra = array(
//                 'tenant' => $request->tenant
//         );
//         $form = new SaaSKM_Form_TagCreate(
//                 array_merge($request->REQUEST, $request->FILES), $extra);
//         return new Pluf_HTTP_Response_Json($form->save());
    }
}