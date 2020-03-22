<?php 

// /**
//  * Translate a string.
//  *
//  * @param
//  *            string String to be translated.
//  * @return string Translated string.
//  * @deprecated Server side translateion will be removed
//  */
// function __($str)
// {
//     return $str;
// }

// /**
//  * Translate the plural form of a string.
//  *
//  * @param
//  *            string Singular form of the string.
//  * @param
//  *            string Plural form of the string.
//  * @param
//  *            int Number of elements.
//  * @return string Translated string.
//  * @deprecated
//  */
// function _n($sing, $plur, $n)
// {
//     return $plur;
// }

// // /**
// //  * Autoload function.
// //  *
// //  * @param
// //  *            string Class name.
// //  */
// // function Pluf_autoload($class_name)
// // {
// //     try {
// //         \Pluf::loadClass($class_name);
// //     } catch (\Exception $e) {
// //         if (\Pluf::f('debug')) {
// //             print $e->getMessage();
// //             die();
// //         }
// //         throw \Pluf\Exception('Class not found:' . $class_name);
// //     }
// // }

// // /*
// //  * PHP 5.x support
// //  */
// // spl_autoload_register('Pluf_autoload');

// // /**
// //  * Exception to catch the PHP errors.
// //  *
// //  * @credits errd
// //  *
// //  * @see http://www.php.net/manual/en/function.set-error-handler.php
// //  */
// // class PlufErrorHandlerException extends Exception
// // {

// //     public function setLine($line)
// //     {
// //         $this->line = $line;
// //     }

// //     public function setFile($file)
// //     {
// //         $this->file = $file;
// //     }
// // }

// // /**
// //  * The function that is the real error handler.
// //  */
// // function PlufErrorHandler($code, $string, $file, $line)
// // {
// //     if (0 == error_reporting())
// //         return false;
// //     if (E_STRICT == $code && (0 === strpos($file, Pluf::f('pear_path', '/usr/share/php/')) or false !== strripos($file, 'pear'))) // if pear in the path, ignore
// //     {
// //         return;
// //     }
// //     $exception = new PlufErrorHandlerException($string, $code);
// //     $exception->setLine($line);
// //     $exception->setFile($file);
// //     throw $exception;
// // }

// // // Set the error handler only if not performing the unittests.
// // if (! defined('IN_UNIT_TESTS')) {
// //     set_error_handler('PlufErrorHandler', error_reporting());
// // }

// /**
//  * Shortcut needed all over the place.
//  *
//  * Note that in some cases, we need to escape strings not in UTF-8, so
//  * this is not possible to safely use a call to htmlspecialchars. This
//  * is why str_replace is used.
//  *
//  * @param
//  *            string Raw string
//  * @return string HTML escaped string
//  */
// function Pluf_esc($string)
// {
//     return str_replace(array(
//         '&',
//         '"',
//         '<',
//         '>'
//     ), array(
//         '&amp;',
//         '&quot;',
//         '&lt;',
//         '&gt;'
//     ), (string) $string);
// }