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
 * Render file as response
 *
 * @author maso<mostafa.barmshory@gmail.com>
 *        
 */
class Pluf_HTTP_Response_File extends Pluf_HTTP_Response
{

    public $delete_file = false;

    /**
     * Creates new instance of File response
     *
     * @param string $filepath
     * @param string $mimetype
     * @param boolean $delete_file
     */
    function __construct($filepath, $mimetype = null, $delete_file = false)
    {
        parent::__construct($filepath, $mimetype);
        $this->delete_file = $delete_file;
    }

    /**
     * Render the file
     *
     * {@inheritdoc}
     * @see Pluf_HTTP_Response::render()
     */
    function render($output_body = true)
    {
        if (! file_exists($this->content)) {
            throw new Pluf_Exception_DoesNotExist('Requested resource not found');
        }
        if (defined('IN_UNIT_TESTS')) {
            parent::render($output_body);
            return;
        }
        $dl = new \Pluf\HTTP\Download2(array(
            'file' => $this->content,
            'contenttype' => $this->headers['Content-Type'],
            'gzip' => false,
            'cache' => true
        ));
        foreach ($this->headers as $key => $value){            
            $dl->headers[$key] = $value;
        }
        $dl->send(false);
    }

    /**
     * Genereate hash code of file
     *
     * {@inheritdoc}
     * @see Pluf_HTTP_Response::hashCode()
     */
    public function hashCode()
    {
        if (isset($this->content) && file_exists($this->content)) {
            if (! isset($this->contentHash)) {
                $this->contentHash = md5_file($this->content);
            }
            return $this->contentHash;
        }
        return '0000';
    }
}
