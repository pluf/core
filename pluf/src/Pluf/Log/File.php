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
 * Log to a file.
 *
 * This is the simplest logger. You can use it as a base to create
 * more complex loggers. The logger interface is really simple and use
 * some helper functions from the main <code>Pluf_Log</code> class.
 *
 * The only required static method of a log writer is
 * <code>write</code>, which takes the stack to write as parameter.
 *
 * The only configuration variable of the file writer is the path to
 * the log file 'pluf_log_file'. By default it creates a
 * <code>pluf.log</code> in the configured tmp folder.
 *
 */
class Pluf_Log_File
{
    /**
     * Flush the stack to the disk.
     *
     * @param $stack Array
     */
    public static function write($stack)
    {
        $file = Pluf::f('pluf_log_file', 
                        Pluf::f('tmp_folder', '/tmp').'/pluf.log');
        $out = array();
        foreach ($stack as $elt) {
            $out[] = date(DATE_ISO8601, (int) $elt[0]).' '.
                Pluf_Log::$reverse[$elt[1]].': '.
                json_encode($elt[2]);
        }
        file_put_contents($file, implode(PHP_EOL, $out).PHP_EOL, FILE_APPEND);
    }
}
