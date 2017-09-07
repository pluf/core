<?php

/**
 * User notification service
 * 
 * @author maso
 *
 */
class User_Notify
{

    /**
     * Push message for the user
     *
     * @param Pluf_User $user
     * @param array $templates
     * @param array $context
     */
    public static function push($user, $templates, $context)
    {
        foreach ($templates as $engineName => $template) {
            $engineClass = 'User_Notify_Engine_' . $engineName;
            $engine = new $engineClass();
            $engine->push($user, $template, $context);
        }
    }
}