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

class Pluf_HTTP_Response_File extends Pluf_HTTP_Response
{
    public $delete_file = false;

    function __construct($filepath, $mimetype=null, $delete_file=false)
    {
        parent::__construct($filepath, $mimetype);
        $this->delete_file = $delete_file;
    }

    /**
     * Render a response object.
     */
    function render($output_body=true)
    {
        $this->headers['Content-Length'] = (string) filesize($this->content);
        $this->outputHeaders();
        if ($output_body) {
            $fp = fopen($this->content, 'rb');
            while(!feof($fp)) {
                $buffer = fread($fp, 2048);
                echo $buffer;
            }
            fclose($fp);
        }
        if ($this->delete_file) {
            @unlink($this->content);
        }
    }
}
