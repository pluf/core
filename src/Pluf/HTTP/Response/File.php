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

class Pluf_HTTP_Response_File extends Pluf_HTTP_Response
{

    public $delete_file = false;

    function __construct ($filepath, $mimetype = null, $delete_file = false)
    {
        parent::__construct($filepath, $mimetype);
        $this->delete_file = $delete_file;
    }

    /**
     * Render a response object.
     *
     * در صورتی که منبع مورد نظر وجود نداشته باشید خطای عدم وجود منبع تولید
     * خواهد شد.
     */
    function render ($output_body = true)
    {
        if (! file_exists($this->content)) {
            throw new Pluf_Exception_DoesNotExist('Requested resource not found');
        }
        $this->headers['Content-Length'] = (string) filesize($this->content);
        $this->outputHeaders();
        if ($output_body) {
            $fp = fopen($this->content, 'rb');
            while (! feof($fp)) {
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
