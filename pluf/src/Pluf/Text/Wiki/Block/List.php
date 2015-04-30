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
 * traite les signes de type list
 */
class Pluf_Text_Wiki_Block_List extends Pluf_Text_Wiki_BlockRenderer
{
    public $_previousTag;
    public $_firstItem;
    public $_firstTagLen;

    function __construct($wr) 
    {
        parent::__construct($wr);
        $this->type   = 'list';
        $this->regexp = "/^([\*#-]+)(.*)/";
    }

    function open() 
    {
        $this->_previousTag = $this->_detectMatch[1];
        $this->_firstTagLen = strlen($this->_previousTag);
        $this->_firstItem=true;

        if(substr($this->_previousTag,-1,1) == '#')
            return "<ol>\n";
        else
            return "<ul>\n";
    }

    function close()
    {
        $t=$this->_previousTag;
        $str='';

        for($i=strlen($t); $i >= $this->_firstTagLen; $i--) {
            $str.=($t{$i-1}== '#'?"</li></ol>\n":"</li></ul>\n");
        }
        return $str;
    }

    function getRenderedLine()
    {
        $t=$this->_previousTag;
        $d=strlen($t) - strlen($this->_detectMatch[1]);
        $str='';

        if( $d > 0 ){ // on remonte d'un ou plusieurs cran dans la hierarchie...
            $l=strlen($this->_detectMatch[1]);
            for($i=strlen($t); $i>$l; $i--){
                $str.=($t{$i-1}== '#'?"</li></ol>\n":"</li></ul>\n");
            }
            $str.="</li>\n<li>";
            $this->_previousTag=substr($this->_previousTag,0,-$d); // pour être sur...

        }
        elseif( $d < 0 ) { // un niveau de plus
            $c=substr($this->_detectMatch[1],-1,1);
            $this->_previousTag.=$c;
            $str=($c == '#'?"<ol>\n<li>":"<ul>\n<li>");
        }
        else {
            $str=($this->_firstItem ? '<li>':'</li><li>');
        }
        $this->_firstItem=false;
        return $str.$this->_renderInlineTag($this->_detectMatch[2]);
    }

}
