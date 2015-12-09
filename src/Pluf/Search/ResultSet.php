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
 * ResultSet class to iterate over a search result.
 *
 */
class Pluf_Search_ResultSet implements Iterator
{
    protected $results = array();

    public function __construct($search_res)
    {
        $this->results = $search_res;
        reset($this->results);
    }

    /**
     * Get the current item.
     */
 	public function current()
    {
        $i = current($this->results);
        $doc = Pluf::factory($i['model_class'], $i['model_id']);
        $doc->_searchScore = $i['score'];
        return $doc;
    }

 	public function key()
    {
        return key($this->results);
    }

 	public function next()
    {
        next($this->results);
    }

 	public function rewind()
    {
        reset($this->results);
    }

 	public function valid()
    {
        // We know that the boolean false will not be stored as a
        // field, so we can test against false to check if valid or
        // not.
        return (false !== current($this->results));
    }

 	public function count()
    {
        return count($this->results);
    }
}