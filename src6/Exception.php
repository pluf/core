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
namespace Pluf;

use Pluf;

/**
 * Pluf root exception type
 *
 * All pluf application exceptions are subclass of the \Pluf\Exception. If any exeption throw
 * which is not subclass of it, the framework will consider as non expected exception.
 *
 *
 * @author Mostafa Barmshory<mostafa.barmshory@dpq.co.ir>
 * @since Pluf6 
 *        
 */
class Exception extends \Exception implements \JsonSerializable
{

    protected $status;

    protected $link;

    protected $developerMessage;

    protected $data;

    /**
     * یک نمونه از این کلاس ایجاد می‌کند.
     *
     * @param string $message
     * @param string $code
     * @param string $previous
     */
    public function __construct($message = "Unknown exception", $code = 5000, $previous = null, $status = 500, $link = null, $developerMessage = null)
    {
        parent::__construct($message, $code, $previous);
        $this->status = $status;
        $this->link = $link;
        $this->developerMessage = $developerMessage;
    }

    public function getDeveloperMessage()
    {
        return $this->developerMessage;
    }

    public function setDeveloperMessage($message)
    {
        $this->developerMessage = $message;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function jsonSerialize()
    {
        if (Pluf::f('debug', false)) {
            return array(
                'code' => $this->code,
                'status' => $this->status,
                'link' => $this->link,
                'message' => $this->message,
                'data' => $this->data,
                'developerMessage' => $this->developerMessage,
                'stack' => $this->getTrace()
            );
        } else {
            return array(
                'code' => $this->code,
                'status' => $this->status,
                'link' => $this->link,
                'message' => $this->message,
                'data' => $this->data
            );
        }
    }
}



