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

class PlufCalendarTest extends PHPUnit_Framework_TestCase {
    
    public $events = array(
                           array('start' => '2007-02-06 08:00:00',
                                 'end'   => '2007-02-06 09:15:00',
                                 'title' => 'Dummy event 1',
                                 'url'   => '',
                                 'color' => '', ),
                           array('start' => '2007-02-07 08:00:00',
                                 'end'   => '2007-02-07 09:15:00',
                                 'title' => 'Dummy event 2',
                                 'url'   => '',
                                 'color' => '', ),
                           array('start' => '2007-02-08 12:00:00',
                                 'end'   => '2007-02-08 13:15:00',
                                 'title' => 'Dummy event 3',
                                 'url'   => '',
                                 'color' => '', ),
                           array('start' => '2007-02-09 08:30:00',
                                 'end'   => '2007-02-09 09:25:00',
                                 'title' => 'Dummy event 4',
                                 'url'   => '',
                                 'color' => '', ),
                           array('start' => '2007-02-10 11:00:00',
                                 'end'   => '2007-02-10 11:45:00',
                                 'title' => 'Dummy event 5',
                                 'url'   => '',
                                 'color' => '', ),
                           );
    public $events2 = array(
                           array('start' => '2007-02-06 08:30:00',
                                 'end'   => '2007-02-06 09:25:00',
                                 'title' => 'Dummy event 1',
                                 'url'   => '',
                                 'color' => '', ),
                           array('start' => '2007-02-07 08:00:00',
                                 'end'   => '2007-02-07 09:15:00',
                                 'title' => 'Dummy event 2',
                                 'url'   => '',
                                 'color' => '', ),
                           array('start' => '2007-02-08 12:00:00',
                                 'end'   => '2007-02-08 13:15:00',
                                 'title' => 'Dummy event 3',
                                 'url'   => '',
                                 'color' => '', ),
                           array('start' => '2007-02-09 08:30:00',
                                 'end'   => '2007-02-09 09:25:00',
                                 'title' => 'Dummy event 4',
                                 'url'   => '',
                                 'color' => '', ),
                           array('start' => '2007-02-10 11:00:00',
                                 'end'   => '2007-02-10 11:45:00',
                                 'title' => 'Dummy event 5',
                                 'url'   => '',
                                 'color' => '', ),
                           array('start' => '2007-02-06 08:00:00',
                                 'end'   => '2007-02-06 11:00:00',
                                 'title' => 'Dummy event 6',
                                 'url'   => '',
                                 'color' => '', ),
                           array('start' => '2007-02-06 12:00:00',
                                 'end'   => '2007-02-06 13:15:00',
                                 'title' => 'Dummy event 7',
                                 'url'   => '',
                                 'color' => '', ),
                           array('start' => '2007-02-06 09:25:00',
                                 'end'   => '2007-02-06 11:45:00',
                                 'title' => 'Dummy event 8',
                                 'url'   => '',
                                 'color' => '', ),
                           );
    public $events3 = array(
                            array('start' => '2007-09-17 15:30:00', 
                                  'end' => '2007-09-17 16:30:00', 
                                  'title' => 'Green organic synthesis routes', 
                                  'url' => '', 
                                  'color' => '', ),
                            array('start' => '2007-09-17 17:30:00', 
                                  'end' => '2007-09-17 19:30:00', 
                                  'title' => 'Test poster cases with thermo', 
                                  'url' => '', 
                                  'color' => '', ), 
                            array('start' => '2007-09-17 17:00:00', 
                                  'end' => '2007-09-17 19:30:00', 
                                  'title' => 'Environmental engineering & management', 
                                  'url' => '', 
                                  'color' => '', ), 
                            array('start' => '2007-09-17 15:30:00', 
                                  'end' => '2007-09-17 19:30:00', 
                                  'title' => 'Chemical Reaction Engineering', 
                                  'url' => '', 
                                  'color' => '', ), 
                            );

    protected function setUp()
    {
        Pluf::start(dirname(__FILE__).'/../conf/pluf.config.php');
    }

    public function testCalendar()
    {
        $calendar = new Pluf_Calendar();
        $calendar->events = $this->events;
        $calendar->opts['start-day'] = '2007-02-06';
        $calendar->opts['end-day'] = '2007-02-10';
        $res = file_get_contents(dirname(__FILE__).'/simplecalendar1.html');
        $this->assertEquals($res, $calendar->render());
    }

    public function testCalendar4()
    {
        $calendar = new Pluf_Calendar();
        $calendar->events = $this->events3;
        $res = file_get_contents(dirname(__FILE__).'/simplecalendar4.html');
        $this->assertEquals($res, $calendar->render());
    }

    public function testCalendar5()
    {
        $calendar = new Pluf_Calendar();
        $calendar->events = array(array('start' => '2007-09-17 15:30:00', 
                                        'end' => '2007-09-17 16:30:00', 
                                        'title' => 'Green organic synthesis routes', 
                                        'url' => '', 
                                        'color' => '', ),
                                  array('start' => '2007-09-17 17:30:00', 
                                        'end' => '2007-09-17 19:30:00', 
                                        'title' => 'Test poster cases with thermo', 
                                        'url' => '', 
                                        'color' => '', ), 
                                  array('start' => '2007-09-17 17:00:00', 
                                        'end' => '2007-09-17 19:30:00', 
                                        'title' => 'Environmental engineering & management', 
                                        'url' => '', 
                                        'color' => '', ),
                                  array('start' => '2007-09-17 15:30:00', 
                                        'end' => '2007-09-17 19:30:00', 
                                        'title' => 'Chemical Reaction Engineering', 
                                        'url' => '', 
                                        'color' => '', ),
                                  array('start' => '2007-09-17 12:30:00', 
                                        'end' => '2007-09-17 19:30:00', 
                                        'title' => 'TRansport', 
                                        'url' => '', 'color' => '', ), 
                                  array('start' => '2007-09-17 15:30:00', 
                                        'end' => '2007-09-17 17:00:00', 
                                        'title' => 'Distillation, absorption and extraction', 
                                        'url' => '', 
                                        'color' => '', ), 
                                  array('start' => '2007-09-17 12:30:00', 
                                        'end' => '2007-09-17 16:00:00', 
                                        'title' => 'Membranes and membrane science', 
                                        'url' => '', 
                                        'color' => '', ),
                                  array('start' => '2007-09-17 12:30:00', 
                                        'end' => '2007-09-17 16:00:00', 
                                        'title' => 'Polymer science & engineering', 
                                        'url' => '', 
                                        'color' => '', ));

        $res = file_get_contents(dirname(__FILE__).'/simplecalendar5.html');
        $this->assertEquals($res, $calendar->render());
    }

    public function testCalendar6()
    {
        $calendar = new Pluf_Calendar();
        $calendar->events = array (
                                   0 => 
                                   array (
                                          'start' => '2007-09-17 12:30:00',
                                          'end' => '2007-09-17 19:30:00',
                                          'title' => 'TRansport',
                                          'url' => '',
                                          'color' => '',
                                          ),
                                   1 => 
                                   array (
                                          'start' => '2007-09-17 12:30:00',
                                          'end' => '2007-09-17 16:00:00',
                                          'title' => 'Membranes and membrane science',
                                          'url' => '',
                                          'color' => '',
                                          ),
                                   2 => 
                                   array (
                                          'start' => '2007-09-17 12:30:00',
                                          'end' => '2007-09-17 16:00:00',
                                          'title' => 'Polymer science & engineering',
                                          'url' => '',
                                          'color' => '',
                                          ),
                                   3 => 
                                   array (
                                          'start' => '2007-09-17 15:30:00',
                                          'end' => '2007-09-17 16:30:00',
                                          'title' => 'Green organic synthesis routes',
                                          'url' => '',
                                          'color' => '',
                                          ),
                                   4 => 
                                   array (
                                          'start' => '2007-09-17 15:30:00',
                                          'end' => '2007-09-17 19:30:00',
                                          'title' => 'Chemical Reaction Engineering',
                                          'url' => '',
                                          'color' => '',
                                          ),
                                   5 => 
                                   array (
                                          'start' => '2007-09-17 15:30:00',
                                          'end' => '2007-09-17 17:00:00',
                                          'title' => 'Distillation, absorption and extraction',
                                          'url' => '',
                                          'color' => '',
                                          ),
                                   6 => 
                                   array (
                                          'start' => '2007-09-17 17:00:00',
                                          'end' => '2007-09-17 19:30:00',
                                          'title' => 'Environmental engineering & management',
                                          'url' => '',
                                          'color' => '',
                                          ),
                                   7 => 
                                   array (
                                          'start' => '2007-09-17 17:30:00',
                                          'end' => '2007-09-17 19:30:00',
                                          'title' => 'Test poster cases with thermo',
                                          'url' => '',
                                          'color' => '',
                                          ),
                                   8 => 
                                   array (
                                          'start' => '2007-09-17 17:30:00',
                                          'end' => '2007-09-17 19:30:00',
                                          'title' => 'Crystallization',
                                          'url' => '',
                                          'color' => '',
                                          ),
                                   ) ;
        $res = file_get_contents(dirname(__FILE__).'/simplecalendar6.html');
        $this->assertEquals($res, $calendar->render());
    }


    public function testCalendar2()
    {
        $calendar = new Pluf_Calendar();
        $calendar->events = $this->events2;
        $calendar->opts['start-day'] = '2007-02-06';
        $calendar->opts['end-day'] = '2007-02-10';
        $res = file_get_contents(dirname(__FILE__).'/simplecalendar2.html');
        $this->assertEquals($res, $calendar->render());
    }

    public function testGetStartEndDays()
    {
        $calendar = new Pluf_Calendar();
        $calendar->events = $this->events;
        $this->assertEquals(array('2007-02-06', '2007-02-10'),
                            $calendar->getStartEndDays());
    }

    public function testGetIntervals()
    {
        $calendar = new Pluf_Calendar();
        $calendar->events = $this->events;
        $calendar->cleanEventList();
        $this->assertEquals(array('08:00', '08:30', '09:15', '09:25',
                                  '11:00', '11:45', '12:00', '13:15'),
                            $calendar->getTimeIntervals());
    }

    public function testGetEventRowSpanning()
    {
        $calendar = new Pluf_Calendar();
        $calendar->events = $this->events;
        $calendar->cleanEventList();
        $inters = $calendar->getTimeIntervals();
        $this->assertEquals(2, $calendar->getEventRowSpanning($this->events[0], $inters));
        $this->assertEquals(2, $calendar->getEventRowSpanning($this->events[1], $inters));
        $this->assertEquals(1, $calendar->getEventRowSpanning($this->events[2], $inters));
        $this->assertEquals(2, $calendar->getEventRowSpanning($this->events[3], $inters));
        $this->assertEquals(1, $calendar->getEventRowSpanning($this->events[4], $inters));
    }


}

?>