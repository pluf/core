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
 * Special response object to output the return value of a command.
 *
 * You need to use escapeshellarg() and escapeshellcmd() to provide a
 * "clean" command. The Content-Length will not be set as it is not
 * possible to predict it.
 */
class Pluf_HTTP_Response_CommandPassThru extends Pluf_HTTP_Response
{
    /**
     * The command argument must be a safe string!
     *
     * @param string Command to run.
     * @param string Mimetype (null)
     */
    function __construct($command, $mimetype=null)
    {
        parent::__construct($command, $mimetype);
    }

    /**
     * Render a response object.
     */
    function render($output_body=true)
    {
        $this->outputHeaders();
        if ($output_body) {
            passthru($this->content);
        }
    }
}
