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
class Pluf_HTTP_Response_NotFound extends Pluf_HTTP_Response {
	/**
	 * یک نمونه جدید از این شئی ایجاد می‌کند
	 *
	 * در فرآیند ساخت تلاش می‌شو که الگویی برای خطای 404 بازیابی شده و به عنوان نتیجه
	 * برگردانده شود.
	 * در صورتی که خطایی رخ دهد، یک متن پیش فرض به عنوان خطای نتیجه نمایش داده خواهد شد.
	 *
	 * @param unknown $request        	
	 */
	function __construct($request) {
		if (Pluf::f ( 'rest', false )) {
			$mimetype = Pluf::f ( 'mimetype_json', 'application/json' ) . '; charset=utf-8';
			$exception = new Pluf_HTTP_Error404 ();
			parent::__construct ( json_encode ( $exception ), $mimetype );
			$this->status_code = 404;
			return;
		}
		
		$content = '';
		try {
			$tmpl = new Pluf_Template ( '404.html' );
			$params = array (
					'query' => $request->query 
			);
			if (is_null ( $request )) {
				$context = new Pluf_Template_Context ( $params );
			} else {
				$context = new Pluf_Template_Context_Request ( $request, $params );
			}
			$content = $tmpl->render ( $context );
			$mimetype = null;
		} catch ( Exception $e ) {
			$mimetype = 'text/plain';
			$content = sprintf ( 'The requested URL %s was not found on this server.' . "\n" . 'Please check the URL and try again.' . "\n\n" . '404 - Not Found', Pluf_esc ( $request->query ) );
		}
		parent::__construct ( $content, $mimetype );
		$this->status_code = 404;
	}
}
