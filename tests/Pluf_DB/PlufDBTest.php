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

class PlufDBTest extends PHPUnit_Framework_TestCase {

    public $db;    

    protected function setUp()
    {
        Pluf::start(dirname(__FILE__).'/../conf/pluf.config.php');
        $this->db = &Pluf::db();

    }

    public function testEscapeInteger()
    {
        $tests = array('123', 123, 123.32, 'qwe\\qwe', '\'');
        $res = array('123', '123', '123', '0', '0');
        foreach ($tests as $test) {
            $ok = current($res);
            $this->assertEquals($ok, Pluf_DB_IntegerToDb($test, $this->db));
            next($res);
        }
    }

    public function testEscapeBoolean()
    {
        $tests = array('123', 123, 123.32, 'qwe\\qwe', '\'', false, '0');
        $res = array("'1'", "'1'", "'1'", "'1'", "'1'", "'0'", "'0'");
        foreach ($tests as $test) {
            $ok = current($res);
            $this->assertEquals($ok, Pluf_DB_BooleanToDb($test, $this->db));
            next($res);
        }
    }
}

