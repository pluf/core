<?php

/**
 * Template tag to display the country/language from the code.
 *
 * @param string Iso code
 * @param string ('country') or 'lang'
 * @param bool Print the result (true)
 */
class Pluf_L10n_Tag extends Pluf_Template_Tag
{

    function start ($code, $what = 'country', $echo = true)
    {
        if ($what == 'country') {
            $cn = Pluf_L10n::getCountryCodes(true);
        } else {
            $cn = Pluf_L10n::getNativeLanguages();
        }
        if (! empty($cn[$code])) {
            if ($echo) {
                echo $cn[$code];
            } else {
                return $cn[$code];
            }
        }
    }
}
