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

class PlufUtilsTest extends TestCase
{

    /**
     * @before
     */
    protected function setUp ()
    {
        Pluf::start(__DIR__. '/../conf/config.php');
    }

    /**
     * @test
     */
    public function testCleanName ()
    {
        $files = array(
                array(
                        'normal',
                        'normal'
                ),
                array(
                        'nor mal',
                        'nor_mal'
                ),
                array(
                        'nor mal.zip',
                        'nor_mal.zip'
                ),
                array(
                        'néor mal.zip',
                        'néor_mal.zip'
                )
        ); 
        // Double byte effect
        foreach ($files as $file) {
            $this->assertTrue($file[1] === Pluf_Utils::cleanFileName($file[0]));
        }
    }

    public function testValidEmail ()
    {
        $emails = array(
                array(
                        'test1@example.com',
                        true
                ),
                array(
                        'test1@example.com-qwe.',
                        false
                ),
                array(
                        'cal@iamcalx.com',
                        true
                ),
                array(
                        'cal+henderson@iamcalx.com',
                        true
                ),
                array(
                        'cal henderson@iamcalx.com',
                        false
                ),
                array(
                        '"cal henderson"@iamcalx.com',
                        false
                ),
                array(
                        'cal@iamcalx',
                        true
                ),
                array(
                        'cal@iamcalx com',
                        false
                ),
                array(
                        'cal@hello world.com',
                        false
                ),
                array(
                        'cal@[hello].com',
                        false
                ),
                array(
                        'cal@[hello world].com',
                        false
                ),
                array(
                        'cal@[hello\\ world].com',
                        false
                ),
                array(
                        'cal@[hello.com]',
                        true
                ),
                array(
                        'cal@[hello world.com]',
                        false
                ),
                array(
                        'cal@[hello\\ world.com]',
                        false
                ),
                array(
                        'abcdefghijklmnopqrstuvwxyz@abcdefghijklmnopqrstuvwxyz',
                        true
                ),
                array(
                        'woo\\ yay@example.com',
                        false
                ),
                array(
                        'woo\\@yay@example.com',
                        false
                ),
                array(
                        'woo\\.yay@example.com',
                        false
                ),
                array(
                        '"woo yay"@example.com',
                        false
                ),
                array(
                        '"woo@yay"@example.com',
                        true
                ),
                array(
                        '"woo.yay"@example.com',
                        true
                ),
                array(
                        '"woo\\"yay"@test.com',
                        true
                ),
                array(
                        'webstaff@redcross.org',
                        true
                ),
                array(
                        'user@???',
                        true
                ),
                array(
                        'user.@domain.com',
                        false
                )
        );
        foreach ($emails as $email) {
            $this->assertFalse($email[1] XOR Pluf_Utils::isValidEmail($email[0]));
        }
    }
}

?>