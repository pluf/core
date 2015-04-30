<?php
/* -*- tab-width: 4; indent-tabs-mode: nil; c-basic-offset: 4 -*- */
/*
# ***** BEGIN LICENSE BLOCK *****
# This file is part of Plume CMS, a website management application.
# Copyright (C) 2001-2007 Loic d'Anterroches and contributors.
#
# Plume CMS is free software; you can redistribute it and/or modify
# it under the terms of the GNU Lesser General Public License as published by
# the Free Software Foundation; either version 2.1 of the License, or
# (at your option) any later version.
#
# Plume CMS is distributed in the hope that it will be useful,
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
 * Error handling class.
 *
 * @credits Basis idea from Olivier Meunier
 */
class Pluf_Error
{
    private $error = array(); /**< Current errors. */


    /**
     * Reset the errors
     */
    function resetError()
    {
        $this->error = array();
    }

    /**
     * Set an error.
     *
     * By convention the number is 4xx if the error is coming from
     * the user or 5xx if coming from the system (database error for ex.)
     *
     * @param string Error message
     * @param int Error number (0)
     */
    function setError($msg, $no=0)
    {
        $this->error[] = array($no,$msg);
    }

    
    /**
     * Returns the errors.
     *
     * @param bool Errors as HTML list (false)
     * @param bool Show numbers (true)
     * @return mixed array of errors, HTML list, or false if no errors
     */
    function error($html=false, $with_nb=true)
    {
        if (count($this->error) > 0) {
            if (!$html) {
                return $this->error;
            } else {
                $res = '<ul>'."\n";
                foreach($this->error as $v) {
                    $res .= '<li>'.
                        (($with_nb) ? 
                         '<span class="errno">'.$v[0].'</span> - ' : 
                         '').
                        '<span class="errmsg">'.$v[1].'</span></li>'."\n";
                }
                return $res."</ul>\n";
            }
        } else {
            return false;
        }
    }

    /**
     * Helper function to set the error from the DB.
     *
     * @param string Error message from the DB
     */
    function setDbError($db_error_msg)
    {
        $this->setError(__('DB error:').' '.$db_error_msg, 500);
    }

    /**
     * Bulk set errors.
     *
     * Used when you went to recopy the errors of one object into
     * another. You can call that way:
     * $object->bulkSetErrors($otherobject->error());
     *
     * @param array List of errors
     * @return bool Success
     */
    function bulkSetError($errors)
    {
        if (!is_array($errors)) {
            return false;
        }
        foreach ($errors as $e) {
            $this->setError($e[1], $e[0]);
        }
        return true;
    }

}
