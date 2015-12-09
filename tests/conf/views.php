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

$ctl = array();

$ctl[] = array('regex' => '#^/$#',
               'priority' => 4,
               'model' => 'Todo_Views',
               'method' => 'main');

$ctl[] = array('regex' => '#^/install/$#',
               'priority' => 4,
               'model' => 'Todo_Views',
               'method' => 'install');

$ctl[] = array('regex' => '#^/uninstall/$#',
               'priority' => 4,
               'model' => 'Todo_Views',
               'method' => 'uninstall');

$ctl[] = array('regex' => '#^/item/(\d+)/$#',
               'priority' => 4,
               'model' => 'Todo_Views',
               'method' => 'viewItem');

$ctl[] = array('regex' => '#^/list/(\d+)/item/add/$#',
               'priority' => 4,
               'model' => 'Todo_Views',
               'method' => 'addItem');

$ctl[] = array('regex' => '#^/item/(\d+)/update/$#',
               'priority' => 4,
               'model' => 'Todo_Views',
               'method' => 'updateItem');

$ctl[] = array('regex' => '#^/item/(\d+)/delete/$#',
               'priority' => 4,
               'model' => 'Todo_Views',
               'method' => 'deleteItem');

$ctl[] = array('regex' => '#^/list/$#',
               'priority' => 4,
               'model' => 'Todo_Views',
               'method' => 'listLists');

$ctl[] = array('regex' => '#^/list/(\d+)/$#',
               'priority' => 4,
               'model' => 'Todo_Views',
               'method' => 'viewList');

$ctl[] = array('regex' => '#^/list/(\d+)/update/$#',
               'priority' => 4,
               'model' => 'Todo_Views',
               'method' => 'updateList');

$ctl[] = array('regex' => '#^/list/(\d+)/delete/$#',
               'priority' => 4,
               'model' => 'Todo_Views',
               'method' => 'deleteList');

$ctl[] = array('regex' => '#^/list/add/$#',
               'priority' => 4,
               'model' => 'Todo_Views',
               'method' => 'addList');

return $ctl;
