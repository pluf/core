<?php

/**
 * Notify engine for mail
 * 
 * @author maso
 *
 */
class User_Notify_Engine_Mail implements User_Notify_Engine
{

    /**
     *
     * {@inheritdoc}
     * @see User_Notify_Engine::push()
     */
    public function push($user, $template, $context)
    {
        $subject = '=?utf-8?B?' . base64_encode($context['subject']) . '?=';
        
        // Messae
        $tmpl = new Pluf_Template($template);
        
        // Send mail
        $email = new Pluf_Mail(Setting_Service::get('notify.mail', 'info@pluf.ir'), $user->email, $subject);
        $email->addHtmlMessage($tmpl->render(new Pluf_Template_Context($context)));
        $res = $email->sendMail();
        if (is_a($res, 'PEAR_Error')) {
            throw new Pluf_Exception($res);
        }
    }
}