<?php
/* -*- tab-width: 4; indent-tabs-mode: nil; c-basic-offset: 4 -*- */
/*
# ***** BEGIN LICENSE BLOCK *****
# This file is part of Plume Framework, a simple PHP Application Framework.
# Copyright (C) 2001-2006 Loic d'Anterroches and contributors.
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

function Todo_Migrations_Install_setup($params='')
{
    // First we create instances of the two data models in the application.
    $list = new Todo_List();
    $item = new Todo_Item();
    // Get a database connection
    // As the parameters have been given in a standard way in the 
    // configuration file we are directly accessing the default db here.
    $db = Pluf::db();
    // Then we create a schema builder to generate the needed tables.
    // The first parameter given to the schema builder is the database
    // connection. 
    $schema = new Pluf_DB_Schema($db);
    // Then for each model, we will create the needed tables.
    // To be safe you should ensure that the createTables() call returns
    // true and not an error.
    $schema->model = $list;
    $schema->createTables();
    $schema->model = $item;
    $schema->createTables();
}

function Todo_Migrations_Install_teardown($params='')
{
    // The uninstallation is the reverse of the installation.
    // We create the data models the same way, but instead of calling
    // createTables() we call dropTables()
    // You can see that without all the comments, you do not have a
    // lot of lines of code.
    $list = new Todo_List();
    $item = new Todo_Item();
    $db = Pluf::db();
    $schema = Pluf::factory('Pluf_DB_Schema', $db);
    $schema->model = $list;
    $schema->dropTables();
    $schema->model = $item;
    $schema->dropTables();
}
