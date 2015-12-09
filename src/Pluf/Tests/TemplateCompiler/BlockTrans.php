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

class Pluf_Tests_TemplateCompiler_BlockTrans extends UnitTestCase {
 
    function __construct() 
    {
        parent::__construct('Test the compilation of a template.');
    }

    function testCompileSimpleBlock()
    {
        $block = '<li>{blocktrans}This email <em>{$email}</em> is already registered. If you forgot your password, you can recover it easily.{/blocktrans}</li>';
        $compiler = new Pluf_Template_Compiler('dummy', array(), false);
        $compiler->templateContent = $block;
        $this->assertEqual('<li><?php ob_start(); ?>This email <em>%%email%%</em> is already registered. If you forgot your password, you can recover it easily.<?php $_b_t_s=ob_get_contents(); ob_end_clean(); echo(Pluf_Translation::sprintf(__($_b_t_s), array(\'email\' => Pluf_Template_safeEcho($t->_vars->email, false)))); ?></li>',
                           $compiler->getCompiledTemplate());
    }
}