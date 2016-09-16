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

Pluf::loadFunction('Pluf_HTTP_URL_urlForView');

/**
 * Unit testing of this small application.
 *
 * The first series of tests is just to test the creation/deletion of
 * lists and items.
 *
 * The second series of tests is to test the views by doing queries
 * against them.
 *
 * That way you can see the way one can test the "backend" and the
 * "frontend".
 */
class Todo_Tests_TestTodo extends UnitTestCase 
{
    public $client = null;
    public $lists = array();

    public function __construct() 
    {
        parent::__construct('Test of the Todo application.');
    }

    /**
     * Create a client.
     */
    public function setUp()
    {
        $this->client = new Pluf_Test_Client(Pluf::f('todo_urls'));
    }

    /**
     * Delete the client and lists.
     *
     * Delete all the list which may be left. When the lists are
     * deleted, the items in those list are automatically deleted too.
     */
    public function tearDown()
    {
        $this->client = null;
        foreach ($this->lists as $list) {
            $list->delete();
        }
    }

    public function testCreateList()
    {
        $list = new Todo_List();
        $list->name = 'Test list';
        $this->assertEqual(true, $list->create());
        $this->lists[] = $list; // to have it deleted in tearDown
        $id = $list->id;
        $nlist = new Todo_List($id);
        $this->assertEqual($nlist->id, $id);
    }

    public function testCreateItem()
    {
        $list = new Todo_List();
        $list->name = 'Test list';
        $this->assertEqual(true, $list->create());
        $this->lists[] = $list; // to have it deleted in tearDown
        $item = new Todo_Item();
        $item->list = $list;
        $item->item = 'Create unit tests';
        $this->assertEqual(true, $item->create());
        $nlist = $item->get_list();
        $this->assertEqual($nlist->id, $list->id);
        $items = $list->get_todo_item_list();
        $this->assertEqual(1, $items->count());
        $item2 = new Todo_Item();
        $item2->list = $list;
        $item2->item = 'Create more unit tests';
        $item2->create();
        // first list has 2 items.
        $this->assertEqual(2, $list->get_todo_item_list()->count());
        $list2 = new Todo_List();
        $list2->name = 'Test list 2';
        $this->assertEqual(true, $list2->create());
        $this->lists[] = $list2; // to have it deleted in tearDown
        $this->assertEqual(0, $list2->get_todo_item_list()->count());
        // Move the item in the second list.
        $item2->list = $list2;
        $item2->update();
        // One item in each list.
        $this->assertEqual(1, $list2->get_todo_item_list()->count());
        $this->assertEqual(1, $list->get_todo_item_list()->count());
    }

}