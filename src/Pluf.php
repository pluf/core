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
use Pluf\Cache;
use Pluf\Module;
use Pluf\Options;
use Pluf\Data\ModelDescription;
use Pluf\Data\Repository;
use Pluf\Data\Schema;
use Pluf\Db\Connection;
use Pluf\Db\Connection\Dumper;
use Pluf\HTTP\Request;

/**
 * The main class of the framework.
 * From where all start.
 *
 * The __autoload function is automatically set.
 */
class Pluf
{

    public static ?Options $options = null;

    public static ?Cache $cache = null;

    public static ?Connection $dbConnection = null;

    public static ?Schema $dataSchema = null;

    /**
     * Start the framework
     *
     * @param
     *            string Configuration file to use
     */
    public static function start($config)
    {
        self::$cache = null;
        self::$dbConnection = null;
        self::$options = null;
        self::$dataSchema = null;

        // Load configurations
        $GLOBALS['_PX_starttime'] = microtime(true);
        $GLOBALS['_PX_uniqid'] = uniqid($GLOBALS['_PX_starttime'], true);
        $GLOBALS['_PX_signal'] = array();
        $GLOBALS['_PX_locale'] = array();

        // Load options
        if (is_array($config)) {
            $GLOBALS['_PX_config'] = $config;
        } else if (false !== ($file = Pluf::fileExists($config))) {
            $GLOBALS['_PX_config'] = require $file;
        } else {
            throw new Exception('Configuration file does not exist: ' . $config);
        }
        self::$options = new Options($GLOBALS['_PX_config']);

        // Load the relations for each installed application. Each
        // application folder must be in the include path.
        // ModelUtils::loadRelations(! Pluf::getConfig('debug', false));

        date_default_timezone_set(Pluf::getConfig('time_zone', 'UTC'));
        mb_internal_encoding(Pluf::getConfig('encoding', 'UTF-8'));
        mb_regex_encoding(Pluf::getConfig('encoding', 'UTF-8'));

        // Load modules
        Module::loadModules();
    }

    /**
     *
     * @deprecated
     */
    public static function f($cfg, $default = '')
    {
        if (isset($GLOBALS['_PX_config'][$cfg])) {
            return $GLOBALS['_PX_config'][$cfg];
        }
        return $default;
    }

    /**
     *
     * @deprecated
     */
    public static function pf(string $prefix, $strip = false)
    {
        return self::getConfigByPrefix($prefix, $strip);
    }

    /**
     * Gets system configuration
     *
     * @param string $key
     *            Configuration key
     * @param
     *            mixed Possible default value if value is not set ('')
     * @return mixed Configuration variable or default value if not defined.
     */
    public static function getConfig(string $key, $default = '')
    {
        $val = self::$options->$key;
        if (isset($val)) {
            return $val;
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
    public static function getConfigByPrefix(string $prefix, bool $strip = false)
    {
        return self::$options->startsWith($prefix, $strip);
    }

    /**
     * Returns a given object.
     *
     *
     * Loads automatically the corresponding class file if needed.
     * If impossible to get the class $model, exception is thrown.
     *
     * @param
     *            string Model to load.
     * @param
     *            mixed Extra parameters for the constructor of the model.
     */
    public static function factory($model, $params = null)
    {
        if ($params !== null) {
            return new $model($params);
        }
        return new $model();
    }

    /**
     * Load a class depending on its name.
     *
     * Throw an exception if not possible to load the class.
     *
     * @param
     *            string Class to load.
     */
    public static function loadClass($class)
    {
        if (class_exists($class, false)) {
            return;
        }
        $file = str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
        if (! file_exists(stream_resolve_include_path($file))) {
            return;
        }
        include_once $file;
        if (class_exists($class, false) || interface_exists($class, false)) {
            return;
        }
        $error = 'Impossible to load the class: ' . $class . "\n" . 'Tried to include: ' . $file . "\n" . 'Include path: ' . get_include_path();
        throw new Exception($error);
    }

    /**
     * Load a function depending on its name.
     *
     * The implementation file of the function
     * MyApp_Youpla_Boum_Stuff() is MyApp/Youpla/Boum.php That way it
     * is possible to group all the related function in one file.
     *
     * Throw an exception if not possible to load the function.
     *
     * @param
     *            string Function to load.
     *            
     * @deprecated will ber removed in next version
     */
    public static function loadFunction($function)
    {
        if (function_exists($function)) {
            return;
        }
        $elts = explode('_', $function);
        array_pop($elts);
        $file = implode(DIRECTORY_SEPARATOR, $elts) . '.php';
        if (false !== ($file = Pluf::fileExists($file))) {
            include_once $file;
        }
        if (! function_exists($function)) {
            throw new Exception('Impossible to load the function: ' . $function);
        }
    }

    /**
     * Hack for [[php file_exists()]] that checks the include_path.
     *
     * Use this to see if a file exists anywhere in the include_path.
     *
     * <code type="php">
     * $file = 'path/to/file.php';
     * if (Pluf::fileExists('path/to/file.php')) {
     * include $file;
     * }
     * </code>
     *
     * @credits Paul M. Jones <pmjones@solarphp.net>
     *
     * @param string $file
     *            Check for this file in the include_path.
     * @return mixed Full path to the file if the file exists and
     *         is readable in the include_path, false if not.
     */
    public static function fileExists($file)
    {
        $file = trim($file);
        if (! $file) {
            return false;
        }
        // using an absolute path for the file?
        // dual check for Unix '/' and Windows '\',
        // or Windows drive letter and a ':'.
        $abs = ($file[0] == '/' || $file[0] == '\\' || $file[1] == ':');
        if ($abs && file_exists($file)) {
            return $file;
        }
        // using a relative path on the file
        $path = explode(PATH_SEPARATOR, get_include_path());
        foreach ($path as $dir) {
            // strip Unix '/' and Windows '\'
            $target = rtrim($dir, '\\/') . DIRECTORY_SEPARATOR . $file;
            try {
                if (file_exists($target)) {
                    return $target;
                }
            } catch (Exception $e) {}
        }
        // never found it
        return false;
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
    /**
     * Get the default DB connection.
     *
     * The default database connection is defined in the configuration file
     * through the following configuration variables:
     * - db_login : Login to connect to the database
     * - db_password : Password to the database
     * - db_server : Name of the server
     * - db_database : Name of the database
     * - db_table_prefix : Prefix for the table names
     * - db_version : Version of the database engine
     * - db_engine : Engine for exampe 'MySQL', 'SQLite'
     *
     * Once the first connection is created the following calls to Pluf::db()
     * are getting the same connection.
     */
    public static function &db($extra = null)
    {
        if (! isset(self::$dbConnection)) {
            $options = self::getConfigByPrefix('db_', true);
            self::$dbConnection = Connection::connect($options->dsn, $options->user, $options->passwd);
            if ($options->dumper) {
                $optionsDumper = $options->startsWith('dumber_', true);
                $optionsDumper->connection = self::$dbConnection;
                self::$dbConnection = new Dumper($optionsDumper);
            }
        }
        return self::$dbConnection;
    }

    /**
     * Get Curretn request
     *
     * @deprecated This will removed in the next major version. Pleas use Request::getCurrent()
     * @return Request|NULL
     */
    public static function getCurrentRequest()
    {
        return Request::getCurrent();
    }

    public static function getCache()
    {
        if (! isset(self::$cache)) {
            // load cache
            self::$cache = Cache::getInstance(self::getConfigByPrefix('cache_', true));
        }
        return self::$cache;
    }

    public static function getDataSchema()
    {
        if (! isset(self::$dataSchema)) {
            self::$dataSchema = Schema::getInstance(self::getConfigByPrefix('data_schema_', true));
        }
        return self::$dataSchema;
    }

    /**
     * Gets new instance of repository
     *
     * @param mixed $option
     * @return \Pluf\Data\Repository\ModelRepository | \Pluf\Data\Repository\RelationRepository
     */
    public static function getDataRepository($option): Repository
    {
        // XXX: maso, 2020: adding cache manager for repository
        if (is_array($option)) {
            $options = new Options($option);
        } else if ($option instanceof \Pluf\Options) {
            $options = $option;
        } else {
            $options = new Options();
        }

        if (is_string($option)) {
            $options->model = $option;
        }

        if ($option instanceof ModelDescription) {
            $options->model = $option->type;
        }
        if ($option instanceof Pluf_Model) {
            $options->model = get_class($option);
        }

        $options->connection = self::db();
        $options->schema = self::getDataSchema();

        return Repository::getInstance($options);
    }
}

/**
 * Translate a string.
 *
 * @param
 *            string String to be translated.
 * @return string Translated string.
 * @deprecated Server side translateion will be removed
 */
function __($str)
{
    return $str;
}

/**
 * Translate the plural form of a string.
 *
 * @param
 *            string Singular form of the string.
 * @param
 *            string Plural form of the string.
 * @param
 *            int Number of elements.
 * @return string Translated string.
 * @deprecated
 */
function _n($sing, $plur, $n)
{
    return $plur;
}

/**
 * Autoload function.
 *
 * @param
 *            string Class name.
 */
function Pluf_autoload($class_name)
{
    try {
        Pluf::loadClass($class_name);
    } catch (Exception $e) {
        if (Pluf::f('debug')) {
            print $e->getMessage();
            die();
        }
        throw new \Pluf\Exception('Class not found:' . $class_name);
    }
}

/*
 * PHP 5.x support
 */
spl_autoload_register('Pluf_autoload');

/**
 * Exception to catch the PHP errors.
 *
 * @credits errd
 *
 * @see http://www.php.net/manual/en/function.set-error-handler.php
 */
class PlufErrorHandlerException extends Exception
{

    public function setLine($line)
    {
        $this->line = $line;
    }

    public function setFile($file)
    {
        $this->file = $file;
    }
}

/**
 * The function that is the real error handler.
 */
function PlufErrorHandler($code, $string, $file, $line)
{
    if (0 == error_reporting())
        return false;
    if (E_STRICT == $code && (0 === strpos($file, Pluf::f('pear_path', '/usr/share/php/')) or false !== strripos($file, 'pear'))) // if pear in the path, ignore
    {
        return;
    }
    $exception = new PlufErrorHandlerException($string, $code);
    $exception->setLine($line);
    $exception->setFile($file);
    throw $exception;
}

// Set the error handler only if not performing the unittests.
if (! defined('IN_UNIT_TESTS')) {
    set_error_handler('PlufErrorHandler', error_reporting());
}

/**
 * Shortcut needed all over the place.
 *
 * Note that in some cases, we need to escape strings not in UTF-8, so
 * this is not possible to safely use a call to htmlspecialchars. This
 * is why str_replace is used.
 *
 * @param
 *            string Raw string
 * @return string HTML escaped string
 */
function Pluf_esc($string)
{
    return str_replace(array(
        '&',
        '"',
        '<',
        '>'
    ), array(
        '&amp;',
        '&quot;',
        '&lt;',
        '&gt;'
    ), (string) $string);
}
