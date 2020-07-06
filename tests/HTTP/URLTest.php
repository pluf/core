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
use Pluf\Dispatcher;
use Pluf\HTTP\URL;
require_once 'Pluf.php';

/**
 *
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class URLTest extends TestCase
{

    protected function setUp()
    {
        Pluf::start(__DIR__ . '/../conf/config.php');
    }

    public function testGenerateSimple()
    {
        $murl = new URL('simple');
        $url = $murl->generate('/toto/titi/', array(
            'param1' => 'value%*one',
            'param2' => 'value&two'
        ), false);
        $this->assertRegExp('/^\?.*\_px\_action\=.*$/', $url);
        $this->assertRegExp('/^\?.*param1\=.*$/', $url);
        $this->assertRegExp('/^\?.*param2\=.*$/', $url);
    }

    public function testGenerateModRewrite()
    {
        $murl = new URL('mod_rewrite');
        $url = $murl->generate('/toto/titi/', array(
            'param1' => 'value%*one',
            'param2' => 'value&two'
        ), false);
        $this->assertEquals('/toto/titi/?param1=value%' . '25%2Aone&param2=value%26two', $url);
    }

    public function testGetActionModRewrite()
    {
        $_SERVER['PATH_INFO'] = '/toto/titi/';
        $murl = new URL('mod_rewrite');
        $this->assertEquals('/toto/titi/', $murl->getAction());
    }

    public function testGetActionModRewriteOrgin()
    {
        $_SERVER['ORIG_PATH_INFO'] = '/toto/titi/';
        $murl = new URL('mod_rewrite');
        $this->assertEquals('/toto/titi/', $murl->getAction());
    }

    public function testGetActionSimple()
    {
        $_GET['_px_action'] = '/toto/titi/';
        $murl = new URL('simple');
        $this->assertEquals('/toto/titi/', $murl->getAction());
    }

    public function testReverseSimpleUrl()
    {
        $url = URL::buildReverseUrl('#^/toto/$#');
        $this->assertEquals('/toto/', $url);
    }

    public function testReverseSimpleArgUrl()
    {
        $url = URL::buildReverseUrl('#^/toto/(\d+)/$#', array(
            23
        ));
        $this->assertEquals('/toto/23/', $url);
    }

    public function testReverseMultipleArgUrl()
    {
        $url = URL::buildReverseUrl('#^/toto/(\d+)/asd/(.*)/$#', array(
            23,
            'titi'
        ));
        $this->assertEquals('/toto/23/asd/titi/', $url);
    }

    public function testComplexReverseMultipleArgUrl()
    {
        $url = URL::buildReverseUrl('#^/toto/([A-Z]{2})/asd/(.*)/$#', array(
            'AB',
            'titi'
        ));
        $this->assertEquals('/toto/AB/asd/titi/', $url);
    }

    public function testReverseWithBackSlashes()
    {
        $url = URL::buildReverseUrl('#^/toto/(.*)\.txt$#', array(
            'AB'
        ));
        $this->assertEquals('/toto/AB.txt', $url);
    }

    // Reversce not supported anymore
    // public function testReverseMultipleArgUrlFailure()
    // {
    // $url_regex = '#^/toto/(\s+)/asd/(.*)/$#';
    // $params = array(
    // '23',
    // 'titi'
    // );
    // $url = URL::buildReverseUrl($url_regex, $params);
    // $this->fail('An exception as not been raised, regex:' . $url_regex . ' should not match params: ' . var_export($params, true));
    // }
    // public function testReverseUrlFromView()
    // {
    // $url = URL::reverse('Todo_Views::updateItem', array(
    // '32'
    // ));
    // $this->assertEquals('/item/32/update/', $url);
    // }
}

