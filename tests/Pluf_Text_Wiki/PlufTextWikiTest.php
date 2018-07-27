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
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\IncompleteTestError;
require_once 'Pluf.php';

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class PlufTextWikiTest extends TestCase
{

    /**
     * @beforeClass
     */
    public static function initTest ()
    {
        Pluf::start(__DIR__. '/../conf/config.php');
    }

    /**
     * @test
     */
    public function testSimpleRender ()
    {
        $renderer = Pluf::factory('Pluf_Text_Wiki_Renderer');
        $this->assertEquals("\n" . '<h4>Title</h4>' . "\n", 
                $renderer->render('!!Title'));
    }

    /**
     * @test
     */
    public function testFullRender ()
    {
        $renderer = Pluf::factory('Pluf_Text_Wiki_Renderer');
        $string = file_get_contents(dirname(__FILE__) . '/wikisample.txt');
        $render = file_get_contents(
                dirname(__FILE__) . '/wikisample.render.txt');
        $this->assertEquals($render, $renderer->render($string));
    }

//     /**
//      * @test
//      */
//     public function testRenderActionUrl ()
//     {
//         $GLOBALS['_PX_config']['Book_create_action'] = true;
//         $GLOBALS['_PX_config']['app_base'] = '/testapp/';
//         $GLOBALS['_PX_config']['url_format'] = 'simple';
//         $string = '[Hello|/link/to]';
//         $string2 = '[/link/to]';
//         $string3 = '[http://example.com]';
//         $string4 = '[Hello|/link/to/file.ext]';
//         $renderer = new Pluf_Text_Wiki_Renderer();
//         $this->assertEquals(
//                 "<p>\n" . '<a href="/testapp/?_px_action=' .
//                          urlencode('/link/to') . '">Hello</a>' . "\n</p>", 
//                         $renderer->render($string));
//         $this->assertEquals(
//                 "<p>\n" . '<a href="/testapp/?_px_action=' .
//                          urlencode('/link/to') . '">/link/to</a>' . "\n</p>", 
//                         $renderer->render($string2));
//         $this->assertEquals(
//                 "<p>\n" . '<a href="http://example.com">http://example.com</a>' .
//                          "\n</p>", $renderer->render($string3));
//         $this->assertEquals(
//                 "<p>\n" . '<a href="/link/to/file.ext">Hello</a>' . "\n</p>", 
//                 $renderer->render($string4));
//         $GLOBALS['_PX_config']['Book_create_action'] = false;
//         $this->assertEquals("<p>\n" . '<a href="/link/to">Hello</a>' . "\n</p>", 
//                 $renderer->render($string));
//         $this->assertEquals(
//                 "<p>\n" . '<a href="/link/to">/link/to</a>' . "\n</p>", 
//                 $renderer->render($string2));
//         $this->assertEquals(
//                 "<p>\n" . '<a href="http://example.com">http://example.com</a>' .
//                          "\n</p>", $renderer->render($string3));
//     }
}

?>