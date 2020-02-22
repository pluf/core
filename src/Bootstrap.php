<?php

/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. http://dpq.co.ir
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
namespace Pluf;

/**
 * The main class of the framework.
 * From where all start.
 *
 * The __autoload function is automatically set.
 *
 * @data 1998 Start to support PHP5 and PHP5
 * @data 2020 Getting start to support PSR4 and PHP7
 */
class Bootstrap
{

    /**
     * Start the framework
     *
     * @param
     *            string Configuration file to use
     */
    public static function start($config)
    {
        $GLOBALS['_PX_starttime'] = microtime(true);
        $GLOBALS['_PX_uniqid'] = uniqid($GLOBALS['_PX_starttime'], true);
        $GLOBALS['_PX_signal'] = array();
        $GLOBALS['_PX_locale'] = array();
        self::loadConfig($config);
        date_default_timezone_set(self::f('time_zone', 'UTC'));
        mb_internal_encoding(self::f('encoding', 'UTF-8'));
        mb_regex_encoding(self::f('encoding', 'UTF-8'));
    }

    /**
     * Load the given configuration file.
     *
     * The configuration is saved in the $GLOBALS['_PX_config'] array.
     * The relations between the models are loaded in $GLOBALS['_PX_models'].
     *
     * @param
     *            string Configuration file to load.
     */
    private static function loadConfig($config_file)
    {
        if (is_array($config_file)) {
            $GLOBALS['_PX_config'] = $config_file;
        } else if (is_readable($config_file)) {
            $GLOBALS['_PX_config'] = require $config_file;
        } else {
            throw new Exception('Configuration file does not exist: ' . $config_file);
        }
        // Load the relations for each installed application. Each
        // application folder must be in the include path.
        self::loadRelations(! self::f('debug', false));
    }

    /**
     * Get the model relations and signals.
     *
     * If not in debug mode, it will automatically cache the
     * information. This allows one include file when many
     * applications and thus many includes are needed.
     *
     * Signals and relations are cached in the same file as the way to
     * go for signals is to put them in the relations.php file.
     *
     * @param
     *            bool Use the cache (true)
     */
    private static function loadRelations($usecache = true)
    {
        $GLOBALS['_PX_models'] = array();
        $GLOBALS['_PX_models_init_cache'] = array();
        $apps = self::f('installed_apps', array());
        $cache = self::f('tmp_folder') . '/Pluf_relations_cache_' . md5(serialize($apps)) . '.phps';
        if ($usecache and file_exists($cache)) {
            list ($GLOBALS['_PX_models'], $GLOBALS['_PX_models_related'], $GLOBALS['_PX_signal']) = include $cache;
            return;
        }
        $m = $GLOBALS['_PX_models'];
        foreach ($apps as $app) {
            $moduleName = "Pluf\\" . $app . "\\Module";
            // Load PSR4 modules
            $m = array_merge_recursive($m, $moduleName::relations);
        }
        $GLOBALS['_PX_models'] = $m;

        $_r = array(
            'relate_to' => array(),
            'relate_to_many' => array()
        );
        foreach ($GLOBALS['_PX_models'] as $model => $relations) {
            foreach ($relations as $type => $related) {
                foreach ($related as $related_model) {
                    if (! isset($_r[$type][$related_model])) {
                        $_r[$type][$related_model] = array();
                    }
                    $_r[$type][$related_model][] = $model;
                }
            }
        }
        $_r['foreignkey'] = $_r['relate_to'];
        $_r['manytomany'] = $_r['relate_to_many'];
        $GLOBALS['_PX_models_related'] = $_r;

        // $GLOBALS['_PX_signal'] is automatically set by the require
        // statement and possibly in the configuration file.
        if ($usecache) {
            $s = var_export(array(
                $GLOBALS['_PX_models'],
                $GLOBALS['_PX_models_related'],
                $GLOBALS['_PX_signal']
            ), true);
            if (@file_put_contents($cache, '<?php return ' . $s . ';' . "\n", LOCK_EX)) {
                chmod($cache, 0755);
            }
        }
    }

    /**
     * Gets system configuration
     *
     * @param
     *            string Configuration variable
     * @param
     *            mixed Possible default value if value is not set ('')
     * @return mixed Configuration variable or default value if not defined.
     */
    public static function f($cfg, $default = '')
    {
        if (isset($GLOBALS['_PX_config'][$cfg])) {
            return $GLOBALS['_PX_config'][$cfg];
        }
        return $default;
    }

    /**
     * Access an array of configuration variables having a given
     * prefix.
     *
     * @param
     *            string Prefix.
     * @param
     *            bool Strip the prefix from the keys (false).
     * @return array Configuration variables.
     */
    public static function pf($pfx, $strip = false)
    {
        $ret = array();
        $pfx_len = strlen($pfx);
        foreach ($GLOBALS['_PX_config'] as $key => $val) {
            if (0 === strpos($key, $pfx)) {
                if (! $strip) {
                    $ret[$key] = $val;
                } else {
                    $ret[substr($key, $pfx_len)] = $val;
                }
            }
        }
        return $ret;
    }

    /**
     * Helper to load the default database connection.
     *
     * This method is just dispatching to the function define in the
     * configuration by the 'db_get_connection' key or use the default
     * 'Pluf_DB_getConnection'. If you want to use your own function,
     * take a look at the Pluf_DB_getConnection function to use the
     * same approach for your method.
     *
     * The extra parameters can be used to selectively connect to a
     * given database. When the ORM is getting a connection, it is
     * passing the current model as parameter. That way you could get
     * different databases for different models.
     *
     * @param
     *            mixed Extra parameters.
     * @return resource DB connection.
     */
    public static function &db($extra = null)
    {
        $func = self::f('db_get_connection', '\Pluf\DB::getConnection');
        $a = $func($extra);
        return $a;
    }
}

// /**
//  * Translate a string.
//  *
//  * @param
//  *            string String to be translated.
//  * @return string Translated string.
//  * @deprecated
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

// /**
//  * Autoload function.
//  *
//  * @param
//  *            string Class name.
//  */
// function Pluf_autoload($class_name)
// {
//     try {
//         Pluf::loadClass($class_name);
//     } catch (Exception $e) {
//         if (Pluf::f('debug')) {
//             print $e->getMessage();
//             die();
//         }
//         throw new Exception('Class not found:' . $class_name);
//     }
// }

// /*
//  * PHP 5.x support
//  */
// spl_autoload_register('Pluf_autoload');

// /**
//  * Exception to catch the PHP errors.
//  *
//  * @credits errd
//  *
//  * @see http://www.php.net/manual/en/function.set-error-handler.php
//  */
// class PlufErrorHandlerException extends Exception
// {

//     public function setLine($line)
//     {
//         $this->line = $line;
//     }

//     public function setFile($file)
//     {
//         $this->file = $file;
//     }
// }

// /**
//  * The function that is the real error handler.
//  */
// function PlufErrorHandler($code, $string, $file, $line)
// {
//     if (0 == error_reporting())
//         return false;
//     if (E_STRICT == $code && (0 === strpos($file, Pluf::f('pear_path', '/usr/share/php/')) or false !== strripos($file, 'pear'))) // if pear in the path, ignore
//     {
//         return;
//     }
//     $exception = new PlufErrorHandlerException($string, $code);
//     $exception->setLine($line);
//     $exception->setFile($file);
//     throw $exception;
// }

// // Set the error handler only if not performing the unittests.
// if (!defined('IN_UNIT_TESTS')) {
//     set_error_handler('PlufErrorHandler', error_reporting());
// }

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
