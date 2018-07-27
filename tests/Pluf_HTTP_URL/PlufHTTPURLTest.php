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
class PlufHTTPURLTest extends TestCase {
    
    protected function setUp()
    {
        Pluf::start(__DIR__. '/../conf/config.php');
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

