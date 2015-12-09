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

error_reporting(E_ALL | E_STRICT);

$path = dirname(__FILE__).'/../../src/';
set_include_path(get_include_path().PATH_SEPARATOR.$path);

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'PHPUnit/Framework/IncompleteTestError.php';

require_once 'Pluf.php';
require_once dirname(__FILE__).'/../Pluf_Form/TestFormModel.php';


class PlufPaginatorTest extends PHPUnit_Framework_TestCase {
    
    protected function setUp()
    {
        Pluf::start(dirname(__FILE__).'/../conf/pluf.config.php');
        $db = Pluf::db();
        $schema = Pluf::factory('Pluf_DB_Schema', $db);
        $m1 = new TestFormModel();
        $schema->model = $m1;
        $schema->dropTables();
        $schema->createTables();
        for ($i=1; $i<11; $i++) {
            $m = new TestFormModel();
            $m->title = 'My title '.$i;
            $m->description = 'My description '.$i;
            $m->create();
        }
    }

    protected function tearDown()
    {
        $db = Pluf::db();
        $schema = Pluf::factory('Pluf_DB_Schema', $db);
        $m1 = new TestFormModel();
        $schema->model = $m1;
        $schema->dropTables();
    }

    public function testSimplePaginate()
    {
        $model = new TestFormModel();
        $pag = Pluf::factory('Pluf_Paginator', $model);
        $pag->action = '/permission/'; 
        $pag->items_per_page = 5;
        $render = file_get_contents(dirname(__FILE__).'/rendersimplepaginate.html');
        $this->assertEquals($render, $pag->render());
        $this->assertEquals($pag->page_number, 2);
    }
}

?>