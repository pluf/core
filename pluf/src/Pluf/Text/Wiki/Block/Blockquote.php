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
 * traite les signes de type blockquote
 */
class Pluf_Text_Wiki_Block_Blockquote extends Pluf_Text_Wiki_BlockRenderer
{
    public $_previousTag;
    public $_firstTagLen;
    public $_firstLine;

    function __construct($wr) {
        parent::__construct($wr);
        $this->type   = 'bq';
        $this->regexp = "/^(\>+)(.*)/";
    }

    function open() {
        $this->_previousTag = $this->_detectMatch[1];
        $this->_firstTagLen = strlen($this->_previousTag);
        $this->_firstLine = true;
        return str_repeat('<blockquote>',$this->_firstTagLen).'<p>';
   }

    function close() {
        return '</p>'.str_repeat('</blockquote>',strlen($this->_previousTag));
    }

    function getRenderedLine() {

        $d=strlen($this->_previousTag) - strlen($this->_detectMatch[1]);
        $str='';

        if( $d > 0 ){ // on remonte d'un cran dans la hierarchie...
            $str='</p>'.str_repeat('</blockquote>',$d).'<p>';
            $this->_previousTag=$this->_detectMatch[1];
        }
        elseif( $d < 0 ) { // un niveau de plus
            $this->_previousTag=$this->_detectMatch[1];
            $str='</p>'.str_repeat('<blockquote>',-$d).'<p>';
        }
        else {
            if($this->_firstLine)
                $this->_firstLine=false;
            else
                $str='<br />';
        }
        return $str.$this->_renderInlineTag($this->_detectMatch[2]);
    }
}