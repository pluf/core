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

/**
 *
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class Pluf_Tests_Dispatch_DispatcherTest extends TestCase
{

    protected $views = array();

    /**
     *
     * @before
     */
    public function setUpTest()
    {
        $this->views = (isset($GLOBALS['_PX_views'])) ? $GLOBALS['_PX_views'] : array();
    }

    /**
     *
     * @after
     */
    public function tearDownTest()
    {
        $GLOBALS['_PX_views'] = $this->views;
    }

    public function hello()
    {
        return new Pluf_HTTP_Response('ok');
    }

    public function hello1()
    {
        return 1;
    }

    public function hello2()
    {
        return 2;
    }

    public function hello3()
    {
        return 3;
    }

    public function hello4()
    {
        return 4;
    }

    /**
     *
     * @test
     */
    public function testSimple()
    {
        $GLOBALS['_PX_views'] = array(
            array(
                'regex' => '#^/hello/$#',
                'base' => '',
                'model' => 'Pluf_Tests_Dispatch_DispatcherTest',
                'method' => 'hello'
            )
        );
        $req1 = (object) array(
            'query' => '/hello/'
        ); // match
        $req2 = (object) array(
            'query' => '/hello'
        ); // match second pass

        $this->assertEquals(200, Pluf_Dispatcher::match($req1)->status_code);
        $this->assertEquals('ok', Pluf_Dispatcher::match($req1)->content);
        $this->assertInstanceOf(Pluf_HTTP_Response_Redirect::class, Pluf_Dispatcher::match($req2));
    }
    /**
     *
     * @test
     * @expectedException Pluf_HTTP_Error404
     */
    public function testSimpleNotfound()
    {
        $GLOBALS['_PX_views'] = array(
            array(
                'regex' => '#^/hello/$#',
                'base' => '',
                'model' => 'Pluf_Tests_Dispatch_DispatcherTest',
                'method' => 'hello'
            )
        );
        $req3 = (object) array(
            'query' => '/hello/you/'
        ); // no match

        Pluf_Dispatcher::match($req3);
    }

    /**
     *
     * @test
     */
    public function testRecursif()
    {
        $GLOBALS['_PX_views'] = array(
            array(
                'regex' => '#^/hello/$#',
                'base' => '',
                'model' => 'Pluf_Tests_Dispatch_DispatcherTest',
                'method' => 'hello3'
            ),
            array(
                'regex' => '#^/hello/#',
                'base' => '',
                'sub' => array(
                    array(
                        'regex' => '#^world/$#',
                        'base' => '',
                        'model' => 'Pluf_Tests_Dispatch_DispatcherTest',
                        'method' => 'hello'
                    ),
                    array(
                        'regex' => '#^hello/$#',
                        'base' => '',
                        'model' => 'Pluf_Tests_Dispatch_DispatcherTest',
                        'method' => 'hello4'
                    )
                )
            ),
            array(
                'regex' => '#^/hello1/#',
                'base' => '',
                'sub' => array(
                    array(
                        'regex' => '#^world/$#',
                        'base' => '',
                        'model' => 'Pluf_Tests_Dispatch_DispatcherTest',
                        'method' => 'hello1'
                    )
                )
            ),
            array(
                'regex' => '#^/hello2/#',
                'base' => '',
                'sub' => array(
                    array(
                        'regex' => '#^world/$#',
                        'base' => '',
                        'model' => 'Pluf_Tests_Dispatch_DispatcherTest',
                        'method' => 'hello2'
                    )
                )
            )
        );
        $req1 = (object) array(
            'query' => '/hello/world/'
        ); // match
        $req2 = (object) array(
            'query' => '/hello/world'
        ); // match second pass
        $h1 = (object) array(
            'query' => '/hello1/world/'
        ); // match
        $h2 = (object) array(
            'query' => '/hello2/world/'
        ); // match
        $h3 = (object) array(
            'query' => '/hello/'
        ); // match
        $h4 = (object) array(
            'query' => '/hello/hello/'
        ); // match
        $this->assertEquals(200, Pluf_Dispatcher::match($req1)->status_code);
        $this->assertEquals('ok', Pluf_Dispatcher::match($req1)->content);
        $this->assertEquals(1, Pluf_Dispatcher::match($h1));
        $this->assertEquals(2, Pluf_Dispatcher::match($h2));
        $this->assertEquals(3, Pluf_Dispatcher::match($h3));
        $this->assertEquals(4, Pluf_Dispatcher::match($h4));
        $this->assertInstanceOf(Pluf_HTTP_Response_Redirect::class, Pluf_Dispatcher::match($req2));
        
        Pluf::loadFunction('Pluf_HTTP_URL_reverse');
        $this->assertEquals('/hello/world/', Pluf_HTTP_URL_reverse('Pluf_Tests_Dispatch_DispatcherTest::hello'));
        $this->assertEquals('/hello1/world/', Pluf_HTTP_URL_reverse('Pluf_Tests_Dispatch_DispatcherTest::hello1'));
        $this->assertEquals('/hello2/world/', Pluf_HTTP_URL_reverse('Pluf_Tests_Dispatch_DispatcherTest::hello2'));
        $this->assertEquals('/hello/', Pluf_HTTP_URL_reverse('Pluf_Tests_Dispatch_DispatcherTest::hello3'));
        $this->assertEquals('/hello/hello/', Pluf_HTTP_URL_reverse('Pluf_Tests_Dispatch_DispatcherTest::hello4'));
    }
}