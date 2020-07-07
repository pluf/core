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
namespace Pluf\Test\Db;

use Pluf\Db\Connection;
use Pluf\Test\PlufTestCase;

class OracleTest extends PlufTestCase
{

    /**
     * Test constructor.
     */
    public function testDetection()
    {
        try {
            $c = Connection::connect('oci:dbname=mydb');
            $this->assertEquals('select "baz" from "foo" where "bar" = :a', $c->dsql()
                ->table('foo')
                ->where('bar', 1)
                ->field('baz')
                ->render());
        } catch (\PDOException $e) {
            if (! extension_loaded('oci8')) {
                $this->markTestSkipped('The oci8 extension is not available.');
            }

            throw $e;
        }
    }

    public function connect($ver = '')
    {
        return new Connection(array_merge([
            'connection' => new \PDO('sqlite::memory:'),
            'query_class' => 'Pluf\Db\Query\Oracle' . $ver,
            'expression_class' => 'atk4\dsql\Expression_Oracle'
        ]));
    }

    public function testOracleClass()
    {
        $c = $this->connect();
        $this->assertEquals('select "baz" from "foo" where "bar" = :a', $c->dsql()
            ->table('foo')
            ->where('bar', 1)
            ->field('baz')
            ->render());

        $this->assertEquals('select "baz" "ali" from "foo" where "bar" = :a', $c->dsql()
            ->table('foo')
            ->where('bar', 1)
            ->field('baz', 'ali')
            ->render());
    }

    public function testClassicOracleLimit()
    {
        $c = $this->connect();
        $this->assertEquals('select * from (select rownum "__dsql_rownum","__t".* from (select "baz" from "foo" where "bar" = :a) "__t") where "__dsql_rownum">0 and "__dsql_rownum"<=10', $c->dsql()
            ->table('foo')
            ->where('bar', 1)
            ->field('baz')
            ->limit(10)
            ->render());

        $this->assertEquals('select * from (select rownum "__dsql_rownum","__t".* from (select "baz" "baz_alias" from "foo" where "bar" = :a) "__t") where "__dsql_rownum">0 and "__dsql_rownum"<=10', $c->dsql()
            ->table('foo')
            ->where('bar', 1)
            ->field('baz', 'baz_alias')
            ->limit(10)
            ->render());
    }

    public function test12cOracleLimit()
    {
        $c = $this->connect('12c');
        $this->assertEquals('select "baz" from "foo" where "bar" = :a FETCH NEXT 10 ROWS ONLY', $c->dsql()
            ->table('foo')
            ->where('bar', 1)
            ->field('baz')
            ->limit(10)
            ->render());
    }

    public function testClassicOracleSkip()
    {
        $c = $this->connect();
        $this->assertEquals('select * from (select rownum "__dsql_rownum","__t".* from (select "baz" from "foo" where "bar" = :a) "__t") where "__dsql_rownum">10', $c->dsql()
            ->table('foo')
            ->where('bar', 1)
            ->field('baz')
            ->limit(null, 10)
            ->render());
    }

    public function test12cOracleSkip()
    {
        $c = $this->connect('12c');
        $this->assertEquals('select "baz" from "foo" where "bar" = :a OFFSET 10 ROWS', $c->dsql()
            ->table('foo')
            ->where('bar', 1)
            ->field('baz')
            ->limit(null, 10)
            ->render());
    }

    public function testClassicOracleLimitSkip()
    {
        $c = $this->connect();
        $this->assertEquals('select * from (select rownum "__dsql_rownum","__t".* from (select "baz" from "foo" where "bar" = :a) "__t") where "__dsql_rownum">99 and "__dsql_rownum"<=109', $c->dsql()
            ->table('foo')
            ->where('bar', 1)
            ->field('baz')
            ->limit(10, 99)
            ->render());
    }

    public function test12cOracleLimitSkip()
    {
        $c = $this->connect('12c');
        $this->assertEquals('select "baz" from "foo" where "bar" = :a OFFSET 99 ROWS FETCH NEXT 10 ROWS ONLY', $c->dsql()
            ->table('foo')
            ->where('bar', 1)
            ->field('baz')
            ->limit(10, 99)
            ->render());
    }
}
