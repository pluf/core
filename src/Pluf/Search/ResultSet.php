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
/**
 * ResultSet class to iterate over a search result.
 */
class Pluf_Search_ResultSet implements Iterator
{

    protected $results = array();

    public function __construct ($search_res)
    {
        $this->results = $search_res;
        reset($this->results);
    }

    /**
     * Get the current item.
     */
    public function current ()
    {
        $i = current($this->results);
        $doc = Pluf::factory($i['model_class'], $i['model_id']);
        $doc->_searchScore = $i['score'];
        return $doc;
    }

    public function key ()
    {
        return key($this->results);
    }

    public function next ()
    {
        next($this->results);
    }

    public function rewind ()
    {
        reset($this->results);
    }

    public function valid ()
    {
        // We know that the boolean false will not be stored as a
        // field, so we can test against false to check if valid or
        // not.
        return (false !== current($this->results));
    }

    public function count ()
    {
        return count($this->results);
    }
}