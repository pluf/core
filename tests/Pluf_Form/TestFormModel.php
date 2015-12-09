<?php
/* -*- tab-width: 4; indent-tabs-mode: nil; c-basic-offset: 4 -*- */
/*
# ***** BEGIN LICENSE BLOCK *****
# This file is part of Plume CMS, a website management application.
# Copyright (C) 2001-2007 Loic d'Anterroches and contributors.
#
# Plume CMS is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# Plume CMS is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
#
# ***** END LICENSE BLOCK ***** */

Pluf::loadClass('Pluf_Model');

class TestFormModel extends Pluf_Model
{
    function init()
    {
        $this->_a['table'] = 'testformmodels';
        $this->_a['model'] = 'TestFormModel';
        $this->_a['cols'] = array(
                             'id' =>
                             array(
                                   'type' => 'Pluf_DB_Field_Sequence',
                                   'blank' => true,//It is automatically added.
                                  ),
                            'title' => 
                            array(
                                  'type' => 'Pluf_DB_Field_Varchar',
                                  'blank' => false,
                                  'size' => 100,
                                  'verbose' => 'Title of the item',
                                  ),
                            'description' => 
                            array(
                                  'type' => 'Pluf_DB_Field_Text',
                                  'blank' => true,
                                  'help_text' => 'This is a small description',
                                  ),
                            );
        $this->_admin = array(
                              'list_display' => array('id', 
                                                      array('title', 'TestFormModel_ConvertTitle'), 
                                                      array('title', 'TestFormModel_ConvertTitle', 'My Title')),
                              'search_fields' => array('title', 'description'),
                              );
        parent::init();
    }



}


function TestFormModel_ConvertTitle($field, $item)
{
    return '"'.$item->$field.'"';
}

?>