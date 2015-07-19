<?php
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');

/**
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class SaaS_Views_Configuration
{


    public $configurations_precond = array(
            'SaaS_Precondition::baseAccess'
    );
    
    /**
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function configurations ($request, $match)
    {
        if ($request->user->isAnonymous() ||
                 ! $request->user->hasPerm('SaaS.software-owner', 
                        $request->application)) {
            return new Pluf_HTTP_Response_Json(
                    $request->application->getConfigurationList(
                            array(
                                    SaaS_ConfigurationType::SYSTEM,
                                    SaaS_ConfigurationType::APPLICATION,
                                    SaaS_ConfigurationType::GENERAL
                            ), 
                            array(
                                    'other_read' => 1
                            )));
        }
        if (! $request->user->administrator) {
            return new Pluf_HTTP_Response_Json(
                    $request->application->getConfigurationList(
                            array(
                                    SaaS_ConfigurationType::SYSTEM,
                                    SaaS_ConfigurationType::APPLICATION,
                                    SaaS_ConfigurationType::GENERAL
                            ), 
                            array(
                                    'owner_read' => 1
                            )));
        }
        return new Pluf_HTTP_Response_Json(
                $request->application->getConfigurationList(
                        array(
                                SaaS_ConfigurationType::SYSTEM,
                                SaaS_ConfigurationType::APPLICATION,
                                SaaS_ConfigurationType::GENERAL
                        )));
    }

    public $get_precond = array(
            'SaaS_Precondition::baseAccess'
    );

    public function get ($request, $match)
    {
        $config = Pluf_Shortcuts_GetObjectOr404('SaaS_Configuration', $match[2]);
        // XXX: maso, 1394: بررسی نکات امنیتی
        return new Pluf_HTTP_Response_Json($config->data);
    }

    public $getByName_precond = array(
            'SaaS_Precondition::baseAccess'
    );

    public function getByName ($request, $match)
    {
        $config = $request->application->fetchConfiguration($match[2]);
        // XXX: maso, 1394: بررسی نکات امنیتی
        return new Pluf_HTTP_Response_Json($config->data);
    }

    public $update_precond = array(
            'SaaS_Precondition::applicationOwner'
    );

    /**
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function update ($request, $match)
    {
        if ($request->user->isAnonymous() ||
                 ! $request->user->hasPerm('SaaS.software-owner', 
                        $request->application)) {
            throw new Pluf_Exception_PermissionDenied();
        }
        
        if (! $request->user->administrator) {
            return new Pluf_HTTP_Response_Json(
                    $request->application->getConfiguration(
                            SaaS_ConfigurationType::APPLICATION));
        }
        return new Pluf_HTTP_Response_Json(
                $request->application->getConfiguration(
                        SaaS_ConfigurationType::SYSTEM));
    }

    public $create_precond = array(
            'SaaS_Precondition::applicationOwner'
    );

    /**
     *
     * @param unknown $request            
     * @param unknown $match            
     * @throws Pluf_Exception_PostMethodSuported
     * @return Pluf_HTTP_Response_Json
     */
    public function create ($request, $match)
    {
        $extra = array(
                'application' => $request->application
        );
        $form = new SaaS_Form_Configuration(
                array_merge($request->POST, $request->FILES), $extra);
        $cuser = $form->save();
        return new Pluf_HTTP_Response_Json($cuser);
    }
}