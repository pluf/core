<?php
/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. (http://dpq.co.ir)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * 
 * @deprecated
 */
class Pluf_HTTP_Response_Forbidden extends Pluf_HTTP_Response
{
    function __construct($request)
    {
        $content = '';
        try {
            $context = new Pluf_Template_Context(array('query' => $request->query));
            $tmpl = new Pluf_Template('403.html');
            $content = $tmpl->render($context);
            $mimetype = null;
        } catch (Exception $e) {
            $mimetype = 'text/plain';
            $content = 'You are not authorized to view this page. You do not have permission'."\n"
                .'to view the requested directory or page using the credentials supplied.'."\n\n".'403 - Forbidden';
        }
        parent::__construct($content, $mimetype);
        $this->status_code = 403;
    }
}