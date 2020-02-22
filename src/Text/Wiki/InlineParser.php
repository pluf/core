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
 * Moteur permettant de transformer les tags wiki inline 
 * d'une chaine en équivalent HTML
 */
class Pluf_Text_Wiki_InlineParser {

    public $resultline = '';
    public $error = false;
    public $listTag = array();
    public $str = array();
    public $splitPattern = '';
    public $checkWikiWord = false;
    public $checkWikiWordFunction = null;
    public $simpletags = null;
    public $_separator;
    public $escapeHtml = true;
    public $end = 0;

    /**
     * constructeur
     * @param array $inlinetags liste des tags permis
     * @param string caractère séparateur des différents composants 
     *               d'un tag wiki
     */

    function __construct($inlinetags, $simpletags, $separator='|',
                         $checkWikiWord=false, $funcCheckWikiWord=null,
                         $escapeHtml=true)
    {
        foreach ($inlinetags as $name=>$prop){
            $this->listTag[$prop[0]] = new Pluf_Text_Wiki_Tag($name, $prop);

            $this->splitPattern.=preg_replace ( '/([^\w\s\d])/', '\\\\\\1',$prop[0]).')|(';
            if ($prop[1] != $prop[0])
                $this->splitPattern.=preg_replace ( '/([^\w\s\d])/', '\\\\\\1',$prop[1]).')|(';
        }
        foreach ($simpletags as $tag=>$html){
            $this->splitPattern.=preg_replace ( '/([^\w\s\d])/', '\\\\\\1',$tag).')|(';
        }

        $this->simpletags = $simpletags;
        $this->_separator = $separator;
        $this->checkWikiWord = $checkWikiWord;
        $this->checkWikiWordFunction = $funcCheckWikiWord;
        $this->escapeHtml = $escapeHtml;
    }

    /**
     * fonction principale du parser.
     * @param   string   $line avec des eventuels tag wiki
     * @return  string   chaine $line avec les tags wiki transformé en HTML
     */
    function parse($line) {
        $this->error = false;

        $this->str = preg_split('/('.$this->splitPattern.'\\'.$this->_separator.')|(\\\\)/',$line, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        $this->end = count($this->str);
        if ($this->end > 1) {
            $firsttag = new Pluf_Text_Wiki_Tag('dummie',array('','', null,'Pluf_Text_Wiki_Configuration_builddummie'));
            $pos=-1;
            return $this->_parse($firsttag, $pos);
        }
        else {
            if ($this->escapeHtml) {
                if ($this->checkWikiWord && $this->checkWikiWordFunction !== null)
                    return  $this->_doCheckWikiWord(htmlspecialchars($line));
                else
                    return htmlspecialchars($line);
            }
            else {
                if ($this->checkWikiWord && $this->checkWikiWordFunction !== null)
                    return  $this->_doCheckWikiWord($line);
                else
                    return $line;
            }

        }
    }

    /**
     * coeur du parseur. Appelé récursivement
     */

    function _parse($tag, &$posstart) {

        $checkNextTag = true;
        $checkBeginTag = true;

        // on parcours la chaine,  morceau aprés morceau
        for($i=$posstart+1; $i < $this->end; $i++) {

            $t=&$this->str[$i];
            // a t-on un antislash ?
            if ($t=='\\'){
                if ($checkNextTag){
                    $t=''; // oui -> on l'efface et on ignore le tag (on continue)
                    $checkNextTag = false;
                }
                else {
                    $tag->addContent('\\',false);
                }

                // est-ce un séparateur ?
            }
            elseif ($t == $this->_separator) {
                if ($tag->isDummy() || !$checkNextTag)
                    $tag->addContent($this->_separator,false);
                elseif ($tag->useSeparator()) {
                    $checkBeginTag = false;
                    $tag->addSeparator();
                }
                else {
                    $tag->addContent($this->_separator,false);
                }
                // a-t-on une balise de fin du tag ?
            }
            elseif ($checkNextTag && $tag->getEndTag() == $t && !$tag->isDummy()) {
                $posstart = $i;
                return $tag->getHtmlContent();

                // a-t-on une balise de debut de tag quelconque ?
            }
            elseif ($checkBeginTag && $checkNextTag && isset($this->listTag[$t]) ) {

                $content  =  $this->_parse(clone($this->listTag[$t]),$i); // clone indispensable sinon plantage !!!
                if ($content)
                    $tag->addContent($content,false);
                else {
                    if ($tag->getNumberSeparator() == 0 && $this->checkWikiWord && $this->checkWikiWordFunction !== null) {
                        if ($this->escapeHtml)
                            $tag->addContent($this->_doCheckWikiWord(htmlspecialchars($t)),false);
                        else
                            $tag->addContent($this->_doCheckWikiWord($t),false);
                    }
                    else
                        $tag->addContent($t,$this->escapeHtml);
                }

                // a-t-on un saut de ligne forcé ?
            }
            elseif ($checkNextTag && $checkBeginTag && isset($this->simpletags[$t])) {
                $tag->addContent($this->simpletags[$t],false);
            }
            else {
                if ($tag->getNumberSeparator() == 0 && $this->checkWikiWord && $this->checkWikiWordFunction !== null) {
                    if ($this->escapeHtml)
                        $tag->addContent($this->_doCheckWikiWord(htmlspecialchars($t)),false);
                    else
                        $tag->addContent($this->_doCheckWikiWord($t),false);
                }
                else
                    $tag->addContent($t,$this->escapeHtml);
                $checkNextTag = true;
            }
        }
        if (!$tag->isDummy()) {
            //--- on n'a pas trouvé le tag de fin
            // on met en erreur
            $this->error = true;
            return false;
        }
        else
            return $tag->getHtmlContent();
    }

    function _doCheckWikiWord($string) {
        if (preg_match_all("/(?<=\b)[A-Z][a-z]+[A-Z0-9]\w*/", $string, $matches)){
            $fct = $this->checkWikiWordFunction;
            $match = array_unique($matches[0]); // il faut avoir une liste sans doublon, à cause du str_replace plus loin...
            $string = str_replace($match, $fct($match), $string);
        }
        return $string;
    }

    function getError() {
        return $this->error;
    }

}
