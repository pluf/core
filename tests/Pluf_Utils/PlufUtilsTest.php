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

class PlufUtilsTest extends PHPUnit_Framework_TestCase {
    
    protected function setUp()
    {
        Pluf::start(dirname(__FILE__).'/../conf/pluf.config.php');
    }

    public function testCleanName()
    {
        $files = array(
                       array('normal', 'normal'),
                       array('nor mal', 'nor_mal'),
                       array('nor mal.zip', 'nor_mal.zip'),
                       array('néor mal.zip', 'n__or_mal.zip'), //Double byte effect
                       );
        foreach ($files as $file) {
            $this->assertEquals($file[1], Pluf_Utils::cleanFileName($file[0]));
        }
    }

    public function testValidEmail()
    {
        $emails = array(
                        array('test1@example.com', true),
                        array('test1@example.com-qwe.', false),
                        array('cal@iamcalx.com', true),
                        array('cal+henderson@iamcalx.com', true),
                        array('cal henderson@iamcalx.com', false),
                        array('"cal henderson"@iamcalx.com', true),
                        array('cal@iamcalx', true),
                        array('cal@iamcalx com', false),
                        array('cal@hello world.com', false),
                        array('cal@[hello].com', false),
                        array('cal@[hello world].com', false),
                        array('cal@[hello\\ world].com', false),
                        array('cal@[hello.com]', true),
                        array('cal@[hello world.com]', true),
                        array('cal@[hello\\ world.com]', true),
                        array('abcdefghijklmnopqrstuvwxyz@abcdefghijklmnopqrstuvwxyz', true),
                        array('woo\\ yay@example.com', false),
                        array('woo\\@yay@example.com', false),
                        array('woo\\.yay@example.com', false),
                        array('"woo yay"@example.com', true),
                        array('"woo@yay"@example.com', true),
                        array('"woo.yay"@example.com', true),
                        array('"woo\\"yay"@test.com', true),
                        array('webstaff@redcross.org', true),
                        array('user@???', true),
                        array('user.@domain.com', false),
                        );
        foreach ($emails as $email) {
            $this->assertEquals($email[1], Pluf_Utils::isValidEmail($email[0]), $email[0]);
        }
    }
}

?>