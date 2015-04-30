<?php
/* -*- tab-width: 4; indent-tabs-mode: nil; c-basic-offset: 4 -*- */
/*
# ***** BEGIN LICENSE BLOCK *****
# This file is part of Plume Framework, a simple PHP Application Framework.
# Copyright (C) 2001-2007 Loic d'Anterroches and contributors.
#
# Plume Framework is free software; you can redistribute it and/or modify
# it under the terms of the GNU Lesser General Public License as published by
# the Free Software Foundation; either version 2.1 of the License, or
# (at your option) any later version.
#
# Plume Framework is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU Lesser General Public License for more details.
#
# You should have received a copy of the GNU Lesser General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
#
# ***** END LICENSE BLOCK ***** */

error_reporting(E_ALL | E_STRICT);

$path = dirname(__FILE__).'/../../src/';
set_include_path(get_include_path().PATH_SEPARATOR.$path);

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'PHPUnit/Framework/IncompleteTestError.php';

require_once 'Pluf.php';

class PlufSQLTest extends PHPUnit_Framework_TestCase {
    
    protected function setUp()
    {
        Pluf::start(dirname(__FILE__).'/../conf/pluf.config.php');
        $this->db = Pluf::db();
    }

    protected function tearDown()
    {
    }

    public function testSimpleSQLAnd()
    {
        $sql = Pluf::factory('Pluf_SQL');
        $sql->Q('blablo=%s', 'bli');
        $this->assertEquals('blablo=\'bli\'', $sql->gen());
    }

    public function testSQLAndOr()
    {
        $sql1 = Pluf::factory('Pluf_SQL');
        $sql1->Q('title=%s', 'my title');
        $sql2 = Pluf::factory('Pluf_SQL');
        $sql2->Q('description=%s', '%par example');
        $sql3 = Pluf::factory('Pluf_SQL');
        $sql3->Q('status=%s', '1');
        $sql4 = Pluf::factory('Pluf_SQL');
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
        $sql2 = Pluf::factory('Pluf_SQL');
        $sql1->Q('description=%s', '%par example')->Q('keywords=%s', "tag'gi`ng");
        $sql3 = Pluf::factory('Pluf_SQL');
        $sql3->Q('status=%s', '1');
        $sql1->SOr($sql3);
        if ($this->db->engine == 'SQLite') {
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
        $lastsql = Pluf::factory('Pluf_SQL');
        $keywords = $lastsql->keywords($query);
        foreach ($keywords as $key) {
            $sql = Pluf::factory('Pluf_SQL');
            foreach ($fields as $field) {
                $sqlor = Pluf::factory('Pluf_SQL');
                $sqlor->Q($field.' LIKE %s', '%'.$key.'%');
                $sql->SOr($sqlor);
            }
            $lastsql->SAnd($sql);
        }
        $res = "(((title LIKE '%key1%') OR (description LIKE '%key1%')) AND ((title LIKE '%key2%') OR (description LIKE '%key2%'))) AND ((title LIKE '%key3%') OR (description LIKE '%key3%'))";
        $this->assertEquals($res, $lastsql->gen());
    }
}

?>