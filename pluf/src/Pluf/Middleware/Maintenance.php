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
 * Maintenance middleware.
 *
 * If a file MAINTENANCE exists in the temp folder then a maintenance
 * page is shown. If available, a template maintenance.html is used,
 * else a simple plain text 'Server in maintenance, please retry
 * later...' is shown.
 *
 * This middleware should be
 *
 * Only the actions starting with Pluf::f('maintenance_root') are not
 * interrupted, that way you can access a special url to perform
 * upgrade.
 *
 */
class Pluf_Middleware_Maintenance
{

    /**
     * Process the request.
     *
     * @param Pluf_HTTP_Request The request
     * @return bool false
     */
    function process_request(&$request)
    {
        if (0 !== strpos($request->query, Pluf::f('maintenance_root')) && file_exists(Pluf::f('tmp_folder').'/MAINTENANCE')) {
            $res = new Pluf_HTTP_Response('Server in maintenance'."\n\n".'We are upgrading the system to make it better for you, please try again later...', 'text/plain');
            $res->status_code = 503;
            return $res;
        }
        return false;
    }
}
