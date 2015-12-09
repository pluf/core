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

error_reporting(E_ALL | E_STRICT);
putenv('TZ=UTC');


require_once 'PHPUnit/Framework/TestCase.php';

$path_to_Pluf = dirname(__FILE__).'/../../src/';
set_include_path(get_include_path().PATH_SEPARATOR.$path_to_Pluf);

require_once 'Pluf.php';


class PlufTextWikiTest extends PHPUnit_Framework_TestCase {

    protected function setUp()
    {
        Pluf::start(dirname(__FILE__).'/../conf/pluf.config.php');
    }

    protected function tearDown()
    {
    }

    public function testSimpleRender()
    {
        $renderer = Pluf::factory('Pluf_Text_Wiki_Renderer');
        $this->assertEquals("\n".'<h4>Title</h4>'."\n", 
                            $renderer->render('!!Title')
                            );
    }

    public function testFullRender()
    {
        $renderer = Pluf::factory('Pluf_Text_Wiki_Renderer');
        $string = file_get_contents(dirname(__FILE__).'/wikisample.txt');
        $render = file_get_contents(dirname(__FILE__).'/wikisample.render.txt');
        $this->assertEquals($render, $renderer->render($string));
    }

    public function testRenderActionUrl()
    {
        $GLOBALS['_PX_config']['wiki_create_action'] = true;
        $GLOBALS['_PX_config']['app_base'] = '/testapp/';
        $GLOBALS['_PX_config']['url_format'] = 'simple';
        $string = '[Hello|/link/to]';
        $string2 = '[/link/to]';
        $string3 = '[http://example.com]';
        $string4 = '[Hello|/link/to/file.ext]';
        $renderer = new Pluf_Text_Wiki_Renderer();
        $this->assertEquals("<p>\n".'<a href="/testapp/?_px_action='.urlencode('/link/to').'">Hello</a>'."\n</p>",
                            $renderer->render($string));
        $this->assertEquals("<p>\n".'<a href="/testapp/?_px_action='.urlencode('/link/to').'">/link/to</a>'."\n</p>",
                            $renderer->render($string2));
        $this->assertEquals("<p>\n".'<a href="http://example.com">http://example.com</a>'."\n</p>",
                            $renderer->render($string3));
        $this->assertEquals("<p>\n".'<a href="/link/to/file.ext">Hello</a>'."\n</p>",
                            $renderer->render($string4));
        $GLOBALS['_PX_config']['wiki_create_action'] = false;
        $this->assertEquals("<p>\n".'<a href="/link/to">Hello</a>'."\n</p>",
                            $renderer->render($string));
        $this->assertEquals("<p>\n".'<a href="/link/to">/link/to</a>'."\n</p>",
                            $renderer->render($string2));
        $this->assertEquals("<p>\n".'<a href="http://example.com">http://example.com</a>'."\n</p>",
                            $renderer->render($string3));
    }
}

?>