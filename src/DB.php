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
class Pluf_DB
{

    /**
     * Get a database connection.
     */
    static function get ($engine, $server, $database, $login, $password, $prefix, 
            $debug = false, $version = '')
    {
        $engine = 'Pluf_DB_' . $engine;
        $con = new $engine($login, $password, $server, $database, $prefix, 
                $debug, $version);
        return $con;
    }
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
function Pluf_DB_getConnection ($extra = null)
{
    if (isset($GLOBALS['_PX_db']) && (is_resource($GLOBALS['_PX_db']->con_id) or
             is_object($GLOBALS['_PX_db']->con_id))) {
        return $GLOBALS['_PX_db'];
    }
    $GLOBALS['_PX_db'] = Pluf_DB::get(Pluf::f('db_engine'), 
            Pluf::f('db_server'), Pluf::f('db_database'), Pluf::f('db_login'), 
            Pluf::f('db_password'), Pluf::f('db_table_prefix'), Pluf::f('debug'), 
            Pluf::f('db_version'));
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
function Pluf_DB_defaultTypecast ()
{
    // Default system type
    $defTypes = array(
            'Pluf_DB_Field_Boolean' => array(
                    'Pluf_DB_BooleanFromDb',
                    'Pluf_DB_BooleanToDb'
            ),
            'Pluf_DB_Field_Date' => array(
                    'Pluf_DB_IdentityFromDb',
                    'Pluf_DB_IdentityToDb'
            ),
            'Pluf_DB_Field_Datetime' => array(
                    'Pluf_DB_IdentityFromDb',
                    'Pluf_DB_IdentityToDb'
            ),
            'Pluf_DB_Field_Email' => array(
                    'Pluf_DB_IdentityFromDb',
                    'Pluf_DB_IdentityToDb'
            ),
            'Pluf_DB_Field_File' => array(
                    'Pluf_DB_IdentityFromDb',
                    'Pluf_DB_IdentityToDb'
            ),
            'Pluf_DB_Field_Float' => array(
                    'Pluf_DB_FloatFromDb',
                    'Pluf_DB_FloatToDb'
            ),
            'Pluf_DB_Field_Foreignkey' => array(
                    'Pluf_DB_IntegerFromDb',
                    'Pluf_DB_IntegerToDb'
            ),
            'Pluf_DB_Field_Integer' => array(
                    'Pluf_DB_IntegerFromDb',
                    'Pluf_DB_IntegerToDb'
            ),
            'Pluf_DB_Field_Password' => array(
                    'Pluf_DB_IdentityFromDb',
                    'Pluf_DB_PasswordToDb'
            ),
            'Pluf_DB_Field_Sequence' => array(
                    'Pluf_DB_IntegerFromDb',
                    'Pluf_DB_IntegerToDb'
            ),
            'Pluf_DB_Field_Slug' => array(
                    'Pluf_DB_IdentityFromDb',
                    'Pluf_DB_SlugToDb'
            ),
            'Pluf_DB_Field_Text' => array(
                    'Pluf_DB_IdentityFromDb',
                    'Pluf_DB_IdentityToDb'
            ),
            'Pluf_DB_Field_Varchar' => array(
                    'Pluf_DB_IdentityFromDb',
                    'Pluf_DB_IdentityToDb'
            ),
            'Pluf_DB_Field_Serialized' => array(
                    'Pluf_DB_SerializedFromDb',
                    'Pluf_DB_SerializedToDb'
            ),
            'Pluf_DB_Field_Compressed' => array(
                    'Pluf_DB_CompressedFromDb',
                    'Pluf_DB_CompressedToDb'
            ),
            'Pluf_DB_Field_Geometry' => array(
                    'Pluf_DB_GeometryFromDb',
                    'Pluf_DB_GeometryToDb'
            ),
    );
    
    // Load extra types
    $extra = Pluf::f('orm.typecasts', array());
    return array_merge($defTypes, $extra);
}

/**
 * Identity function.
 *
 * @params
 *            mixed Value
 * @return mixed Value
 */
function Pluf_DB_IdentityFromDb ($val)
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
function Pluf_DB_IdentityToDb ($val, $db)
{
    if (null === $val) {
        return 'NULL';
    }
    return $db->esc($val);
}

function Pluf_DB_SerializedFromDb ($val)
{
    if ($val) {
        return unserialize($val);
    }
    return $val;
}

function Pluf_DB_SerializedToDb ($val, $db)
{
    if (null === $val) {
        return 'NULL';
    }
    return $db->esc(serialize($val));
}

function Pluf_DB_CompressedFromDb ($val)
{
    return ($val) ? gzinflate($val) : $val;
}

function Pluf_DB_CompressedToDb ($val, $db)
{
    return (null === $val) ? 'NULL' : $db->esc(gzdeflate($val, 9));
}

function Pluf_DB_BooleanFromDb ($val)
{
    if ($val) {
        return true;
    }
    return false;
}

function Pluf_DB_BooleanToDb ($val, $db)
{
    if (null === $val) {
        return 'NULL';
    }
    if ($val) {
        return $db->esc('1');
    }
    return $db->esc('0');
}

function Pluf_DB_IntegerFromDb ($val)
{
    return (null === $val) ? null : (int) $val;
}

function Pluf_DB_IntegerToDb ($val, $db)
{
    return (null === $val) ? 'NULL' : (string) (int) $val;
}

function Pluf_DB_FloatFromDb ($val)
{
    return (null === $val) ? null : (float) $val;
}

function Pluf_DB_FloatToDb ($val, $db)
{
    return (null === $val) ? 'NULL' : (string) (float) $val;
}

function Pluf_DB_PasswordToDb ($val, $db)
{
    $exp = explode(':', $val);
    if (in_array($exp[0], 
            array(
                    'sha1',
                    'md5',
                    'crc32'
            ))) {
        return $db->esc($val);
    }
    // We need to hash the value.
    $salt = Pluf_Utils::getRandomString(5);
    return $db->esc('sha1:' . $salt . ':' . sha1($salt . $val));
}

function Pluf_DB_SlugToDB ($val, $db)
{
    return $db->esc(Pluf_DB_Field_Slug::slugify($val));
}

/**
 *
 * @param Object $val
 * @return string
 */
function Pluf_DB_GeometryFromDb ($val)
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
    if($val == null)
        return null;
        $data = unpack("lsrid/H*wkb", $val);
        $geometry = geoPHP::load($data['wkb'], 'wkb', TRUE);
        $wkt_writer = new WKT();
        $wkt = $wkt_writer->write($geometry);
        return $wkt;
}

/**
 * Convert text to geometry
 *
 * @param unknown $val
 * @param unknown $db
 * @return string
 */
function Pluf_DB_GeometryToDb ($val, $db)
{
    // TODO: maso, 2018: check if we need to use geoPHP::load to load data
    // SEE: https://github.com/phayes/geoPHP
    // TODO: hadi 1397-06-16: Here $val should be encoded
//     if($db->engine === 'SQLite'){
//         return (null === $val || empty($val)) ? 'NULL' : "'" . $val . "'";
//     }
    return (null === $val || empty($val)) ? 'NULL' : (string) "GeometryFromText('" . $val . "')";
}



