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

class PlufTest extends PHPUnit_Framework_TestCase {
    
    protected function setUp()
    {
        Pluf::start(dirname(__FILE__).'/../conf/pluf.config.php');
    }

    public function testF()
    {
        $this->assertEquals(Pluf::f('test'), false);
    }

    public function testFactory()
    {
        $pluf = Pluf::factory('Pluf');
        $this->assertEquals(get_class($pluf), 'Pluf');
    }

    public function testFileExists()
    {
        $this->assertEquals(true, Pluf::fileExists('Pluf.php'));
        $this->assertEquals(true, Pluf::fileExists('PEAR.php'));
        $this->assertEquals(false, Pluf::fileExists('Pluf-dummy.php'));
    }

    public function testLoadClass()
    {
        Pluf::loadClass('Pluf_Model');
        $this->assertEquals(true, class_exists('Pluf_Model'));
    }

    public function testLoadFunction()
    {
        Pluf::loadFunction('Pluf_HTTP_handleMagicQuotes');
        $this->assertEquals(true, function_exists('Pluf_HTTP_handleMagicQuotes'));
    }
}

?>