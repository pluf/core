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

// Set the include path to have Pluf in it.
// If you have Pluf in your include path, you do not need that
$path_to_Pluf = dirname(__FILE__).'/../../../src';
$path_to_Todo = dirname(__FILE__).'/../src';
set_include_path(get_include_path().PATH_SEPARATOR.$path_to_Pluf.PATH_SEPARATOR.$path_to_Todo);

// Load Pluf
require 'Pluf.php';
// Start the framework with the todo app configuration.
Pluf::start($path_to_Todo.'/Todo/conf/todo.php');
// As we are using a dispatcher, we need to load the corresponding
// view controllers. The controllers are just a mapping between the query
// string and corresponding classes and methods.
Pluf_Dispatcher::loadControllers(Pluf::f('todo_urls'));
// Dispatch the call. Note that the use of a dispatcher is not
// mandatory at all, you can create any number of .php file to dispatch
// manually. A dispatcher enables the use of only one index.php file.
Pluf_Dispatcher::dispatch(Pluf_HTTP_URL::getAction()); 

