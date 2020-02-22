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
namespace Pluf\HTTP\Response;

use Pluf\HTTP\Request;
use Pluf\Bootstrap;
use Pluf\Exception;

/**
 * Error response
 */
class ServerError extends \Pluf\HTTP\Response
{

    /**
     * یک نمونه جدید از این شئی ایجاد می‌کند
     *
     * در فرآیند ساخت تلاش می‌شو که الگویی برای خطای 500 بازیابی شده و به عنوان
     * نتیجه
     * برگردانده شود.
     * در صورتی که خطایی رخ دهد، یک متن پیش فرض به عنوان خطای نتیجه نمایش داده
     * خواهد شد.
     *
     * @param Request $request
     */
    function __construct($exception, $mimetype = null, $request = null)
    {
        /*
         * ایجاد پیام مناسب برای کاربر
         */
        $mimetype = Bootstrap::f('mimetype_json', 'application/json') . '; charset=utf-8';
        if (! ($exception instanceof Exception)) {
            parent::__construct(json_encode(array(
                'code' => 5000,
                'status' => 500,
                // 'link' => $this->link,
                'message' => $exception->getMessage(),
                // 'data' => $this->data,
                // 'developerMessage' => $this->developerMessage,
                'stack' => Bootstrap::f('debug', false) ? $exception->getTrace() : array()
            )), $mimetype);
            $this->status_code = 500;
        } else {
            parent::__construct(json_encode($exception), $mimetype);
            $this->status_code = $exception->getStatus();
        }
        return;
    }
}
