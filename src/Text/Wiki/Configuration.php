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
 * Default Wiki Renderer configuration.
 */
class Pluf_Text_Wiki_Configuration
{
    /**
     * @var array   liste des tags inline
     */
    public $inlinetags = array(
         'strong' => array('__','__',      
                           null,null),
         'em'     => array('\'\'','\'\'',  
                           null,null),
         'code'   => array('@@','@@',      
                           null,null),
         'q'      => array('^^','^^',      
                           array('lang','cite'), null),
         'cite'   => array('{{','}}',      
                           array('title'), null),
         'acronym'=> array('??','??',      
                           array('title'), null),
         'link'   => array('[',']',        
                           array('href','hreflang','title'), 
                           'Pluf_Text_Wiki_Configuration_buildlink'),
         'image'  => array('((','))',      
                           array('src','alt','align','longdesc'),
                           'Pluf_Text_Wiki_Configuration_buildimage'),
         'anchor' => array('~~','~~',      
                           array('name'),
                           'Pluf_Text_Wiki_Configuration_buildanchor')
         );

    /**
     * liste des balises de type bloc autorisées.  Attention, ordre
     * important (p en dernier, car c'est le bloc par defaut..)
     */

    public $bloctags = array(
        'Pluf_Text_Wiki_Block_Title' => true,
        'Pluf_Text_Wiki_Block_List' => true,
        'Pluf_Text_Wiki_Block_Pre' => true,
        'Pluf_Text_Wiki_Block_Hr' => true,
        'Pluf_Text_Wiki_Block_Blockquote' => true,
        'Pluf_Text_Wiki_Block_Definition' => true,
        'Pluf_Text_Wiki_Block_Table' => true,
        'Pluf_Text_Wiki_Block_P' => true
    );


    public $simpletags = array('%%%'=>'<br />', ':-)'=>'<img src="laugh.png" alt=":-)" />');

    /**
     * @var   integer   niveau minimum pour les balises titres
     */

    public $minHeaderLevel=3;


    /**
     * indique le sens dans lequel il faut interpreter le nombre de
     * signe de titre
     *
     * true -> ! = titre , !! = sous titre, !!! = sous-sous-titre
     * false-> !!! = titre , !! = sous titre, ! = sous-sous-titre
     */

    public $headerOrder=false;
    public $escapeSpecialChars=true;
    public $inlineTagSeparator='|';
    public $blocAttributeTag='°°';

    public $checkWikiWord = false;
    public $checkWikiWordFunction = null;

}

// ===================================== 
// fonctions de générateur de
// code HTML spécifiques à certaines balises inlines
/**
 * Generate a link.
 *
 * If the configuration variable 'wiki_create_action' is set to true and
 * the URL starts with '/' and does not contains a dot '.' an action is
 * created out of it, with 'app_base' as the base url.
 */
function Pluf_Text_Wiki_Configuration_buildlink($contents, $attr)
{
    $cnt = count($contents);
    $attribut = '';
    if ($cnt == 0) return '[]';
    if ($cnt == 1) {
        $contents[1] = $contents[0];
        if (strlen($contents[0]) > 40) {
            $contents[0] = substr($contents[0], 0, 40).'(..)';
        }
        $cnt = 2;
    }
    if ($cnt > count($attr)) {
        $cnt = count($attr)+1;
    }
    if (strpos($contents[1], 'javascript:') !== false) {
        // for security reason
        $contents[1] = '#';
    }
    if ('/' == $contents[1]{0} and false === strpos($contents[1], '.')) {
        if (true === Pluf::f('wiki_create_action')) {
            $murl = new Pluf_HTTP_URL();
            $contents[1] = Pluf::f('app_base').$murl->generate($contents[1]);
        }
    }
    for ($i=1; $i<$cnt; $i++) {
        $attribut .= ' '.$attr[$i-1].'="'.$contents[$i].'"';
    }
    return '<a'.$attribut.'>'.$contents[0].'</a>';
}

function Pluf_Text_Wiki_Configuration_buildanchor($contents, $attr)
{
   return '<a name="'.$contents[0].'"></a>';
}

function Pluf_Text_Wiki_Configuration_builddummie($contents, $attr)
{
   return (isset($contents[0])?$contents[0]:'');
}

function Pluf_Text_Wiki_Configuration_buildimage($contents, $attr)
{
   $cnt=count($contents);
   $attribut='';
   if($cnt > 4) $cnt=4;
   switch($cnt){
      case 4:
         $attribut.=' longdesc="'.$contents[3].'"';
      case 3:
         if($contents[2]=='l' ||$contents[2]=='L' || $contents[2]=='g' || $contents[2]=='G')
            $attribut.=' style="float:left;"';
         elseif($contents[2]=='r' ||$contents[2]=='R' || $contents[2]=='d' || $contents[2]=='D')
            $attribut.=' style="float:right;"';
      case 2:
         $attribut.=' alt="'.$contents[1].'"';
      case 1:
      default:
         $attribut.=' src="'.$contents[0].'"';
         if($cnt == 1) $attribut.=' alt=""';
   }
   return '<img'.$attribut.' />';

}
