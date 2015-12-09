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

class Pluf_Tests_Model_SlugField_Model extends Pluf_Model
{
    public $_model = __CLASS__;

    function init()
    {
        $this->_a['verbose'] = 'slug';
        $this->_a['table'] = 'slug';
        $this->_a['model'] = __CLASS__;
        $this->_a['cols'] = array(
            'id' => array(
                'type' => 'Pluf_DB_Field_Sequence',
                'blank' => true,
            ),
            'slug_default_length' => array(
                'type' => 'Pluf_DB_Field_Slug',
            ),
            'slug_custom_legnth' => array(
                'type' => 'Pluf_DB_Field_Slug',
                'widget_attrs' => array(
                    'maxlength' => 5,
                ),
            ),
        );
    }
}

class Pluf_Tests_Model_SlugField extends UnitTestCase
{

    function __construct()
    {
        parent::__construct('Test the slug field.');
    }

    function testSlugifyLowercase()
    {
        $slug = Pluf_DB_Field_Slug::slugify('Pluf');
        $this->assertEqual('pluf', $slug);
    }

    function testSlugifyReplaceWhiteSpaces()
    {
        // replaces a white space by the separator
        $slug = Pluf_DB_Field_Slug::slugify('ceondo pluf');
        $this->assertEqual('ceondo-pluf', $slug);

        // replaces several white spaces by a single the separator
        $slug = Pluf_DB_Field_Slug::slugify('ceondo    pluf');
        $this->assertEqual('ceondo-pluf', $slug);

        // removes separator at the bound of a string
        $slug = Pluf_DB_Field_Slug::slugify(' ceondo pluf');
        $this->assertEqual('ceondo-pluf', $slug);
        $slug = Pluf_DB_Field_Slug::slugify('ceondo pluf ');
        $this->assertEqual('ceondo-pluf', $slug);
    }

    function testSlugifyNonASCII()
    {
        // replaces non-ASCII characters by the separator
        $slug = Pluf_DB_Field_Slug::slugify('ceondo,pluf');
        $this->assertEqual('ceondo-pluf', $slug);
        $slug = Pluf_DB_Field_Slug::slugify('ceondo€pluf');
        $this->assertEqual('ceondo-pluf', $slug);

        // replaces accents by their equivalent non-accented
        $slug = Pluf_DB_Field_Slug::slugify('éiùàñ');
        $this->assertEqual('eiuan', $slug);
    }

    function testSlugifyWithCustomSeparator()
    {
        $backup = $GLOBALS['_PX_config'];
        $GLOBALS['_PX_config']['slug-separator'] = '_';

        $slug = Pluf_DB_Field_Slug::slugify('ceondo pluf');
        $this->assertEqual('ceondo_pluf', $slug);
        $slug = Pluf_DB_Field_Slug::slugify('ceondo   pluf');
        $this->assertEqual('ceondo_pluf', $slug);

        $GLOBALS['_PX_config'] = $backup;
    }

    function testCreate()
    {
        $db = Pluf::db();
        $schema = new Pluf_DB_Schema($db);
        $m = new Pluf_Tests_Model_SlugField_Model();
        $schema->model = $m;
        $schema->createTables();

        $m->slug_default_length = 'Pluf, supported by Céondo Ltd.';
        $m->create();
        $this->assertEqual(1, $m->id);

        $m = new Pluf_Tests_Model_SlugField_Model(1);
        $this->assertEqual('pluf-supported-by-ceondo-ltd', $m->slug_default_length);
        $schema->dropTables();
    }
}