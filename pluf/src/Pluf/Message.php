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

class Pluf_Message extends Pluf_Model
{
    public $_model = 'Pluf_Message';

    function init()
    {
        $this->_a['table'] = 'messages';
        $this->_a['model'] = 'Pluf_Message';
        $this->_a['cols'] = array(
                             // It is mandatory to have an "id" column.
                            'id' =>
                            array(
                                  'type' => 'Pluf_DB_Field_Sequence',
                                  //It is automatically added.
                                  'blank' => true, 
                                  ),
			        		'version' =>
			        		array(
			        			'type' => 'Pluf_DB_Field_Integer',
			        			'blank' => true,
			        			),
                            'user' =>
                            array(
                                  'type' => 'Pluf_DB_Field_Foreignkey',
                                  'model' => Pluf::f('pluf_custom_user','Pluf_User'),
                                  'blank' => false,
                                  'verbose' => __('user'),
                                  ),
                            'message' => 
                            array(
                                  'type' => 'Pluf_DB_Field_Text',
                                  'blank' => false,
                                  ),
                            );
    }

    function __toString()
    {
        return $this->message;
    }
}
