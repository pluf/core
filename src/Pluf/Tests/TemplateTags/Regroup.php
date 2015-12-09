<?php
/* -*- tab-width: 4; indent-tabs-mode: nil; c-basic-offset: 4 -*- */
/*
# ***** BEGIN LICENSE BLOCK *****
# This file is part of Plume Framework, a simple PHP Application Framework.
# Copyright (C) 2001-2010 Loic d'Anterroches and contributors.
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

class Pluf_Tests_Model_People_Model extends Pluf_Model
{
    public $_model = __CLASS__;

    function init()
    {
        $this->_a['verbose'] = 'people';
        $this->_a['table'] = 'people';
        $this->_a['model'] = __CLASS__;
        $this->_a['cols'] = array(
            'id' => array(
                'type' => 'Pluf_DB_Field_Sequence',
                'blank' => true,
            ),
            'first_name' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => true,
                'size' => 50,
            ),
            'last_name' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => true,
                'size' => 50,
            ),
            'gender' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => true,
                'size' => 50,
                'default' => 'Unknown',
            ),
        );
    }
}

class Pluf_Tests_Templatetags_Regroup extends Pluf_Test_TemplatetagsUnitTestCase
{
    protected $tag_class = 'Pluf_Template_Tag_Regroup';
    protected $tag_name = 'regroup';

    public function testRegroupAnArray()
    {
        $context = new Pluf_Template_Context(array(
            'data' => array(array('foo' => 'c', 'bar' => 1),
                            array('foo' => 'd', 'bar' => 1),
                            array('foo' => 'a', 'bar' => 2),
                            array('foo' => 'b', 'bar' => 2),
                            array('foo' => 'x', 'bar' => 3))));
        $to_parse = '{regroup $data, "bar", "grouped"}'.
                    '{foreach $grouped as $group}'.
                    '{$group.grouper}:'.
                    '{foreach $group.list as $item}'.
                    '{$item.foo}'.
                    '{/foreach},'.
                    '{/foreach}';
        $expected = '1:cd,2:ab,3:x,';
        $tpl = $this->getNewTemplate($to_parse);
        $this->assertEqual($expected, $tpl->render($context));
    }

    public function testRegroupAnObject()
    {
        $obj1 = new stdClass();
        $obj1->foo = 'c';
        $obj1->bar = 1;
        $obj2 = new stdClass();
        $obj2->foo = 'd';
        $obj2->bar = 1;

        $obj3 = new stdClass();
        $obj3->foo = 'a';
        $obj3->bar = 2;
        $obj4 = new stdClass();
        $obj4->foo = 'b';
        $obj4->bar = 2;

        $obj5 = new stdClass();
        $obj5->foo = 'x';
        $obj5->bar = 3;

        $context = new Pluf_Template_Context(array(
            'data' => array($obj1, $obj2, $obj3, $obj4, $obj5)));
        $to_parse = '{regroup $data, "bar", "grouped"}'.
                    '{foreach $grouped as $group}'.
                    '{$group.grouper}:'.
                    '{foreach $group.list as $item}'.
                    '{$item.foo}'.
                    '{/foreach},'.
                    '{/foreach}';
        $expected = '1:cd,2:ab,3:x,';
        $tpl = $this->getNewTemplate($to_parse);
        $this->assertEqual($expected, $tpl->render($context));
    }

    public function testRegroupPlufModelInstance()
    {
        $db = Pluf::db();
        $schema = new Pluf_DB_Schema($db);
        $m = new Pluf_Tests_Model_People_Model();
        $schema->model = $m;
        $schema->createTables();

        $people = array(
            array('first_name' => 'George',
                  'last_name' => 'Bush',
                  'gender' => 'Male'),
            array('first_name' => 'Bill',
                  'last_name' => 'Clinton',
                  'gender' => 'Male'),
            array('first_name' => 'Margaret',
                  'last_name' => 'Thatcher',
                  'gender' => 'Female'),
            array('first_name' => 'Condoleezza',
                  'last_name' => 'Rice',
                  'gender' => 'Female'),
            array('first_name' => 'Pat',
                  'last_name' => 'Smith',
                  'gender' => 'Unknow'),
        );

        foreach ($people as $person) {
            $p = new Pluf_Tests_Model_People_Model();
            foreach ($person as $key => $value) {
                $p->$key = $value;
            }
            $p->create();
        }
        unset($p);

        $people_list = Pluf::factory('Pluf_Tests_Model_People_Model')->getList();
        $context = new Pluf_Template_Context(array(
            'people' => $people_list));
        $to_parse = <<<TPL
{regroup \$people, 'gender', 'gender_list'}
<ul>
{foreach \$gender_list as \$gender}
    <li>{\$gender.grouper}:
        <ul>
        {foreach \$gender.list as \$item}
            <li>{\$item.first_name} {\$item.last_name}</li>
        {/foreach}
        </ul>
    </li>
{/foreach}
</ul>
TPL;
        $expected = <<<HTML

<ul>

    <li>Male:
        <ul>
        
            <li>George Bush</li>
        
            <li>Bill Clinton</li>
        
        </ul>
    </li>

    <li>Female:
        <ul>
        
            <li>Margaret Thatcher</li>
        
            <li>Condoleezza Rice</li>
        
        </ul>
    </li>

    <li>Unknow:
        <ul>
        
            <li>Pat Smith</li>
        
        </ul>
    </li>

</ul>
HTML;
        $tpl = $this->getNewTemplate($to_parse);
        $this->assertEqual($expected, $tpl->render($context));
        $schema->dropTables();
    }

}
