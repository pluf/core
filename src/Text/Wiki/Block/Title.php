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
 * traite les signes de type titre
 */
class Pluf_Text_Wiki_Block_Title extends Pluf_Text_Wiki_BlockRenderer
{
    public $_minlevel = 1;
    public $_order = false;

    function __construct($wr)
    {
        parent::__construct($wr);
        $this->type      = 'title';
        $this->regexp    = "/^(\!{1,3})(.*)/";
        $this->_closeNow  = true;
        $cfg = $wr->getConfig();
        $this->_minlevel = $cfg->minHeaderLevel;
        $this->_order    = $cfg->headerOrder;
    }

    function getRenderedLine() 
    {
        if($this->_order)
            $hx= $this->_minlevel + strlen($this->_detectMatch[1])-1;
        else
            $hx= $this->_minlevel + 3-strlen($this->_detectMatch[1]);
        return '<h'.$hx.'>'.$this->_renderInlineTag($this->_detectMatch[2]).'</h'.$hx.'>';
    }
}
