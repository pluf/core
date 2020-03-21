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


class Pluf_DB_Introspect
{
    protected $int = null;
    protected $backend = '';
    
    public function __construct($db)
    {
        $this->int = Pluf::factory('Pluf_DB_Introspect_'.$db->engine, $db);
        $this->backend = $db->engine;
    }

    /**
     * Get the list of tables in the current database. The search
     * automatically limit the list to the visible ones.
     * 
     * @param object DB connection.
     * @return array List of tables.
     */
    function listTables()
    {
        if (!method_exists($this->int, 'listTables')) {
            throw new \Pluf\Exception_NotImplemented();
        }
        return $this->int->listTables();
    }
}


