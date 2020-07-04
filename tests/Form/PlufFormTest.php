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
namespace Pluf\Test\Form;

use PHPUnit\Framework\TestCase;
use Pluf;
use Pluf_Migration;
use Pluf\NoteBook\Book;
require_once 'Pluf.php';

/**
 *
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class PlufFormTest extends TestCase
{

    /**
     *
     * @beforeClass
     */
    public static function initMutlitenantApplication()
    {
        // Load config
        $config = include __DIR__ . '/../conf/config.php';
        Pluf::start($config);
        $migration = new Pluf_Migration();
        $migration->install();
    }

    /**
     *
     * @afterClass
     */
    public static function removeApplication()
    {
        $migration = new Pluf_Migration();
        $migration->uninstall();
    }

    /**
     * 
     * @test
     */
    public function testFormRenderByField()
    {
//         $m1 = new Book();
        
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
    }

    // public function testFormValidate()
    // {
    // $m1 = new TestFormModel();
    // $form = new Pluf_Form_Update();
    // $form->defineFromModel($m1);
    // $errors = array();
    // $m1->description = 'My description';
    // $m1->title = 'What a title?';
    // $m1->id = 'qwe';
    // $new_data = $m1->getData();
    // $errors = $form->getValidationErrors($new_data);
    // $this->assertEquals(true, isset($errors['id']));
    // $this->assertEquals(1, count($errors));
    // }
}

