<?php

/**
 * Display the messages for the current user.
 */
class Pluf_Template_Tag_Messages extends Pluf_Template_Tag
{

    function start ($user)
    {
        if (is_object($user) && ! $user->isAnonymous()) {
            $messages = $user->getAndDeleteMessages();
            if (count($messages) > 0) {
                echo '<div class="user-messages">' . "\n" . '<ul>' . "\n";
                foreach ($messages as $m) {
                    echo '<li>' . $m . '</li>';
                }
                echo '</ul></div>';
            }
        }
    }
}
