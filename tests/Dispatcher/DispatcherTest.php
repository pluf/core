<?php
/*
 * This file is part of bootstrap Framework, a simple PHP Application Framework.
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
namespace Pluf\Test\Dispatcher;

use Pluf\Dispatcher;
use Pluf\HTTP\Request;
use Pluf\HTTP\Response;
use Pluf\HTTP\Response\Redirect;
use Pluf\Test\PlufTestCase;

class DispatcherTest extends PlufTestCase
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
        return new Response('ok');
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
        $views = [
            [
                'regex' => '#^/hello/$#',
                'processors' => [
                    SimpleTextProcessor::class
                ]
            ],
            [
                'regex' => '#^/hello-redirect/$#',
                'processors' => [
                    RedirectProcessor::class
                ]
            ]
        ];
        $req1 = new Request('/hello/');
        $req2 = new Request('/hello-redirect/');

        $dispatcher = Dispatcher::getInstance()->setViews($views);
        $this->assertEquals(200, $dispatcher->dispatch($req1)
            ->getStatusCode());
        $this->assertEquals('ok', $dispatcher->dispatch($req1)
            ->getBody());
        $this->assertInstanceOf(Redirect::class, $dispatcher->dispatch($req2));
    }

    /**
     *
     * @test
     */
    public function testSimpleNotfound()
    {
        $views = [
            [
                'regex' => '#^/hello/$#',
                'processors' => [
                    SimpleTextProcessor::class
                ]
            ]
        ];
        $this->assertEquals(404, Dispatcher::getInstance()->setViews($views)
            ->dispatch(new Request('/hello/you/'))
            ->getStatusCode());
    }

    /**
     *
     * @test
     */
    public function testRecursif()
    {
        $views = [
            [
                'regex' => '#^/hello$#',
                'processors' => [
                    CounterProcessor::class
                ]
            ],
            [
                'regex' => '#^/hello#',
                'processors' => [
                    CounterProcessor::class
                ],
                'sub' => [
                    [
                        'regex' => '#^/hello$#',
                        'processors' => [
                            CounterProcessor::class
                        ]
                    ],
                    [
                        'regex' => '#^/hello#',
                        'processors' => [
                            CounterProcessor::class
                        ],
                        'sub' => [
                            [
                                'regex' => '#^/hello$#',
                                'processors' => [
                                    CounterProcessor::class
                                ]
                            ],
                            [
                                'regex' => '#^/hello#',
                                'processors' => [
                                    CounterProcessor::class
                                ],
                                'sub' => [
                                    [
                                        'regex' => '#^/hello$#',
                                        'processors' => [
                                            CounterProcessor::class
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $dispatcher = Dispatcher::getInstance()->setViews($views);

        $this->assertEquals(200, $dispatcher->dispatch(new Request('/hello'))
            ->getStatusCode());
        $this->assertEquals(1, $dispatcher->dispatch(new Request('/hello'))
            ->getBody());
        $this->assertEquals(2, $dispatcher->dispatch(new Request('/hello/hello'))
            ->getBody());
        $this->assertEquals(3, $dispatcher->dispatch(new Request('/hello/hello/hello'))
            ->getBody());
        $this->assertEquals(4, $dispatcher->dispatch(new Request('/hello/hello/hello/hello'))
            ->getBody());
    }
}