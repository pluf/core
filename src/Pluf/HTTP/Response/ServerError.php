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
 * Error response
 */
class Pluf_HTTP_Response_ServerError extends Pluf_HTTP_Response {

    /**
     * یک نمونه جدید از این شئی ایجاد می‌کند
     *
     * در فرآیند ساخت تلاش می‌شو که الگویی برای خطای 500 بازیابی شده و به عنوان
     * نتیجه
     * برگردانده شود.
     * در صورتی که خطایی رخ دهد، یک متن پیش فرض به عنوان خطای نتیجه نمایش داده
     * خواهد شد.
     *
     * @param Pluf_HTTP_Request $request            
     */
    function __construct($exception, $mimetype = null, $request = null) {
        $admins = Pluf::f('admins', array());

        /*
         * ارسال رایانامه برای تمام مدیران سیستم
         */
        if (count($admins) > 0) {
            // FIXME: maso, 1394: Get a nice stack trace and send it by emails.
            $stack = json_encode($exception->getTrace());
            $subject = $exception->getMessage();
            $subject = substr(strip_tags(nl2br($subject)), 0, 50) . '...';

            // TODO: maso, 2018: send email in SIGNAL-EMITER form
            foreach ($admins as $admin) {
                $email = new Pluf_Mail($admin[1], $admin[1], $subject);
                $email->addTextMessage($stack);
                $email->sendMail();
            }
        }

        /*
         * ایجاد پیام مناسب برای کاربر
         */
        $mimetype = Pluf::f('mimetype_json', 'application/json') .
                '; charset=utf-8';
        if (!($exception instanceof Pluf_Exception)) {
            parent::__construct(json_encode(array(
                    'code' => 5000,
                    'status' => 500,
//                    'link' => $this->link,
                    'message' => $exception->getMessage(),
//                    'data' => $this->data,
//                    'developerMessage' => $this->developerMessage,
                    'stack' => Pluf::f('debug', false)? $exception->getTrace() : array()
            )), $mimetype);
            $this->status_code = 500;
        } else {
            parent::__construct(json_encode($exception), $mimetype);
            $this->status_code = $exception->getStatus();
        }
        return;
    }

}
