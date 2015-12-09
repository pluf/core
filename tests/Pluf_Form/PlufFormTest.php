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
require_once dirname(__FILE__).'/TestFormModel.php';


class PlufFormTest extends PHPUnit_Framework_TestCase {
    
    protected function setUp()
    {
        $this->markTestSkipped('Need to rewrite the form handling.');
        Pluf::start(dirname(__FILE__).'/../conf/pluf.config.php');
        $db = Pluf::db();
        $schema = Pluf::factory('Pluf_DB_Schema', $db);
        $m1 = new TestFormModel();
        $schema->model = $m1;
        $schema->dropTables();
        $schema->createTables();
    }

    protected function tearDown()
    {
        $db = Pluf::db();
        $schema = Pluf::factory('Pluf_DB_Schema', $db);
        $m1 = new TestFormModel();
        $schema->model = $m1;
        $schema->dropTables();
    }

    public function testFormRenderByField()
    {
        $m1 = new TestFormModel();
        $form = new Pluf_Form_Create();
        $form->defineFromModel($m1);
        $errors = array();
        $m1->description = 'My description';
        $m1->title = 'What a title?';
        $m1->create();
        $new_data = $m1->getData();
        $form_view = new Pluf_Form_Render($form, $new_data, $errors);
        $this->assertEquals('<label class="px-form-required" for="title">Title of the item:</label>',
                            $form_view->fields['title']->label);
        $this->assertEquals('<label for="description">Description:</label>',
                            $form_view->fields['description']->label);
    }

    public function testFormValidate()
    {
        $m1 = new TestFormModel();
        $form = new Pluf_Form_Update();
        $form->defineFromModel($m1);
        $errors = array();
        $m1->description = 'My description';
        $m1->title = 'What a title?';
        $m1->id = 'qwe';
        $new_data = $m1->getData();
        $errors = $form->getValidationErrors($new_data);
        $this->assertEquals(true, isset($errors['id']));
        $this->assertEquals(1, count($errors));
    }


}

?>