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

class PlufEncoderTest extends PHPUnit_Framework_TestCase {
    
    protected function setUp()
    {
        Pluf::start(dirname(__FILE__).'/../conf/pluf.config.php');
    }

    protected function tearDown()
    {
        putenv('PHP_TZ='.Pluf::f('timezone')); 
    }

    public function testEncoder()
    {
        $p = array();
        $form = array();
        $enc = Pluf::factory('Pluf_Encoder');
        $this->assertEquals(true, $enc->checkEmpty('', $form, $p));
        $p['blank'] = false;
        // ------------- url -------------------------------
        $good = array(
                      'http://www.example.com/lkjasd',
                      'https://wwwcom/lkjasd',
                      'https://www-com/lkjasd',
                      'http://123.345.234.12/lkjasd'
                      );
        $bad = array(
                     'www.com'
                     );
        foreach ($good as $url) {
            $this->assertEquals($url, $enc->url($url, $form, $p));
        }
        foreach ($bad as $url) {
            try {
                $enc->url($url, $form, $p);
                $this->assertEquals(false, $url);
            } catch (Pluf_Form_Invalid $e) {
                $this->assertEquals(true, true);
            }
        }
        // ------------- date -------------------------------
        $good = array(
                      '1995-12-04',
                      '1995-12-1',
                      '1000-2-2',
                      '9999-12-31'
                      );
        $bad = array(
                     '23-12-2',
                     '1996-2-31',
                     '2006.05.12',
                     );
        foreach ($good as $date) {
            $this->assertEquals($date, $enc->date($date, $form, $p));
        }
        foreach ($bad as $date) {
            try {
                $enc->date($date, $form, $p);
                $this->assertEquals(false, $date);
            } catch (Pluf_Form_Invalid $e) {
                $this->assertEquals(true, true);
            }
        }

    }

    public function testTimeShift()
    {
        $enc = Pluf::factory('Pluf_Encoder');
        $p = array('blank' => false);
        $form = array();
        // When passing a datetime (not a date and not a time)
        // from the browser, the datetime must be converted 
        // into GMT time.
        $tests = array();
        $tests[] = array('Europe/Berlin', 
                         '2006-03-16 01:15:35', '2006-03-16 00:15:35');
        $tests[] = array('America/New_York', 
                         '2006-03-16 01:15:35', '2006-03-16 06:15:35');
        $tests[] = array('America/Los_Angeles', 
                         '2006-03-16 01:15:35', '2006-03-16 09:15:35');
        foreach ($tests as $test) {
            putenv('TZ='.$test[0]);
            date_default_timezone_set($test[0]);
            $this->assertEquals($test[2], $enc->datetime($test[1], $form, $p));
            $this->assertEquals($test[1], date('Y-m-d H:i:s', strtotime($test[2].' GMT')));
        }

    }
}

?>