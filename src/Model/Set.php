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
 * Allow to iterate over an array of standard classes with
 * 'model_class' and 'model_id' properties set.
 *
 * Optional properties from the classses can also be extracted and
 * added as properties to the model.
 *
 * Suppose you have a $data array with:
 * array(
 *       (object) array('model_class' => 'MyApp_Item',
 *                      'model_id' => 32,
 *                      'foo' => 'bingo plus'),
 *       (object) array('model_class' => 'MyApp_Bong',
 *                      'model_id' => 12,
 *                      'foo' => 'youpla'),
 *       (object) array('model_class' => 'MyApp_Item',
 *                      'model_id' => 14,
 *                      'foo' => 'bingo'),
 * );
 *
 * You can do:
 * $set = new Model_Set($data, array('foo' => '_Foo'));
 * foreach ($set as $res) {
 *    echo $res; // Will be the loaded model based on the class and id.
 *    echo $res->_Foo; // will contain the value foo of the $data array.
 * }
 *
 */
class Model_Set implements Iterator, ArrayAccess
{
    protected $data = array();
    protected $keys = array();

    public function __construct($data, $keys=array())
    {
        $this->data = $data;
        $this->keys = $keys;
        reset($this->data);
    }

    /**
     * Get the current item.
     */
 	public function current()
    {
        $i = current($this->data);
        $item = Pluf::factory($i->model_class, $i->model_id);
        foreach ($this->keys as $key => $val) {
            $item->$val = $i->$key;
        }
        return $item;
    }

 	public function key()
    {
        return key($this->data);
    }

 	public function next()
    {
        next($this->data);
    }

 	public function rewind()
    {
        reset($this->data);
    }

 	public function valid()
    {
        // We know that the boolean false will not be stored as a
        // field, so we can test against false to check if valid or
        // not.
        return (false !== current($this->data));
    }

 	public function count()
    {
        return count($this->data);
    }

    public function offsetUnset($index) 
    {
        unset($this->data[$index]);
    }
 
    public function offsetSet($index, $value) 
    {
        if (!is_object($value) or
            is_subclass_of($value, 'Model')) {
            throw new Exception('Must be a subclass of Model: '.$value);
        }
        $res = array('model_class' => $value->_model,
                     'model_id' => $value->id);
        foreach ($this->keys as $key => $name) {
            $res[$key] = (isset($value->$name)) ? $value->$name : null;
        }
        $this->data[$index] = (object) $res;
    }

    public function offsetGet($index) 
    {
        if (!isset($this->data[$index])) {
            throw new Exception('Undefined index: '.$index);
        }
        $i = $this->data[$index];
        $item = Pluf::factory($i->model_class, $i->model_id);
        foreach ($this->keys as $key => $val) {
            $item->$val = $i->$key;
        }
        return $item;
    }

    public function offsetExists($index) 
    {
        return (isset($this->data[$index]));
    }
}