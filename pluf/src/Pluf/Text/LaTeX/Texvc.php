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
 * Very simple interface to the texvc program.
 *
 */
class Pluf_Text_LaTeX_Texvc
{
    public $tmp_dir = '/tmp'; 
    public $output_dir = '/tmp';
    public $texvc_path = '/usr/bin/texvc';
    public $encoding = 'utf-8';
    public $fragment = '';

    /**
     * Constructor.
     *
     * @param string Latex fragment.
     * @param array Configuration variables.
     */
    public function __construct($latex, $cfg=array())
    {
        foreach ($cfg as $key=>$val) {
            $this->$key = $val;
        }
        $this->fragment = $latex;
    }

    /**
     * @param string Return type 'md5', ('file')
     * @return file Path to the generated png file.
     */
	public function render($return='file') 
    {
        $cmd = sprintf('%s %s %s %s %s',
                       $this->texvc_path, // texvc binary
                       escapeshellarg($this->tmp_dir), 
                       escapeshellarg($this->output_dir),
                       escapeshellarg($this->fragment),
                       escapeshellarg($this->encoding));
        $out = exec($cmd);
        if (strlen($out) == 0) {
            throw new Exception('Unknown error in the LaTeX rendering.');
        }
        $code = substr($out, 0, 1);
        if (false !== strpos('SEF-', $code)) {
            $error = '';
            switch ($code) {
            case 'S':
                $error = 'syntax error'; break;
            case 'E':
                $error = 'lexing error'; break;
            case '-':
                $error = 'other error'; break;
            case 'F':
                $error = 'unknown function: '.substr($out, 1); break;
            }
            throw new Exception('Error in the LaTeX rendering: '.$error);
        }
        $md5 = md5($this->fragment);
        if (!file_exists($this->output_dir.'/'.$md5.'.png')) {
            throw new Exception(sprintf('Error: Output file not written (%s).', $md5.'.png'));
        }
        if ($return == 'file') {
            return $this->output_dir.'/'.$md5.'.png';
        } 
        return $md5;
	}
}
