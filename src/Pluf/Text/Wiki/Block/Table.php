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

/*
 * Based on Wiki Renderer copyright (C) 2003-2004 Laurent Jouanneau
 * http://ljouanneau.com/softs/wikirenderer/
 */

/**
 * traite les signes de type table
 */
class Pluf_Text_Wiki_Block_Table extends Pluf_Text_Wiki_BlockRenderer
{
    public $_colcount = 0;

    function __construct($wr) 
    {
        parent::__construct($wr);
        $this->type      = 'table';
        $this->regexp    = "/^\| ?(.*)/";
        $this->_openTag  = '<table border="1">';
        $this->_closeTag = '</table>';
    }

    function open() 
    {
        $this->_colcount=0;
        return $this->_openTag;
    }

    function getRenderedLine() 
    {
        $result=explode(' | ',trim($this->_detectMatch[1]));
        $str='';
        $t='';
        if((count($result) != $this->_colcount) && ($this->_colcount!=0))
            $t='</table><table border="1">';
            $this->_colcount=count($result);

        for($i=0; $i < $this->_colcount; $i++) {
            $str.='<td>'. $this->_renderInlineTag($result[$i]).'</td>';
        }
        $str=$t.'<tr>'.$str.'</tr>';

        return $str;
    }

}
