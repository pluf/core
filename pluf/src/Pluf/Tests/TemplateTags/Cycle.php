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

class Pluf_Tests_Templatetags_Cycle extends Pluf_Test_TemplatetagsUnitTestCase
{
    protected $tag_class = 'Pluf_Template_Tag_Cycle';
    protected $tag_name = 'cycle';

    public function skip($message = '')
    {
        if (!empty($message)) {
            $this->skipIf(1, "%s\n      " . $message);
        }
    }

    public function testNoArguments()
    {
        $tpl = $this->getNewTemplate('{cycle}');
        try {
            $tpl->render();
            $this->fail();
        } catch (InvalidArgumentException $e) {
            $this->pass();
        }
    }

    public function testSimpleCaseInLoop()
    {
        $context = new Pluf_Template_Context(array('test' => range(0, 4)));
        $to_parse = '{foreach $test as $i}'.
                    '{cycle "a", "b"}{$i},'.
                    '{/foreach}';
        $expected = 'a0,b1,a2,b3,a4,';
        $tpl = $this->getNewTemplate($to_parse);
        $this->assertEqual($expected, $tpl->render($context));
    }

    public function testSingleStringArgument()
    {
        $context = new Pluf_Template_Context(array('test' => range(0, 4)));
        $to_parse = '{foreach $test as $i}'.
                    '{cycle "a"}{$i},'.
                    '{/foreach}';
        $expected = 'a0,a1,a2,a3,a4,';
        $tpl = $this->getNewTemplate($to_parse);
        $this->assertEqual($expected, $tpl->render($context));
    }

    public function testSingleArrayArgument()
    {
        $context = new Pluf_Template_Context(array('test' => range(0, 4)));
        $to_parse = '{foreach $test as $i}'.
                    '{cycle array("a", "b", "c")}{$i},'.
                    '{/foreach}';
        $expected = 'a0,b1,c2,a3,b4,';
        $tpl = $this->getNewTemplate($to_parse);
        $this->assertEqual($expected, $tpl->render($context));
    }

    public function testSingleContextVariableArgument()
    {
        $context = new Pluf_Template_Context(array('one' => 1));
        $to_parse = '{cycle $one}{cycle $one}';
        $expected = '11';
        $tpl = $this->getNewTemplate($to_parse);
        $this->assertEqual($expected, $tpl->render($context));
    }

    public function testMultipleCalls()
    {
        $to_parse = '{cycle "a", "b"}{cycle "a", "b"}';
        $expected = 'ab';
        $tpl = $this->getNewTemplate($to_parse);
        $this->assertEqual($expected, $tpl->render());
    }

    public function testAssignContextVariable()
    {
        $to_parse = '{cycle array("a", "b", "c"), "abc"}'.
                    '{cycle $abc}';
        $expected = 'ab';
        $tpl = $this->getNewTemplate($to_parse);
        $this->assertEqual($expected, $tpl->render());

        $to_parse = '{cycle array("a", "b", "c"), "abc"}'.
                    '{cycle $abc}'.
                    '{cycle $abc}';
        $expected = 'abc';
        $tpl = $this->getNewTemplate($to_parse);
        $this->assertEqual($expected, $tpl->render());

        $to_parse = '{cycle array("a", "b", "c"), "abc"}'.
                    '{cycle $abc}'.
                    '{cycle $abc}'.
                    '{cycle $abc}';
        $expected = 'abca';
        $tpl = $this->getNewTemplate($to_parse);
        $this->assertEqual($expected, $tpl->render());
    }

    public function testContextVariablesInArrayAsArgument()
    {
        $context = new Pluf_Template_Context(array('test' => range(0, 4),
                                                   'one' => 1,
                                                   'two' => 2));
        $to_parse = '{foreach $test as $i}'.
                    '{cycle array($one, $two)}'.
                    '{/foreach}';
        $expected = '12121';
        $tpl = $this->getNewTemplate($to_parse);
        $this->assertEqual($expected, $tpl->render($context));

        $context = new Pluf_Template_Context(array('one' => 1,
                                                   'two' => 2));
        $to_parse = '{cycle array($one, $two), "counter"}{cycle $counter}';
        $expected = '12';
        $tpl = $this->getNewTemplate($to_parse);
        $this->assertEqual($expected, $tpl->render($context));
    }

    public function testContextVariablesArgument()
    {
        $context = new Pluf_Template_Context(array('test' => range(0, 4),
                                                   'first' => 'a',
                                                   'second' => 'b'));
        $to_parse = '{foreach $test as $i}'.
                    '{cycle $first, $second}{$i},'.
                    '{/foreach}';
        $expected = 'a0,b1,a2,b3,a4,';
        $tpl = $this->getNewTemplate($to_parse);
        $this->assertEqual($expected, $tpl->render($context));
    }

    public function testFilterInCycle()
    {
        $this->skip('Pluf has no support for applying filters to a variable of array');
        return;

        $context = new Pluf_Template_Context(array('one' => 'A',
                                                   'two' => '2'));
        $to_parse = '{cycle array($one|lower, $two), "counter"}{cycle $counter}';
        $expected = 'a2';
        $tpl = $this->getNewTemplate($to_parse);
        $this->assertEqual($expected, $tpl->render($context));
    }
}
