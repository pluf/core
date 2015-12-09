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
 * Implémente les propriétés d'un tag inline wiki et 
 * le fonctionnement pour la génération
 * du code html correspondant
 */

class Pluf_Text_Wiki_Tag {

    public $name;
    public $beginTag;
    public $endTag;
    public $useSeparator = true;
    public $attribute = array();
    public $builderFunction = null;
    public $contents = array();
    public $separatorCount = 0;
    public $isDummy = false;

    function __construct($name, $properties)
    {
        $this->name = $name;
        $this->beginTag = $properties[0];
        $this->endTag = $properties[1];
        if ($this->name == 'dummie')
            $this->isDummy = true;

        if (is_null($properties[2])) {
            $this->attribute = array();
            $this->useSeparator = false;
        }
        else {
            $this->attribute = $properties[2];
            $this->useSeparator = (count($this->attribute)>0);
        }
        $this->builderFunction = $properties[3];
    }

    function addContent($string, $escape=true)
    {
        if (!isset($this->contents[$this->separatorCount]))
            $this->contents[$this->separatorCount] = '';

        if ($escape) {
            $this->contents[$this->separatorCount] .= htmlspecialchars($string);
        } else {
            $this->contents[$this->separatorCount] .= $string;
        }
    }

    function addSeparator() 
    {
        $this->separatorCount++;
    }

    function getBeginTag() 
    {
        return $this->beginTag;
    }

    function getEndTag() 
    {
        return $this->endTag;
    }

    function getNumberSeparator() 
    {
        return $this->separatorCount;
    }

    function useSeparator() 
    {
        return $this->useSeparator;
    }

    function isDummy() 
    {
        return $this->isDummy;
    }

    function getHtmlContent() 
    {
        if (is_null($this->builderFunction)) {
            $attr = '';
            if ($this->useSeparator) {
                $cntattr = count($this->attribute);
                $count = ($this->separatorCount > $cntattr?$cntattr:$this->separatorCount);
                for ($i=1; $i<=$count; $i++) {
                    $attr .= ' '.$this->attribute[$i-1].'="'.$this->contents[$i].'"';
                }
            }
            if (isset($this->contents[0]))
                return '<'.$this->name.$attr.'>'.$this->contents[0].'</'.$this->name.'>';
            else
                return '<'.$this->name.$attr.' />';
        }
        else {
            $fct = $this->builderFunction;
            return $fct($this->contents, $this->attribute);
        }
    }

}
