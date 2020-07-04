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
namespace Pluf\Test\Data;

require_once 'Pluf.php';

use PHPUnit\Framework\TestCase;
use Pluf\Options;
use Pluf\Data\Query;
use Pluf\NoteBook\Book;
use Pluf\NoteBook\Tag;
use Pluf;
use Pluf_Migration;

class RepositoryTest extends TestCase
{

    /**
     *
     * @beforeClass
     */
    public static function installApplication()
    {
        Pluf::start(__DIR__ . '/../conf/config.php');
        $m = new Pluf_Migration();
        $m->install();
    }

    /**
     *
     * @afterClass
     */
    public static function deleteApplication()
    {
        $m = new Pluf_Migration();
        $m->uninstall();
    }

    /**
     * Getting list of books with repository model
     *
     * @test
     */
    public function getListOfBookByOptions()
    {
        $repo = Pluf::getDataRepository([
            'model' => Book::class
        ]);
        $this->assertNotNull($repo);

        $query = new Query([
            'filter' => [
                [
                    'title',
                    '=',
                    'my title'
                ],
                [
                    'id',
                    '>',
                    5
                ]
            ]
        ]);

        $items = $repo->get($query);
        $this->assertNotNull($items);
    }

    /**
     * Getting list of books with repository model
     *
     * @test
     */
    public function getListOfBookByClassName()
    {
        $repo = Pluf::getDataRepository(Book::class);
        $this->assertNotNull($repo);

        $query = new Query([
            'filter' => [
                [
                    'title',
                    '=',
                    'my title'
                ],
                [
                    'id',
                    '>',
                    5
                ]
            ]
        ]);

        $items = $repo->get($query);
        $this->assertNotNull($items);
    }

    /**
     * Getting list of books with repository model
     *
     * @test
     */
    public function getListOfBookByOptionsModel()
    {
        $repo = Pluf::getDataRepository(new Options([
            'model' => Book::class
        ]));
        $this->assertNotNull($repo);

        $query = new Query([
            'filter' => [
                [
                    'title',
                    '=',
                    'my title'
                ],
                [
                    'id',
                    '>',
                    5
                ]
            ]
        ]);

        $items = $repo->get($query);
        $this->assertNotNull($items);
    }

    /**
     * Getting list of books with repository model
     *
     * @test
     */
    public function getListOfTagsByOptionsModel()
    {
        $repo = Pluf::getDataRepository(new Options([
            'model' => Tag::class
        ]));
        $this->assertNotNull($repo);

        $query = new Query([
            'filter' => [
                [
                    'title',
                    '=',
                    'my title'
                ],
                [
                    'id',
                    '>',
                    5
                ]
            ]
        ]);

        $items = $repo->get($query);
        $this->assertNotNull($items);
    }

    /**
     *
     * @test
     */
    public function putTagsByOptionsModel()
    {
        $repo = Pluf::getDataRepository(new Options([
            'model' => Tag::class
        ]));
        $this->assertNotNull($repo);

        $tag = new Tag();
        $tag->title = 'Hi';
        $tag->create();
        $this->assertFalse($tag->isAnonymous());

        $items = $repo->get();
        $this->assertNotNull($items);
        $this->assertTrue(count($items) > 0);
    }

    /**
     *
     * @test
     */
    public function putTagsByOptionsModelByRepo()
    {
        $repo = Pluf::getDataRepository(new Options([
            'model' => Tag::class
        ]));
        $this->assertNotNull($repo);

        $tag = new Tag();
        $tag->title = 'Hi';
        $repo->create($tag);
        $this->assertFalse($tag->isAnonymous());

        $items = $repo->get();
        $this->assertNotNull($items);
        $this->assertTrue(count($items) > 0);
    }

    /**
     *
     * @test
     */
    public function updateTagsByOptionsModelByRepo()
    {
        $repo = Pluf::getDataRepository(new Options([
            'model' => Tag::class
        ]));
        $this->assertNotNull($repo);

        $tag = new Tag();
        $tag->title = 'Hi';
        $repo->create($tag);
        $this->assertFalse($tag->isAnonymous());

        $items = $repo->get();
        $this->assertNotNull($items);
        $this->assertTrue(count($items) > 0);

        $tag2 = new Tag($tag->id);
        $this->assertFalse($tag2->isAnonymous());
        $this->assertEquals($tag->title, $tag2->title);

        $tag2->title = rand() . '-name';
        $repo->update($tag2);

        $tag3 = new Tag($tag2->id);
        $this->assertFalse($tag3->isAnonymous());
        $this->assertEquals($tag2->title, $tag3->title);
    }
}

