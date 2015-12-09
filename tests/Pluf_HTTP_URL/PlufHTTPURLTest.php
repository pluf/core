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

$path = dirname(__FILE__).'/../../src/';
set_include_path(get_include_path().PATH_SEPARATOR.$path);

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'PHPUnit/Framework/IncompleteTestError.php';

require_once 'Pluf.php';

class PlufHTTPURLTest extends PHPUnit_Framework_TestCase {
    
    protected function setUp()
    {
        Pluf::start(dirname(__FILE__).'/../conf/pluf.config.php');
        Pluf::loadFunction('Pluf_HTTP_URL_buildReverseUrl');
        Pluf::loadFunction('Pluf_HTTP_URL_reverse');
        $d = new Pluf_Dispatcher();
        $d->loadControllers(Pluf::f('app_views'));
    }

    public function testGenerateSimple()
    {
        $murl = Pluf::factory('Pluf_HTTP_URL', 'simple');
        $url = $murl->generate('/toto/titi/', array(
                                                    'param1' => 'value%*one',
                                                    'param2' => 'value&two',
                                                    ), false);
        $this->assertEquals('?_px_action=%2Ftoto%2Ftiti%2F&param1=value%'
                            .'25%2Aone&param2=value%26two', $url);
    }

    public function testGenerateSimpleEncoded()
    {
        $murl = new Pluf_HTTP_URL('simple');
        $url = $murl->generate('/toto/titi/', array(
                                                    'param1' => 'value%*one',
                                                    'param2' => 'value&two',
                                                    ));
        $this->assertEquals('?_px_action=%2Ftoto%2Ftiti%2F&amp;param1=value%'
                            .'25%2Aone&amp;param2=value%26two', $url);
    }

    public function testGenerateModRewrite()
    {
        $murl = Pluf::factory('Pluf_HTTP_URL', 'mod_rewrite');
        $url = $murl->generate('/toto/titi/', array(
                                                    'param1' => 'value%*one',
                                                    'param2' => 'value&two',
                                                    ), false);
        $this->assertEquals('/toto/titi/?param1=value%'
                            .'25%2Aone&param2=value%26two', $url);
    }

    public function testGetActionModRewrite()
    {
        $_SERVER['QUERY_STRING'] = '/toto/titi/';
        $murl = Pluf::factory('Pluf_HTTP_URL', 'mod_rewrite');
        $this->assertEquals('/toto/titi/', $murl->getAction());

    }

    public function testGetActionSimple()
    {
        $_GET['_px_action'] = '/toto/titi/';
        $murl = Pluf::factory('Pluf_HTTP_URL', 'simple');
        $this->assertEquals('/toto/titi/', $murl->getAction());

    }

    public function testReverseSimpleUrl()
    {
        $url = Pluf_HTTP_URL_buildReverseUrl('#^/toto/$#');
        $this->assertEquals('/toto/', $url);
    }

    public function testReverseSimpleArgUrl()
    {
        $url = Pluf_HTTP_URL_buildReverseUrl('#^/toto/(\d+)/$#', array(23));
        $this->assertEquals('/toto/23/', $url);
    }

    public function testReverseMultipleArgUrl()
    {
        $url = Pluf_HTTP_URL_buildReverseUrl('#^/toto/(\d+)/asd/(.*)/$#', array(23, 'titi'));
        $this->assertEquals('/toto/23/asd/titi/', $url);
    }

    public function testComplexReverseMultipleArgUrl()
    {
        $url = Pluf_HTTP_URL_buildReverseUrl('#^/toto/([A-Z]{2})/asd/(.*)/$#', array('AB', 'titi'));
        $this->assertEquals('/toto/AB/asd/titi/', $url);
    }

    public function testReverseWithBackSlashes()
    {
        $url = Pluf_HTTP_URL_buildReverseUrl('#^/toto/(.*)\.txt$#', array('AB'));
        $this->assertEquals('/toto/AB.txt', $url);
    }

    public function testReverseMultipleArgUrlFailure()
    {
        $url_regex = '#^/toto/(\s+)/asd/(.*)/$#';
        $params =  array('23', 'titi');
        try {
            $url = Pluf_HTTP_URL_buildReverseUrl($url_regex, $params);
        } catch (Exception $e) {
            return;
        }
        $this->fail('An exception as not been raised, regex:'.$url_regex.' should not match params: '.var_export($params, true));
    }

    public function testReverseUrlFromView()
    {
        $url = Pluf_HTTP_URL_reverse('Todo_Views::updateItem', array('32'));
        $this->assertEquals('/item/32/update/', $url);
    }


}

