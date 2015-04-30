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

/**
 * Simple input of type text.
 */
class Pluf_Form_Widget_PasswordInput extends Pluf_Form_Widget_Input
{
    public $input_type = 'password';
    public $render_value = true;

    public function __construct($attrs=array())
    {
        $this->render_value = (isset($attrs['render_value'])) ? $attrs['render_value'] : $this->render_value;
        unset($attrs['render_value']);
        parent::__construct($attrs);
    }

    public function render($name, $value, $extra_attrs=array())
    {
        if ($this->render_value === false) {
            $value = '';
        }
        return parent::render($name, $value, $extra_attrs);
    }
}