<?php

/* -*- tab-width: 4; indent-tabs-mode: nil; c-basic-offset: 4 -*- */
/*
 * # ***** BEGIN LICENSE BLOCK *****
 * # This file is part of Plume Framework, a simple PHP Application Framework.
 * # Copyright (C) 2001-2007 Loic d'Anterroches and contributors.
 * #
 * # Plume Framework is free software; you can redistribute it and/or modify
 * # it under the terms of the GNU Lesser General Public License as published by
 * # the Free Software Foundation; either version 2.1 of the License, or
 * # (at your option) any later version.
 * #
 * # Plume Framework is distributed in the hope that it will be useful,
 * # but WITHOUT ANY WARRANTY; without even the implied warranty of
 * # MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * # GNU Lesser General Public License for more details.
 * #
 * # You should have received a copy of the GNU Lesser General Public License
 * # along with this program; if not, write to the Free Software
 * # Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 * #
 * # ***** END LICENSE BLOCK *****
 */

/**
 * Setup of the Plume Framework.
 *
 * It creates all the tables for the framework models.
 */
function Pluf_Migrations_Install_setup ($params = null)
{
    $models = array(
            'Pluf_DB_SchemaInfo',
            'Pluf_Session',
            Pluf::f('pluf_custom_user', 'Pluf_User'),
            Pluf::f('pluf_custom_group', 'Pluf_Group'),
            'Pluf_Message',
            'Pluf_Permission',
            'Pluf_RowPermission',
            'Pluf_Search_Word',
            'Pluf_Search_Occ',
            'Pluf_Search_Stats',
            'Pluf_Queue',
            'Pluf_Monitor'
    );
    $db = Pluf::db();
    $schema = new Pluf_DB_Schema($db);
    foreach ($models as $model) {
        $schema->model = new $model();
        $schema->createTables();
    }
}

function Pluf_Migrations_Install_teardown ($params = null)
{
    $models = array(
            'Pluf_Queue',
            'Pluf_Search_Stats',
            'Pluf_Search_Occ',
            'Pluf_Search_Word',
            'Pluf_RowPermission',
            'Pluf_Permission',
            'Pluf_Message',
            Pluf::f('pluf_custom_group', 'Pluf_Group'),
            Pluf::f('pluf_custom_user', 'Pluf_User'),
            'Pluf_Session',
            'Pluf_DB_SchemaInfo',
            'Pluf_Monitor'
    );
    $db = Pluf::db();
    $schema = new Pluf_DB_Schema($db);
    foreach ($models as $model) {
        $schema->model = new $model();
        $schema->dropTables();
    }
}