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
namespace Pluf\PlufTest;

use PHPUnit\Framework\TestCase;
use Pluf\Bootstrap;
use Pluf\DB;

/**
 *
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class PlufDBTest extends TestCase
{

    public $db;

    /**
     *
     * @before
     */
    public function setUpTest()
    {
        Bootstrap::start(__DIR__ . '/../conf/config.php');
        $this->db = Bootstrap::db();
    }

    public function testEscapeInteger()
    {
        $tests = array(
            '123',
            123,
            123.32,
            'qwe\\qwe',
            '\''
        );
        $res = array(
            '123',
            '123',
            '123',
            '0',
            '0'
        );
        foreach ($tests as $test) {
            $ok = current($res);
            $this->assertEquals($ok, DB::integerToDb($test, $this->db));
            next($res);
        }
    }

    public function testEscapeBoolean()
    {
        $tests = array(
            '123',
            123,
            123.32,
            'qwe\\qwe',
            '\'',
            false,
            '0'
        );
        $res = array(
            "'1'",
            "'1'",
            "'1'",
            "'1'",
            "'1'",
            "'0'",
            "'0'"
        );
        foreach ($tests as $test) {
            $ok = current($res);
            $this->assertEquals($ok, DB::booleanToDb($test, $this->db));
            next($res);
        }
    }
}

