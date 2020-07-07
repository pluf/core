<?php
/*
 * This file is part of bootstrap Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. http://dpq.co.ir
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
namespace Pluf\Test\HTTP\Response;

use PHPUnit\Framework\TestCase;
use Pluf\HTTP\Response\File;

/**
 *
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class FileHashCodeTest extends TestCase
{

    /**
     *
     * @before
     */
    public function setUpTest()
    {
        // TODO:
        $this->TestFileHashCode = md5_file(__DIR__ . '/TestFile');
    }

    /**
     *
     * @test
     */
    public function testHashFunction()
    {
        $response = new File(__DIR__ . '/TestFile');
        $this->assertTrue(method_exists($response, 'hashCode'));
    }

    /**
     *
     * @test
     */
    public function testHashFunction1()
    {
        $response = new File(__DIR__ . '/TestFile');
        $this->assertTrue($this->TestFileHashCode === $response->hashCode());
    }

    /**
     *
     * @test
     */
    public function testHashFunction2()
    {
        $response = new File(__DIR__ . '/TestFile');
        $this->assertTrue($this->TestFileHashCode === $response->hashCode());
        $this->assertTrue($this->TestFileHashCode === $response->hashCode());
    }
}
