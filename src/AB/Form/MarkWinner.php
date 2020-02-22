<?php
/* -*- tab-width: 4; indent-tabs-mode: nil; c-basic-offset: 4 -*- */
/*
# ***** BEGIN LICENSE BLOCK *****
# This file is part of Plume Framework, a simple PHP Application Framework.
# Copyright (C) 2001-2010 Loic d'Anterroches and contributors.
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
 * Mark the winner of a test.
 *
 * This form is not used to display the form, only to validate and
 * process it.
 *
 */
class Pluf_AB_Form_MarkWinner extends Pluf_Form
{
    protected $test = null; /**< Store the test retrieved during validation. */

    public function initFields($extra=array())
    {
        $this->fields['test'] = new \Pluf\Form\Field\Varchar(
                                        array('required' => true)
                                                            );
        $this->fields['alt'] = new Pluf_Form_Field_Integer(
                                      array('required' => true,
                                            'min' => 0,
                                            ));
    }

    /**
     * Validate that the test exists, is active and the corresponding
     * alternative exists too.
     *
     * The validation is at the global level to prevent the need of a
     * form per test and simplify the dashboard design.
     */
    public function clean()
    {
        $db = Pluf_AB::getDb();
        $test = $db->tests->findOne(array('_id' => $this->cleaned_data['test']));
        if ($test == null) {
            throw new Pluf_Form_Invalid(__('The test has not been found.'));
        }
        if (!$test['active']) {
            throw new Pluf_Form_Invalid(__('The test is already inactive.'));
        }
        if (!isset($test['alts'][$this->cleaned_data['alt']])) {
            throw new Pluf_Form_Invalid(__('This alternative is not available.'));
        }
        // Good we have the test and the right alternative
        $this->test = $test;
        return $this->cleaned_data;
    }

    /**
     * Save the test.
     *
     * @return array Test.
     */
    function save($commit=true)
    {
        $this->test['winner'] = $this->cleaned_data['alt'];
        $this->test['active'] = false;
        $this->test['stop_dtime'] = gmdate('Y-m-d H:i:s');
        $db = Pluf_AB::getDb();
        $db->tests->update(array('_id'=> $this->cleaned_data['test']), 
                           $this->test);
        return $this->test;
    }
}
