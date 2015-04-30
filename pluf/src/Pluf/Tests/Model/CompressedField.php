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

class Pluf_Tests_Model_CompressedField_Model extends Pluf_Model
{
    public $_model = __CLASS__; 

    function init()
    {
        $this->_a['verbose'] = 'compressed';
        $this->_a['table'] = 'compressed';
        $this->_a['model'] = __CLASS__;
        $this->_a['cols'] = array(
                             // It is mandatory to have an "id" column.
                            'id' =>
                            array(
                                  'type' => 'Pluf_DB_Field_Sequence',
                                  //It is automatically added.
                                  'blank' => true, 
                                  ),
                            'compressed' =>
                            array(
                                  'type' => 'Pluf_DB_Field_Compressed',
                                  ),
                            );
    }
}


class Pluf_Tests_Model_CompressedField extends UnitTestCase {
 
    function __construct() 
    {
        parent::__construct('Test the compressed field.');
    }

    function testCreate()
    {
        $db = Pluf::db();
        $schema = new Pluf_DB_Schema($db);
        $m = new Pluf_Tests_Model_CompressedField_Model();
        $schema->model = $m;
        $schema->createTables();
        $m->compressed = 'Youplaboum';
        $m->create();
        $this->assertEqual(1, $m->id);
        $m = new Pluf_Tests_Model_CompressedField_Model(1);
        $this->assertEqual('Youplaboum', $m->compressed);
        $schema->dropTables();
    }
}