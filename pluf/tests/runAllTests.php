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


/**
 * Run all the unit tests of the Plume Framework.
 */

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Framework_AllTests::main');
}

if (!defined('PHPUnit_INSIDE_OWN_TESTSUITE')) {
    define('PHPUnit_INSIDE_OWN_TESTSUITE', TRUE);
}

require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'PHPUnit/Framework/TestCase.php';
 
#require_once 'Framework/AllTests.php';

#require_once 'PHPUnit/Framework/TestSuite.php';
#require_once 'PHPUnit/TextUI/TestRunner.php';
#require_once 'PHPUnit/Util/Filter.php';

error_reporting(E_ALL | E_STRICT);
putenv('TZ=UTC');

function getTestDirs($dir='./')
{
    $file = new DirectoryIterator($dir);
    $res = array();
    while ($file->valid()) {
        if ($file->isDir() && !$file->isDot()) {
            $res[] = $file->getPathName();
        }
        $file->next();
    }
    return $res;
}

function getTestFiles($dir='')
{
    $file = new DirectoryIterator($dir);
    $res = array();
    while ($file->valid()) {
        if ($file->isFile() && substr($file->getPathName(), -8) == 'Test.php') {
            $res[] = $file->getPathName();
        }
        $file->next();
    }
    return $res;
}


class Framework_AllTests
{
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Plume_Framework_Test');
        $dirs = getTestDirs();
        foreach ($dirs as $dir) {
            $testfiles = getTestFiles($dir);
            foreach ($testfiles as $test) {
                $suite->addTestFile(substr($test, 2));
            }
        }
        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'Framework_AllTests::main') {
    Framework_AllTests::main();
}


//print $tests." tests performed.\n";
?>