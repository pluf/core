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
namespace Pluf\PlufTest\Form;

use PHPUnit\Framework\TestCase;
use Pluf\Bootstrap;
use Pluf\Form\FormModelCreate;
use Pluf\DB;
use Pluf\Model;

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
class TestFormModel extends Model
{

    function init()
    {
        $this->_a['table'] = 'testformmodels';
        $this->_a['model'] = 'TestFormModel';
        $this->_a['cols'] = array(
            'id' => array(
                'type' => '\Pluf\DB\Field\Sequence',
                'blank' => true
            ), // It is automatically added.
            'title' => array(
                'type' => '\Pluf\DB\Field\Varchar',
                'blank' => false,
                'size' => 100,
                'verbose' => 'Title of the item'
            ),
            'description' => array(
                'type' => '\Pluf\DB\Field\Text',
                'blank' => true,
                'help_text' => 'This is a small description'
            )
        );
        $this->_admin = array(
            'list_display' => array(
                'id',
                array(
                    'title',
                    'TestFormModel_ConvertTitle'
                ),
                array(
                    'title',
                    'TestFormModel_ConvertTitle',
                    'My Title'
                )
            ),
            'search_fields' => array(
                'title',
                'description'
            )
        );
        parent::init();
    }
}

function TestFormModel_ConvertTitle($field, $item)
{
    return '"' . $item->$field . '"';
}

class FormTest extends TestCase
{

    protected function setUp()
    {
        Bootstrap::start(__DIR__ . '/../conf/config.php');
        $schema = new DB\Schema(Bootstrap::db());

        $m1 = new TestFormModel();
        $schema->model = $m1;
        $schema->dropTables();
        $schema->createTables();
    }

    protected function tearDown()
    {
        $db = Bootstrap::db();
        $schema = new \Pluf\DB\Schema($db);
        $m1 = new TestFormModel();
        $schema->model = $m1;
        $schema->dropTables();
    }

    // public function testFormRenderByField()
    // {
    // $form = new FormModelCreate(array(
    // 'model' => '\TestFromModel'
    // ));

    // // create test model
    // $m1 = new TestFormModel();
    // $errors = array();
    // $m1->description = 'My description';
    // $m1->title = 'What a title?';
    // $m1->create();
    // $new_data = $m1->getData();

    // $form_view = new Pluf_Form_Render($form, $new_data, $errors);
    // $this->assertEquals('<label class="px-form-required" for="title">Title of the item:</label>', $form_view->fields['title']->label);
    // $this->assertEquals('<label for="description">Description:</label>', $form_view->fields['description']->label);
    // }
    public function testFormValidate()
    {
        $m1 = new TestFormModel();
        $m1->description = 'My description';
        $m1->title = 'What a title?';
        $m1->id = 'qwe';
        $form = new FormModelCreate($m1->getData(), array(
            'model' => new TestFormModel()
        ));
        $this->assertTrue($form->isValid());

        $m1 = new TestFormModel();
        $m1->description = 'My description';
        $m1->id = 'qwe';
        $form = new FormModelCreate($m1->getData(), array(
            'model' => new TestFormModel()
        ));
        $this->assertFalse($form->isValid());
    }
}
