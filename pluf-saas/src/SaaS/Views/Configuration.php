<?php
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');
Pluf::loadFunction('Pluf_Shortcuts_RenderToResponse');
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('Pluf_Shortcuts_GetFormForModel');

/**
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class SaaS_Views_Configuration extends Pluf_Views
{

    /**
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function configurations ($request, $match)
    {
        $application_id = $match[1];
        $application = Pluf_Shortcuts_GetObjectOr404('SaaS_Application', 
                $application_id);
        if ($request->user->isAnonymous() ||
                 ! $request->user->hasPerm('SaaS.software-owner', $application)) {
            return new Pluf_HTTP_Response_Json(
                    $application->getConfigurationList(
                            array(
                                    SaaS_ConfigurationType::GENERAL
                            )));
        }
        if (! $request->user->administrator) {
            return new Pluf_HTTP_Response_Json(
                    $application->getConfigurationList(
                            array(
                                    SaaS_ConfigurationType::APPLICATION,
                                    SaaS_ConfigurationType::GENERAL
                            )));
        }
        return new Pluf_HTTP_Response_Json(
                $application->getConfigurationList(
                        array(
                                SaaS_ConfigurationType::SYSTEM,
                                SaaS_ConfigurationType::APPLICATION,
                                SaaS_ConfigurationType::GENERAL
                        )));
    }
    

    public $configuration_precond = array(
            'Pluf_Precondition::loginRequired'
    );

    /**
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function configuration ($request, $match)
    {
        $application = Pluf_Shortcuts_GetObjectOr404('SaaS_Application', 
                $match[1]);
        if ($request->user->isAnonymous() ||
                 ! $request->user->hasPerm('SaaS.software-owner', $application)) {
            throw new Pluf_Exception_PermissionDenied();
        }
        
        if (! $request->user->administrator) {
            return new Pluf_HTTP_Response_Json(
                    $application->getConfiguration(
                            SaaS_ConfigurationType::APPLICATION));
        }
        return new Pluf_HTTP_Response_Json(
                $application->getConfiguration(SaaS_ConfigurationType::SYSTEM));
    }
    

    public $create_precond = array(
            'Pluf_Precondition::loginRequired'
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
        $application = Pluf_Shortcuts_GetObjectOr404('SaaS_Application', 
                $match[1]);
        SaaS_Precondition::applicationOwner($request, $application);
        $extra = array(
                'application' => $application
        );
        $form = new SaaS_Form_Configuration(
                array_merge($request->GET, $request->FILES), $extra);
        $cuser = $form->save();
        return new Pluf_HTTP_Response_Json($cuser);
    }
}