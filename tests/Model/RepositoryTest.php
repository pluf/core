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

namespace Pluf\Test\Model;

use PHPUnit\Framework\TestCase;
use Pluf;
use Pluf_Migration;
use Pluf\Model\QueryBuilder;
use Pluf\Model\Repository;
use Pluf\NoteBook\Book;

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
    public function getListOfBook()
    {
        $query = QueryBuilder::getInstance()
            ->setFilter(array(
                'title=\'test\''
            ))
            ->setOrder(array(
                'id'
            ))
            ->build();
        $res = Repository::getInstance(Book::class)
            ->getList($query);
        $this->assertNotNull($res);
    }
}

