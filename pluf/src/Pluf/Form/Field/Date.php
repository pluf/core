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

class Pluf_Form_Field_Date extends Pluf_Form_Field
{
    public $widget = 'Pluf_Form_Widget_TextInput';
    public $input_formats = array(
       '%Y-%m-%d', '%m/%d/%Y', '%m/%d/%y', // 2006-10-25, 10/25/2006, 10/25/06
       '%b %d %Y', '%b %d, %Y',      // 'Oct 25 2006', 'Oct 25, 2006'
       '%d %b %Y', '%d %b, %Y',      // '25 Oct 2006', '25 Oct, 2006'
       '%B %d %Y', '%B %d, %Y',      // 'October 25 2006', 'October 25, 2006'
       '%d %B %Y', '%d %B, %Y',      // '25 October 2006', '25 October, 2006'
                                  );

    public function clean($value)
    {
        parent::clean($value);
        if (in_array($value, $this->empty_values)) {
            return '';
        }
        foreach ($this->input_formats as $format) {
            if (false !== ($date = strptime($value, $format))) {
                $day   = $date['tm_mday'];
                $month = $date['tm_mon'] + 1;
                $year  = $date['tm_year'] + 1900;
                if (checkdate($month, $day, $year)) {
                    return str_pad($year,  4, '0', STR_PAD_LEFT).'-'.
                           str_pad($month, 2, '0', STR_PAD_LEFT).'-'.
                           str_pad($day,   2, '0', STR_PAD_LEFT);
                }
            }
        }
        throw new Pluf_Form_Invalid(__('Enter a valid date.'));
    }
}
