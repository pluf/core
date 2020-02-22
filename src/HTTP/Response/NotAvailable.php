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
class Pluf_HTTP_Response_NotAvailable extends Pluf_HTTP_Response
{
	/**
	 * یک نمونه جدید از این شئی ایجاد می‌کند
	 * 
	 * در فرآیند ساخت تلاش می‌شو که الگویی برای خطای 503 بازیابی شده و به عنوان نتیجه
	 * برگردانده شود.
	 * در صورتی که خطایی رخ دهد، یک متن پیش فرض به عنوان خطای نتیجه نمایش داده خواهد شد.
	 * 
	 * @param Pluf_HTTP_Request $request
	 */
    function __construct($request)
    {
        $content = '';
        try {
        	$tmpl = new Pluf_Template('503.html');
        	$params = array(
        			'query' => $request->query
        	);
        	if (is_null($request)) {
        		$context = new Pluf_Template_Context($params);
        	} else {
        		$context = new Pluf_Template_Context_Request($request, $params);
        	}
        	$content = $tmpl->render($context);
        	$mimetype = null;
        } catch (Exception $e) {
            $mimetype = 'text/plain';
            $content = sprintf('The requested URL %s is not available at the moment.'."\n"
                               .'Please try again later.'."\n\n".'503 - Service Unavailable',
                               Pluf_esc($request->query));
        }
        parent::__construct($content, $mimetype);
        $this->status_code = 503;
        $this->headers['Retry-After'] = 300; // retry after 5 minutes
    }
}
