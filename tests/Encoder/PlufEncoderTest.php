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
namespace Pluf\PlufTest\Encoder;

use PHPUnit\Framework\TestCase;
use Pluf\Bootstrap;
use Pluf\FormInvalidException;

/**
 *
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class EncoderTest extends TestCase
{

    /**
     * Undocumented function
     *
     * @return void
     * @before
     */
    protected function setUpTest()
    {
        Bootstrap::start(__DIR__ . '/../conf/config.php');
    }

    /**
     * Undocumented function
     *
     * @after
     * @return void
     */
    protected function tearDownTest()
    {
        putenv('PHP_TZ=' . Bootstrap::f('timezone'));
    }

    public function testEncoder()
    {
        $p = array();
        $form = array();
        $enc = new \Pluf\Encoder();
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
            } catch (FormInvalidException $e) {
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
            '2006.05.12'
        );
        foreach ($good as $date) {
            $this->assertEquals($date, $enc->date($date, $form, $p));
        }
        foreach ($bad as $date) {
            try {
                $enc->date($date, $form, $p);
                $this->assertEquals(false, $date);
            } catch (FormInvalidException $e) {
                $this->assertEquals(true, true);
            }
        }
    }

    public function testTimeShift()
    {
        $enc = new \Pluf\Encoder();
        $p = array(
            'blank' => false
        );
        $form = array();
        // When passing a datetime (not a date and not a time)
        // from the browser, the datetime must be converted
        // into GMT time.
        $tests = array();
        $tests[] = array(
            'Europe/Berlin',
            '2006-03-16 01:15:35',
            '2006-03-16 00:15:35'
        );
        $tests[] = array(
            'America/New_York',
            '2006-03-16 01:15:35',
            '2006-03-16 06:15:35'
        );
        $tests[] = array(
            'America/Los_Angeles',
            '2006-03-16 01:15:35',
            '2006-03-16 09:15:35'
        );
        foreach ($tests as $test) {
            putenv('TZ=' . $test[0]);
            date_default_timezone_set($test[0]);
            $this->assertEquals($test[2], $enc->datetime($test[1], $form, $p));
            $this->assertEquals($test[1], date('Y-m-d H:i:s', strtotime($test[2] . ' GMT')));
        }
    }
}