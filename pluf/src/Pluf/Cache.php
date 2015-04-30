<?php
/* -*- tab-width: 4; indent-tabs-mode: nil; c-basic-offset: 4 -*- */
/*
# ***** BEGIN LICENSE BLOCK *****
# This file is part of Plume Framework, a simple PHP Application Framework.
# Copyright (C) 2001-2007 Loic d'Anterroches and contributors.
#
# Plume Framework is free software; you can redistribute it and/or modify
# it under the terms of the GNU Lesser General Public License as published by
# the Free Software Foundation; either version 2.1 of the License, or
# (at your option) any later version.
#
# Plume Framework is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU Lesser General Public License for more details.
#
# You should have received a copy of the GNU Lesser General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
#
# ***** END LICENSE BLOCK ***** */

/**
 * Cache class.
 *
 * You should not use this class directly, but one of the subclasses
 * implementing a given engine. This is done automatically when using
 * the factory. It will use the engine defined by the 'cache_engine'
 * configuration variable.
 *
 * Default timeout in seconds is defined by the 'cache_timeout'
 * configuration variable.
 *
 * <pre>
 * $cache = new Pluf_Cache::factory();
 * if (null === ($foo=$cache->get('my-key'))) {
 *     $foo = run_complex_operation();
 *     $cache->set('my-key', $foo);
 * }
 * return $foo;
 * </pre>
 *
 * The value to be stored in the cache must be serializable.
 *
 * @see http://www.php.net/serialize
 */
class Pluf_Cache
{
    /**
     * Factory.
     *
     * @return Pluf_Cache_* Cache object
     */
    public static function factory()
    {
        if (false === ($engine=Pluf::f('cache_engine', false))) {
            throw new Pluf_Exception_SettingError('"cache_engine" setting not defined.');
        }
        if (!isset($GLOBALS['_PX_Pluf_Cache-'.$engine])) {
            $GLOBALS['_PX_Pluf_Cache-'.$engine] = new $engine();
        } 
        return $GLOBALS['_PX_Pluf_Cache-'.$engine];
    }

    /**
     * Set a value in the cache.
     *
     * @param string Key to store the information
     * @param mixed Value to store
     * @param int Timeout in seconds (null)
     * @return bool Success
     */
    public function set($key, $value, $timeout=null)
    {
        throw new Pluf_Exception_NotImplemented();
    }

    /**
     * Get value from the cache.
     *
     * @param string Key to get the information
     * @param mixed Default value to return if cache miss (null)
     * @param mixed Stored value or default
     */
    public function get($key, $default=null)
    {
        throw new Pluf_Exception_NotImplemented();
    }
}
