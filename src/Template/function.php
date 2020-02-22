<?php 



namespace Pluf\Template ;

use Pluf\Bootstrap;
use Pluf\Template;

/**
 * Set a string to be safe for display.
 *
 * @param
 *            string String to be safe for display.
 * @return string SafeString
 */
function unsafe($string)
{
    return new SafeString($string, true);
}

/**
 * Special htmlspecialchars that can handle the objects.
 *
 * @param
 *            string String proceeded by htmlspecialchars
 * @return string String like if htmlspecialchars was not applied
 */
function htmlspecialchars($string)
{
    return htmlspecialchars((string) $string, ENT_COMPAT, 'UTF-8');
}

/**
 * Modifier plugin: Convert the date from GMT to local and format it.
 *
 * This is used as all the datetime are stored in GMT in the database.
 *
 * @param string $date
 *            input date string considered GMT
 * @param string $format
 *            strftime format for output ('%b %e, %Y')
 * @return string date in localtime
 */
function dateFormat($date, $format = '%b %e, %Y')
{
    if (substr(PHP_OS, 0, 3) == 'WIN') {
        $_win_from = array(
            '%e',
            '%T',
            '%D'
        );
        $_win_to = array(
            '%#d',
            '%H:%M:%S',
            '%m/%d/%y'
        );
        $format = str_replace($_win_from, $_win_to, $format);
    }
    $date = date('Y-m-d H:i:s', strtotime($date . ' GMT'));
    return strftime($format, strtotime($date));
}

/**
 * Modifier plugin: Format a unix time.
 *
 * Warning: date format is directly to be used, not consideration of
 * GMT or local time.
 *
 * @param int $time
 *            input date string considered GMT
 * @param string $format
 *            strftime format for output ('Y-m-d H:i:s')
 * @return string formated time
 */
function timeFormat($time, $format = 'Y-m-d H:i:s')
{
    return date($format, $time);
}

/**
 * Special echo function that checks if the string to output is safe
 * or not, if not it is escaped.
 *
 * @param
 *            mixed Input
 * @return string Safe to display in HTML.
 */
function safeEcho($mixed, $echo = true)
{
    if ($echo) {
        echo (! is_object($mixed) or '\Pluf\Template\SafeString' != get_class($mixed)) ? htmlspecialchars($mixed, ENT_COMPAT, 'UTF-8') : $mixed->value;
    } else {
        return (! is_object($mixed) or '\Pluf\Template\SafeString' != get_class($mixed)) ? htmlspecialchars($mixed, ENT_COMPAT, 'UTF-8') : $mixed->value;
    }
}

/**
 * New line to <br /> returning a safe string.
 *
 * @param
 *            mixed Input
 * @return string Safe to display in HTML.
 */
function nl2br($mixed)
{
    if (! is_object($mixed) or '\Pluf\Template\SafeString' !== get_class($mixed)) {
        return Template::markSafe(nl2br(htmlspecialchars((string) $mixed, ENT_COMPAT, 'UTF-8')));
    } else {
        return Template::markSafe(nl2br($mixed->value));
    }
}

/**
 * Var export returning a safe string.
 *
 * @param
 *            mixed Input
 * @return string Safe to display in HTML.
 */
function varExport($mixed)
{
    return unsafe('<pre>' . Pluf_esc(var_export($mixed, true)) . '</pre>');
}

/**
 * Display the date in a "6 days, 23 hours ago" style.
 */
function dateAgo($date, $f = 'withal')
{
    Bootstrap::loadFunction('Pluf_Date_Easy');
    $date = dateFormat($date, '%Y-%m-%d %H:%M:%S');
    if ($f == 'withal') {
        return Pluf_Date_Easy($date, null, 2, __('now'));
    } else {
        return Pluf_Date_Easy($date, null, 2, __('now'), false);
    }
}

/**
 * Display the time in a "6 days, 23 hours ago" style.
 */
function timeAgo($date, $f = "withal")
{
//     Pluf::loadFunction('Pluf_Date_Easy');
    $date = timeFormat($date);
    if ($f == 'withal') {
        return \Pluf\Date\easy($date, null, 2, __('now'));
    } else {
        return  \Pluf\Date\easy($date, null, 2, __('now'), false);
    }
}

/**
 * Hex encode an email excluding the "mailto:".
 */
function safeEmail($email)
{
    $email = chunk_split(bin2hex($email), 2, '%');
    $email = '%' . substr($email, 0, strlen($email) - 1);
    return Template::markSafe($email);
}

/**
 * Returns the first item in the given array.
 *
 * @param array $array
 * @return mixed An empty string if $array is not an array.
 */
function first($array)
{
    $array = (array) $array;
    $result = array_shift($array);
    if (null === $result) {
        return '';
    }
    
    return $result;
}

/**
 * Returns the last item in the given array.
 *
 * @param array $array
 * @return mixed An empty string if $array is not an array.
 */
function last($array)
{
    $array = (array) $array;
    $result = array_pop($array);
    if (null === $result) {
        return '';
    }
    
    return $result;
}