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
namespace Pluf\PlufTest\HTTP\Response;

use PHPUnit\Framework\TestCase;
use Pluf\Bootstrap;
use Pluf\Dispatcher;
use Pluf\HTTP;

class URLTest extends TestCase
{

    protected function setUp()
    {
        Bootstrap::start(__DIR__ . '/../../conf/config.php');
        $d = new Dispatcher();
        $d->loadControllers(Bootstrap::f('app_views'));
    }

    public function testGenerateSimple()
    {
        $murl = new HTTP\URL('simple');
        $url = $murl->generate('/toto/titi/', array(
            'param1' => 'value%*one',
            'param2' => 'value&two'
        ));

        $data = array();
        parse_str(substr($url, 1), $data);

        $this->assertEquals($data['_px_action'], '/toto/titi/');
        $this->assertEquals($data['param1'], 'value%*one');
        $this->assertEquals($data['param2'], 'value&two');
    }

    public function testGenerateModRewrite()
    {
        $murl = new HTTP\URL('mod_rewrite');
        $url = $murl->generate('/toto/titi/', array(
            'param1' => 'value%*one',
            'param2' => 'value&two'
        ), false);
        $this->assertEquals('/toto/titi/?param1=value%' . '25%2Aone&param2=value%26two', $url);
    }

    public function testGetActionModRewrite()
    {
        $_SERVER['PATH_INFO'] = '/toto/titi/';
        $murl = new HTTP\URL('mod_rewrite');
        $this->assertEquals('/toto/titi/', $murl->getAction());

        $_SERVER['ORIG_PATH_INFO'] = '/toto/titi/';
        $murl = new HTTP\URL('mod_rewrite');
        $this->assertEquals('/toto/titi/', $murl->getAction());
    }

    public function testGetActionSimple()
    {
        $_GET['_px_action'] = '/toto/titi/';
        $murl = new HTTP\URL('simple');
        $this->assertEquals('/toto/titi/', $murl->getAction());
    }

    public function testReverseSimpleUrl()
    {
        $url = HTTP\URL::buildReverseUrl('#^/toto/$#');
        $this->assertEquals('/toto/', $url);
    }

    public function testReverseSimpleArgUrl()
    {
        $url = HTTP\URL::buildReverseUrl('#^/toto/(\d+)/$#', array(
            23
        ));
        $this->assertEquals('/toto/23/', $url);
    }

    public function testReverseMultipleArgUrl()
    {
        $url = HTTP\URL::buildReverseUrl('#^/toto/(\d+)/asd/(.*)/$#', array(
            23,
            'titi'
        ));
        $this->assertEquals('/toto/23/asd/titi/', $url);
    }

    public function testComplexReverseMultipleArgUrl()
    {
        $url = HTTP\URL::buildReverseUrl('#^/toto/([A-Z]{2})/asd/(.*)/$#', array(
            'AB',
            'titi'
        ));
        $this->assertEquals('/toto/AB/asd/titi/', $url);
    }

    public function testReverseWithBackSlashes()
    {
        $url = HTTP\URL::buildReverseUrl('#^/toto/(.*)\.txt$#', array(
            'AB'
        ));
        $this->assertEquals('/toto/AB.txt', $url);
    }

    public function testReverseMultipleArgUrlFailure()
    {
        $url_regex = '#^/toto/(\s+)/asd/(.*)/$#';
        $params = array(
            '23',
            'titi'
        );
        $url = HTTP\URL::buildReverseUrl($url_regex, $params);
        $this->assertNotNull($url);
        $this->assertEquals('/toto/23/asd/titi/', $url);
    }

    public function testReverseUrlFromView()
    {
        $url = HTTP\URL::reverse('Todo_Views::updateItem', array(
            '32'
        ));
        $this->assertEquals('/item/32/update/', $url);
    }
}

