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

require_once 'Pluf.php';

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class PlufSQLTest extends TestCase {
    
    /**
     * @before
     */
    protected function setUpTest()
    {
        Pluf::start(__DIR__. '/../conf/config.php');
        $this->db = Pluf::db();
    }

    public function testSimpleSQLAnd()
    {
        $sql = new Pluf_SQL();
        $sql->Q('blablo=%s', 'bli');
        $this->assertEquals('blablo=\'bli\'', $sql->gen());
    }

    public function testSQLAndOr()
    {
        $sql1 = new Pluf_SQL();
        $sql1->Q('title=%s', 'my title');
        $sql2 = new Pluf_SQL();
        $sql2->Q('description=%s', '%par example');
        $sql3 = new Pluf_SQL();
        $sql3->Q('status=%s', '1');
        $sql4 = new Pluf_SQL();
        $sql4->Q('keywords=%s', "tag'gi`ng");
        $sql1->SAnd($sql2);
        $sql3->SAnd($sql4);
        $sql1->SOr($sql3);
        if ($this->db->engine == 'SQLite') {
            $res = "((title='my title') AND (description='%par example'))";
            $res .= " OR ((status='1') AND (keywords='tag''gi`ng'))";
        } else {
            $res = "((title='my title') AND (description='%par example'))";
            $res .= " OR ((status='1') AND (keywords='tag\'gi`ng'))";
        }
        $this->assertEquals($res, $sql1->gen());
    }

    public function testChainSQLAndOr()
    {
        $sql1 = new Pluf_SQL('title=%s', 'my title');
//         $sql2 = new Pluf_SQL();
        $sql1->Q('description=%s', '%par example')->Q('keywords=%s', "tag'gi`ng");
        $sql3 = new Pluf_SQL();
        $sql3->Q('status=%s', '1');
        $sql1->SOr($sql3);
        if ($this->db->engine == '\Pluf\Db\SQLiteEngine') {
            $res = "(title='my title' AND description='%par example' AND keywords='tag''gi`ng')";
            $res .= " OR (status='1')";
        } else {
            $res = "(title='my title' AND description='%par example' AND keywords='tag\''gi`ng')";
            $res .= " OR (status='1')";
        }
        $this->assertEquals($res, $sql1->gen());
    }

    public function testORSQLKeywords()
    {
        $query = 'key1 key2   key3';
        $fields = array('title', 'description');
        $lastsql = new Pluf_SQL();
        $keywords = $lastsql->keywords($query);
        foreach ($keywords as $key) {
            $sql = new Pluf_SQL();
            foreach ($fields as $field) {
                $sqlor = new Pluf_SQL();
                $sqlor->Q($field.' LIKE %s', '%'.$key.'%');
                $sql->SOr($sqlor);
            }
            $lastsql->SAnd($sql);
        }
        $res = "(((title LIKE '%key1%') OR (description LIKE '%key1%')) AND ((title LIKE '%key2%') OR (description LIKE '%key2%'))) AND ((title LIKE '%key3%') OR (description LIKE '%key3%'))";
        $this->assertEquals($res, $lastsql->gen());
    }
}
