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
 * Log to a server via a POST request.
 *
 * Fire a POST request agains a server with the payload being the
 * content of the log. The log is serialized as JSON. It is always
 * containing the current stack, so an array of log "lines".
 *
 * The configuration keys are:
 *
 * - 'log_remote_server' (localhost)
 * - 'log_remote_path' (/)
 * - 'log_remote_port' (8000)
 * - 'log_remote_headers' (array())
 *
 */
class Pluf_Log_Remote
{
    /**
     * Flush the stack to the remote server.
     *
     * @param $stack Array
     */
    public static function write($stack)
    {
        $payload = json_encode($stack);
        $out = 'POST '.Pluf::f('log_remote_path', '/').' HTTP/1.1'."\r\n";
        $out.= 'Host: '.Pluf::f('log_remote_server', 'localhost')."\r\n";
        $out.= 'Host: localhost'."\r\n";
        $out.= 'Content-Length: '.strlen($payload)."\r\n";
        foreach (Pluf::f('log_remote_headers', array()) as $key=>$val) {
            $out .= $key.': '.$val."\r\n";
        }
        $out.= 'Connection: Close'."\r\n\r\n";
        $out.= $payload;
        $fp = fsockopen(Pluf::f('log_remote_server', 'localhost'),
                        Pluf::f('log_remote_port', 8000),
                        $errno, $errstr, 5);
        fwrite($fp, $out);
        fclose($fp);
    }
}
