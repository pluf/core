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
use PHPUnit\Framework\IncompleteTestError;
require_once 'Pluf.php';

class PlufFormFieldTest extends TestCase
{

    protected function setUp ()
    {
        Pluf::start(dirname(__FILE__) . '/../conf/pluf.config.php');
    }

    public function testPlufFormFieldFloat ()
    {
        $field = new Pluf_Form_Field_Float();
        $valids = array(
                1234.12,
                123.0,
                123e+32
        );
        $invalids = array(
                '12,34.12',
                '12+3.0',
                '123es+32'
        );
        foreach ($valids as $valid) {
            $this->assertEquals($valid, $field->clean($valid));
        }
        foreach ($invalids as $invalid) {
            try {
                $field->clean($invalid);
                $this->fail(
                        'An expected Exception has not been raised. Not a valid float: ' .
                                 $invalid);
            } catch (Pluf_Form_Invalid $expected) {
                continue;
            }
        }
        return;
    }

    public function testPlufFormFieldEmail ()
    {
        $field = new Pluf_Form_Field_Email();
        $valids = array(
                'cal@iamcalx.com',
                'cal+henderson@iamcalx.com',
                '"cal henderson"@iamcalx.com',
                'cal@iamcalx',
                'cal@[hello.com]',
                'cal@[hello world.com]',
                'cal@[hello\\ world.com]',
                'abcdefghijklmnopqrstuvwxyz@abcdefghijklmnopqrstuvwxyz',
                '"woo yay"@example.com',
                '"woo@yay"@example.com',
                '"woo.yay"@example.com',
                '"woo\\"yay"@test.com',
                'toto+yop@gmail.com',
                'webstaff@redcross.org'
        );
        $invalids = array(
                'cal henderson@iamcalx.com',
                'cal@iamcalx com',
                'cal@hello world.com',
                'cal@[hello].com',
                'cal@[hello world].com',
                'cal@[hello\\ world].com',
                'woo\\ yay@example.com',
                'woo\\@yay@example.com',
                'woo\\.yay@example.com'
        );
        foreach ($valids as $valid) {
            $this->assertEquals($valid, $field->clean($valid));
        }
        foreach ($invalids as $invalid) {
            try {
                $field->clean($invalid);
                $this->fail(
                        'An expected Exception has not been raised. Not a valid email: ' .
                                 $invalid);
            } catch (Pluf_Form_Invalid $expected) {
                continue;
            }
        }
        return;
    }
}
