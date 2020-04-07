<?php
/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. (http://dpq.co.ir)
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
use PHPUnit\Framework\TestCase;
require_once 'Pluf.php';

require_once dirname(__FILE__) . '/TestFormModel.php';

/**
 *
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class PlufFormTest extends TestCase
{

//     protected function setUp()
//     {
//         $this->markTestSkipped('Need to rewrite the form handling.');
//         Pluf::start(__DIR__ . '/../conf/config.php');
//         $db = Pluf::db();
//         $m1 = new TestFormModel();
//         $schema->model = $m1;
//         $schema->dropTables();
//         $schema->createTables();
//     }

//     protected function tearDown()
//     {
//         $db = Pluf::db();
//         $m1 = new TestFormModel();
//         $schema->model = $m1;
//         $schema->dropTables();
//     }

//     public function testFormRenderByField()
//     {
//         $m1 = new TestFormModel();
//         $form = new Pluf_Form_Create();
//         $form->defineFromModel($m1);
//         $errors = array();
//         $m1->description = 'My description';
//         $m1->title = 'What a title?';
//         $m1->create();
//         $new_data = $m1->getData();
//         $form_view = new Pluf_Form_Render($form, $new_data, $errors);
//         $this->assertEquals('<label class="px-form-required" for="title">Title of the item:</label>', $form_view->fields['title']->label);
//         $this->assertEquals('<label for="description">Description:</label>', $form_view->fields['description']->label);
//     }

//     public function testFormValidate()
//     {
//         $m1 = new TestFormModel();
//         $form = new Pluf_Form_Update();
//         $form->defineFromModel($m1);
//         $errors = array();
//         $m1->description = 'My description';
//         $m1->title = 'What a title?';
//         $m1->id = 'qwe';
//         $new_data = $m1->getData();
//         $errors = $form->getValidationErrors($new_data);
//         $this->assertEquals(true, isset($errors['id']));
//         $this->assertEquals(1, count($errors));
//     }
}

