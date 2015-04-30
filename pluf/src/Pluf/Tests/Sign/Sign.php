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

class Pluf_Tests_Sign_Sign extends UnitTestCase 
{
    function __construct() 
    {
        parent::__construct('Test the signing functions.');
    }

    function setUp()
    {
    }

    function tearDown()
    {
    }

    function testSignUsesCorrectKey()
    {
        $s = 'This is a string';
        $this->assertEqual(Pluf_Sign::sign($s),
                           $s.'.'.Pluf_Sign::base64_hmac($s, 
                                                         Pluf::f('secret_key')
                                                         )
                           );
    }

    function testSignIsReversible()
    {
        $examples = array(
                          'q;wjmbk;wkmb',
                          '3098247529087',
                          '3098247:529:087:',
                          'jkw osanteuh ,rcuh nthu aou oauh ,ud du',
                          );
        foreach ($examples as $example) {
            $this->assertTrue($example != Pluf_Sign::sign($example));
            $this->assertEqual($example, Pluf_Sign::unsign(Pluf_Sign::sign($example)));
        }
    }

    function testUnsignDetectsTampering()
    {
        $value = 'Another string';
        $signed_value = Pluf_Sign::sign($value);
        $transforms = array(
                            strtoupper($signed_value),
                            $signed_value.'a',
                            'a'.substr($signed_value, 1),
                            str_replace('w', '', $signed_value),
                            );
        $this->assertEqual($value, Pluf_Sign::unsign($signed_value));
        foreach ($transforms as $t) {
            try {
                Pluf_Sign::unsign($t);
                $this->fail();
            } catch (Exception $e) {
                $this->pass();
            }
        }
    }
 
    function testEncodeDecode()
    {
        $objects = array(
                         array('an', 'array'),
                         'a string',
                         (object) array('a' => 'property'),
                         );
        foreach ($objects as $o) {
            $this->assertTrue($o != Pluf_Sign::dumps($o));
            $this->assertEqual($o, Pluf_Sign::loads(Pluf_Sign::dumps($o)));
        }
    }
    
    function testDecodeDetectsTampering()
    {
        $value = array('foo'=> 'bar', 'baz'=> 1);
        $encoded = Pluf_Sign::dumps($value);
        $transforms = array(
                            strtoupper($encoded),
                            $encoded.'a',
                            'a'.substr($encoded, 1),
                            str_replace('M', '', $encoded),
                            );
        $this->assertEqual($value, Pluf_Sign::loads($encoded));
        foreach ($transforms as $t) {
            try {
                Pluf_Sign::loads($t);
                $this->fail();
            } catch (Exception $e) {
                $this->pass();
            }
        }
    }
}