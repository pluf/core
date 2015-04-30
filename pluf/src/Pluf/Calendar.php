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
 * Calendar to display a list of events in a calendar table.
 *
 * The calendar is independent of other elements of Pluf, you can use
 * it standalone if you want.
 *
 * The principle is that you set options and feed the calendar with a
 * list of events. Based on the options, the render() method will
 * produce different views of the calendar.
 */
class Pluf_Calendar
{
    /**
     * The list of events to display.
     */
    var $events = array();
    var $summary = '';

    /**
     * The display options of the calendar.
     */
    var $opts = array();

    // When updating an interval, if a col span more rows and columns,
    // store the info for the next rows to compensate as needed.
    var $bspans = array();

    /**
     * List of events without the events not between the start/end
     * days.
     */
    var $_events = array();

    /**
     * List of time intervals in the $_events list.
     */
    var $_time_intervals = array();

    /**
     * Simultaneous events at a given time slot, for a given group.
     *
     * array('2007-03-25' => 
     *       array(array('time' => '10:15',
     *                   'start' => 4 , 
     *                   'continued' => 5),
     *             array('time' => '11:30',
     *                   'start' => 3 , 
     *                   'continued' => 0),
     *             )
     *       '2007-03-24' =>
     *       array(array('time' => '11:30',
     *                   'start' => 2 , 
     *                   'continued' => 3),
     *            )
     *       )
     *
     */
    var $_simultaneous = array();
    var $_max_simultaneous = array();
    var $_groups = array();

    /**
     * Render the calendar based on the options.
     */
    public function render()
    {
        if (count($this->events) == 0) {
            return '';
        }
        $this->cleanEventList();
        $this->getTimeIntervals();
        $this->getSimultaneous();
        $this->getMaxSimultaneous();
        $s = '';
        if ($this->summary) {
            $s = 'summary="'.htmlspecialchars($this->summary).'" ';
        }
        $out = '<table '.$s.'cellspacing="0" class="px-calendar">'."\n";
        $out .= $this->getHead();
        $out .= $this->getBody();
        $out .= '</table>'."\n";
        return Pluf_Template_SafeString::markSafe($out);
    }

    /**
     * Event are grouped by day per default, you can group as you
     * want, just subclass this method. Groups are used to make
     * columns in the table with the headings.
     */
    function getEventGroup($event)
    {
        return substr($event['start'], 0, 10);
    }

    /**
     * Get all the available groups.
     */
    function getGroups()
    {
        if (count($this->_groups)) {
            return $this->_groups;
        }
        foreach ($this->_events as $event) {
            $group = $this->getEventGroup($event);
            if (!in_array($group, $this->_groups)) {
                $this->_groups[] = $group;
            }
        }
        return $this->_groups;
    }

    /**
     * Get the name of a group to print in the headers.
     */
    function getGroupName($group)
    {
        $dw = $this->daysOfWeek();
        $days = date('w', strtotime($group));
        return htmlspecialchars($dw[$days%7]);
    }

    /**
     * Generate the body of the calendar.
     */
    function getBody()
    {
        $out = '<tbody>'."\n";
        $inters = $this->getTimeIntervals();
        $groups = $this->getGroups();
        for ($i=0;$i<(count($inters)-1);$i++) {
            $out .= '<tr>'."\n";
            $out .= '  <th scope="row">'.$inters[$i].' - '.$inters[$i+1].'</th>'."\n";
            foreach ($groups as $group) {
                $out .= $this->getEventCell($group, $inters[$i]);
            }
            $out .= '</tr>'."\n";
        }
        $out .= '</tbody>'."\n";
        return $out;
    }


    /**
     * Get the value to print for the given cell
     *
     * @param string Current group
     * @param string Current interval
     * @return string Table cells
     */
    function getEventCell($group, $inter)
    {
        $out = '';
        $max = $this->getMaxSimultaneous();
        $fullspanevent = false;
        foreach ($this->_events as $event) {
            // Get the start time of the event
            $e_start = substr($event['start'], 11, 5);
            if ($e_start != $inter) {
                // If the event does not start at the current time,
                // skip it
                continue;
            }
            if ($group != $this->getEventGroup($event)) {
                // Check if full span even at this time interval
                if (!empty($event['fullspan'])) {
                    $fullspanevent = true;
                }
                continue;
            }
            // Find how many rows the event will span
            $extra = '';
            $content = '';
            if (!isset($event['content'])) $event['content'] = '';
            $row_span = $this->getEventRowSpanning($event, $this->_time_intervals);
            if ($row_span > 1) {
                $extra .= ' rowspan="'.$row_span.'"';
            } 
            if (!empty($event['fullspan'])) {
                $colspan = 0;
                foreach ($max as $_s) {
                    $colspan += $_s;
                }
                $extra .= ' colspan="'.$colspan.'"';
                $fullspanevent = true;
            }
            if (strlen($event['color']) > 0) {
                $extra .= ' style="background-color: '.$event['color'].';"';
            }
            if (strlen($event['content']) > 0) {
                $content .= $event['content'];
            } 
            if (strlen($event['url']) > 0) {
                $content .= '<a href="'.$event['url'].'">'.htmlspecialchars($event['title']).'</a>';
            } 
            if (strlen($event['content']) == 0 and strlen($event['url']) == 0) {
                $content .= htmlspecialchars($event['title']);
            }
            $out .= '  <td'.$extra.'>'.$content.'</td>'."\n";
        }
        if (!$fullspanevent) {
            $sim = null;
            foreach ($this->_simultaneous[$group] as $_sim) {
                if ($_sim['time'] == $inter) {
                    $sim = $_sim;
                    break;
                }
            }
            $diff = $max[$group] - ($sim['start'] + $sim['continued']);
            for ($k=0; $k<$diff; $k++) {
                $out .= '  <td class="empty">&nbsp;</td>'."\n";
            }
        }
        return $out;
    }

    /**
     * Get event spanning over the rows.
     *
     * @param array Event
     * @param array Intervals
     * @return int Spanning
     */
    function getEventRowSpanning($event, $inters)
    {
        $start = substr($event['start'], 11, 5);
        $end = substr($event['end'], 11, 5);
        $span = 1;
        foreach ($inters as $inter) {
            if ($inter < $end and $inter > $start) {
                $span++;
            }
        }
        return $span;
    }

    /**
     * Generate the head of the calendar.
     */
    function getHead()
    {
        $out = '<thead>'."\n".'<tr>'."\n".'  <th>&nbsp;</th>'."\n";
        // Print the groups.
        $groups = $this->getGroups();
        $max = $this->getMaxSimultaneous();
        foreach ($groups as $group) {
            if ($max[$group] > 1) {
                $span = ' colspan="'.$max[$group].'"';
            } else {
                $span = '';
            }
            $out .= '  <th scope="col"'.$span.'>'.$this->getGroupName($group).'</th>'."\n";
        }
        $out .= '</tr>'."\n".'</thead>'."\n";
        return $out;
    }

    /**
     * Get the rowspan for each day.
     */
    function getDaySpanning()
    {
        list($start, $end) = $this->getStartEndDays();
        $inters = $this->getTimeIntervals($start, $end);
        $n = $this->getDayNumber($start, $end);
        $inter_n = array_fill(0, count($inters), 0);
        $day_span = array_fill(0, $n+1, $inter_n);
        foreach ($this->events as $event) {
            // The event must be between $start and $end
            $e_dstart = substr($event['start'], 0, 10);
            $e_dend = substr($event['end'], 0, 10);
            if ($e_dend < $start or $e_dstart > $end) {
                continue;
            }
            
            $day = $this->getDayNumber($start, substr($event['end'], 0, 10));
            $e_start = substr($event['start'], 11, 5);
            $e_end = substr($event['end'], 11, 5);
            $i = 0;
            foreach ($inters as $inter) {
                if ($inter < $e_end and $inter >= $e_start) {
                    $day_span[$day][$i]++;
                }
                $i++;
            }
        }
        return $day_span;
    }

    /**
     * Get an array with the days of the week.
     */
    function daysOfWeek()
    {
        return array(
                     __('Sunday'),
                     __('Monday'),
                     __('Tuesday'),
                     __('Wednesday'),
                     __('Thursday'),
                     __('Friday'),
                     __('Saturday'),
                     );
    }

    /**
     * Get the number of days to list.
     *
     * @param string Start date
     * @param string End date
     * @return int Number of days
     */
    function getDayNumber($start, $end)
    {
        Pluf::loadFunction('Pluf_Date_Compare');
        $diff = Pluf_Date_Compare($start.' 00:00:00', $end.' 00:00:00');
        return (int) $diff/86400;
    }

    /**
     * Get the start and end dates based on the event list.
     *
     * @return array (start day, end day)
     */
    function getStartEndDays()
    {
        $start = '9999-12-31';
        $end = '0000-01-01';
        if (!isset($this->opts['start-day']) 
            or !isset($this->opts['end-day'])) {
            foreach ($this->events as $event) {
                $t_start = substr($event['start'], 0, 10);
                $t_end = substr($event['end'], 0, 10);
                if ($t_start < $start) {
                    $start = $t_start;
                }
                if ($t_end > $end) {
                    $end = $t_end;
                }
            }
        }
        if (isset($this->opts['start-day'])) {
            $start = $this->opts['start-day'];
        } else {
            $this->opts['start-day'] = $start;
        }
        if (isset($this->opts['end-day'])) {
            $end = $this->opts['end-day'];
        } else {
            $this->opts['end-day'] = $end;
        }
        return array($start, $end);
    }

    /**
     * Clean event list.
     */
    function cleanEventList()
    {
        list($start, $end) = $this->getStartEndDays();
        $this->_events = array();
        foreach ($this->events as $event) {
            $e_dstart = substr($event['start'], 0, 10);
            $e_dend = substr($event['end'], 0, 10);
            if ($e_dend < $start or $e_dstart > $end) {
                continue;
            }
            $this->_events[] = $event;
        }
        return true;
    }

    /**
     * Get the time intervals. They span all the groups.
     */
    function getTimeIntervals($start='', $end='')
    {
        if (count($this->_time_intervals)) {
            return $this->_time_intervals;
        }
        $intervals = array();
        foreach ($this->_events as $event) {
            $t = substr($event['start'], 11, 5);
            if (!in_array($t, $intervals)) {
                $intervals[] = $t;
            }
            $t = substr($event['end'], 11, 5);
            if (!in_array($t, $intervals)) {
                $intervals[] = $t;
            }
        }
        sort($intervals);
        $this->_time_intervals = $intervals;
        return $intervals;
    }

    /**
     * Get simultaneous events at the same time slot and same group.
     */
    function getSimultaneous()
    {
        foreach ($this->getGroups() as $group) {
            $this->_simultaneous[$group] = array();
            foreach ($this->_time_intervals as $inter) {
                $this->_simultaneous[$group][] = array('time' => $inter,
                                                       'start' => 0,
                                                       'continued' => 0);
            }
        }
        foreach ($this->_events as $event) {
            $group = $this->getEventGroup($event);
            $e_tstart = substr($event['start'], 11, 5);
            $e_tend = substr($event['end'], 11, 5);
            foreach ($this->_simultaneous[$group] as $index=>$inter) {
                if ($e_tstart == $inter['time']) {
                    $inter['start'] += 1;
                    $this->_simultaneous[$group][$index] = $inter;
                    continue;
                }
                if ($e_tstart < $inter['time'] and $e_tend > $inter['time']) {
                    $inter['continued'] += 1;
                    $this->_simultaneous[$group][$index] = $inter;
                    continue;
                }
            }
        }
        return $this->_simultaneous;
    }

    /**
     * Get maximum simultaneous events
     */
    function getMaxSimultaneous()
    {
        if (count($this->_max_simultaneous) > 0) {
            return $this->_max_simultaneous;
        }
        foreach ($this->getGroups() as $group) {
            $this->_max_simultaneous[$group] = 0;
        }
        foreach ($this->_simultaneous as $group=>$choices) {
            foreach ($choices as $count) {
                if ($this->_max_simultaneous[$group] < $count['start'] + $count['continued']) {
                    $this->_max_simultaneous[$group] = $count['start'] + $count['continued'];
                }
            }
        }
        return $this->_max_simultaneous;
    }


    /**
     * Overloading of the get method.
     *
     * @param string Property to get
     */
    function __get($prop)
    {
        if ($prop == 'render') return $this->render();
        return $this->$prop;
    }

}