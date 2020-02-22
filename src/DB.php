<?php
/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. (http://dpq.co.ir)
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
 * مدیریت پایگاه داده سیستم
 *
 * این کلاس مدیریت لازم برای اتصال به پایگاه داده را فراهم می‌کند. بر اساس
 * تنظیم‌ها این کلاس به پایگاه‌های داده زیر متصل می‌شود:
 *
 * - MySQL
 * - SQLite
 */
class DB
{

    /**
     * Get a database connection.
     */
    static function get($engine, $server, $database, $login, $password, $prefix, $debug = false, $version = '')
    {
        $engine = '\\Pluf\\DB\\' . $engine;
        $con = new $engine($login, $password, $server, $database, $prefix, $debug, $version);
        return $con;
    }

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
    public static function getConnection($extra = null)
    {
        if (isset($GLOBALS['_PX_db']) && (is_resource($GLOBALS['_PX_db']->con_id) or is_object($GLOBALS['_PX_db']->con_id))) {
            return $GLOBALS['_PX_db'];
        }
        $GLOBALS['_PX_db'] = self::get(Bootstrap::f('db_engine'), Bootstrap::f('db_server'), Bootstrap::f('db_database'), Bootstrap::f('db_login'), Bootstrap::f('db_password'), Bootstrap::f('db_table_prefix'), Bootstrap::f('debug'), Bootstrap::f('db_version'));
        return $GLOBALS['_PX_db'];
    }

    /**
     * Returns an array of default typecast and quoting for the database ORM.
     *
     * Foreach field type you need to provide an array with 2 functions,
     * the from_db, the to_db.
     *
     * $value = from_db($value);
     * $escaped_value = to_db($value, $dbobject);
     *
     * $escaped_value is ready to be put in the SQL, that is if this is a
     * string, the value is quoted and escaped for example with SQLite:
     * 'my string'' is escaped' or with MySQL 'my string\' is escaped' the
     * starting ' and ending ' are included!
     *
     * @return array Default typecast.
     */
    public static function defaultTypecast()
    {
        // Default system type
        $defTypes = array(
            '\Pluf\DB\Field\Boolean' => array(
                '\Pluf\DB::booleanFromDb',
                '\Pluf\DB::booleanToDb'
            ),
            '\Pluf\DB\Field\Date' => array(
                '\Pluf\DB::identityFromDb',
                '\Pluf\DB::identityToDb'
            ),
            '\Pluf\DB\Field\Datetime' => array(
                '\Pluf\DB::identityFromDb',
                '\Pluf\DB::identityToDb'
            ),
            '\Pluf\DB\Field\Email' => array(
                '\Pluf\DB::identityFromDb',
                '\Pluf\DB::identityToDb'
            ),
            '\Pluf\DB\Field\File' => array(
                '\Pluf\DB::identityFromDb',
                '\Pluf\DB::identityToDb'
            ),
            '\Pluf\DB\Field\FloatPoint' => array(
                '\Pluf\DB::floatFromDb',
                '\Pluf\DB::floatToDb'
            ),
            '\Pluf\DB\Field\Foreignkey' => array(
                '\Pluf\DB::integerFromDb',
                '\Pluf\DB::integerToDb'
            ),
            '\Pluf\DB\Field\Integer' => array(
                '\Pluf\DB::integerFromDb',
                '\Pluf\DB::integerToDb'
            ),
            '\Pluf\DB\Field\Password' => array(
                '\Pluf\DB::identityFromDb',
                '\Pluf\DB::passwordToDb'
            ),
            '\Pluf\DB\Field\Sequence' => array(
                '\Pluf\DB::integerFromDb',
                '\Pluf\DB::integerToDb'
            ),
            '\Pluf\DB\Field\Slug' => array(
                '\Pluf\DB::identityFromDb',
                '\Pluf\DB::slugToDb'
            ),
            '\Pluf\DB\Field\Text' => array(
                '\Pluf\DB::identityFromDb',
                '\Pluf\DB::identityToDb'
            ),
            '\Pluf\DB\Field\Varchar' => array(
                '\Pluf\DB::identityFromDb',
                '\Pluf\DB::identityToDb'
            ),
            '\Pluf\DB\Field\Serialized' => array(
                '\Pluf\DB::serializedFromDb',
                '\Pluf\DB::serializedToDb'
            ),
            '\Pluf\DB\Field\Compressed' => array(
                '\Pluf\DB::compressedFromDb',
                '\Pluf\DB::compressedToDb'
            ),
            '\Pluf\DB\Field\Geometry' => array(
                '\Pluf\DB::geometryFromDb',
                '\Pluf\DB::geometryToDb'
            )
        );

        // Load extra types
        $extra = Bootstrap::f('orm.typecasts', array());
        return array_merge($defTypes, $extra);
    }

    /**
     * Identity function.
     *
     * @params
     *            mixed Value
     * @return mixed Value
     */
    public static function identityFromDb($val)
    {
        return $val;
    }

    /**
     * Identity function.
     *
     * @param
     *            mixed Value.
     * @param
     *            object Database handler.
     * @return string Ready to use for SQL.
     */
    public static function identityToDb($val, $db)
    {
        if (null === $val) {
            return 'NULL';
        }
        return $db->esc($val);
    }

    public static function serializedFromDb($val)
    {
        if ($val) {
            return unserialize($val);
        }
        return $val;
    }

    public static function serializedToDb($val, $db)
    {
        if (null === $val) {
            return 'NULL';
        }
        return $db->esc(serialize($val));
    }

    public static function compressedFromDb($val)
    {
        return ($val) ? gzinflate($val) : $val;
    }

    public static function compressedToDb($val, $db)
    {
        return (null === $val) ? 'NULL' : $db->esc(gzdeflate($val, 9));
    }

    public static function booleanFromDb($val)
    {
        if ($val) {
            return true;
        }
        return false;
    }

    public static function booleanToDb($val, $db)
    {
        if (null === $val) {
            return 'NULL';
        }
        if ($val) {
            return $db->esc('1');
        }
        return $db->esc('0');
    }

    public static function integerFromDb($val)
    {
        return (null === $val) ? null : (int) $val;
    }

    public static function integerToDb($val, $db)
    {
        return (null === $val) ? 'NULL' : (string) (int) $val;
    }

    public static function floatFromDb($val)
    {
        return (null === $val) ? null : (float) $val;
    }

    public static function floatToDb($val, $db)
    {
        return (null === $val) ? 'NULL' : (string) (float) $val;
    }

    public static function passwordToDb($val, $db)
    {
        $exp = explode(':', $val);
        if (in_array($exp[0], array(
            'sha1',
            'md5',
            'crc32'
        ))) {
            return $db->esc($val);
        }
        // We need to hash the value.
        $salt = Utils::getRandomString(5);
        return $db->esc('sha1:' . $salt . ':' . sha1($salt . $val));
    }

    public static function slugToDB($val, $db)
    {
        return $db->esc(DB\Field\Slug::slugify($val));
    }

    /**
     *
     * @param Object $val
     * @return string
     */
    public static function geometryFromDb($val)
    {
        // TODO: maso, 2018: check if we need to use geoPHP::load to load data
        // SEE: https://github.com/phayes/geoPHP
        /*
         * maso, 1395: convert $val (from BLOB) to WKT
         *
         * 1- SRID
         * 2- WKB
         *
         * See:
         * https://dev.mysql.com/doc/refman/5.7/en/gis-data-formats.html#gis-internal-format
         */
        if ($val == null)
            return null;
        $data = unpack("lsrid/H*wkb", $val);
        $geometry = geoPHP::load($data['wkb'], 'wkb', TRUE);
        $wkt_writer = new WKT();
        $wkt = $wkt_writer->write($geometry);
        return $wkt;
    }

    /**
     * Convert text to geometry
     */
    public static function geometryToDb($val, $db)
    {
        // TODO: maso, 2018: check if we need to use geoPHP::load to load data
        // SEE: https://github.com/phayes/geoPHP
        // TODO: hadi 1397-06-16: Here $val should be encoded
        // if($db->engine === 'SQLite'){
        // return (null === $val || empty($val)) ? 'NULL' : "'" . $val . "'";
        // }
        return (null === $val || empty($val)) ? 'NULL' : (string) "GeometryFromText('" . $val . "')";
    }
}



