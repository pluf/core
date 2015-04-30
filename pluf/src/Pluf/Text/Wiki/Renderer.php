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
 * Rendering engine. 
 *
 * Main class to convert a wiki text into XHTML.
 *
 * Usage:
 * <code>
 * $ctr = Pluf::factory('Pluf_Text_Wiki_Renderer');
 * $xhtml_text = $ctr->render($wiki_text);
 * </code>
 */

class Pluf_Text_Wiki_Renderer {

    /**
     * HTML version of analysed text.
     */
    private $_newtext;

    private $_isBlocOpen=false;

    /**
     * Current opened block element.
     */
    private $_currentBloc;

    /**
     * List of available blocks.
     */
    private $_blocList=array();

    /**
     * List of parameters for the engine.
     */
    public $params=array();

    /**
     * Inline parser for the Wiki Inline tags.
     */
    public $inlineParser=null;

    /**
     * List of lines with wiki errors.
     */
    public $errors;

    /**
     * Config object to customized the renderer.
     */
    public $config=null;

    /**
     * Constructor of the renderer.
     *
     * Create the needed objects for the rendering.
     *
     * @param Configuration Custom configuration object (null)
     */
    function __construct($config=null) 
    {
        if (is_null($config)) {
            $this->config = new Pluf_Text_Wiki_Configuration();
        } else {
            $this->config = $config;
        }
        // Gost block
        $this->_currentBloc = new Pluf_Text_Wiki_BlockRenderer($this); 
        $this->inlineParser = new Pluf_Text_Wiki_InlineParser(
                                      $this->config->inlinetags,
                                      $this->config->simpletags,
                                      $this->config->inlineTagSeparator,
                                      $this->config->checkWikiWord,
                                      $this->config->checkWikiWordFunction,
                                      $this->config->escapeSpecialChars
                                      );

        foreach ($this->config->bloctags as $name=>$ok) {
            if ($ok) {
                $this->_blocList[] = new $name($this);
            }
        }
    }

    /**
     * Main method to convert the wiki text into XHTML.
     *
     * @param string Text to be converted.
     * @return string Converted text into XHTML.
     */
    function render($text) 
    {
        // Split on \r (mac), \n (unix) or \r\n (windows)
        $lignes = preg_split("/\015\012|\015|\012/", $text);
        $this->_newtext = array();
        $this->_isBlocOpen = false;
        $this->errors = false;
        $this->_currentBloc = new Pluf_Text_Wiki_BlockRenderer($this);
        // Go through all the lines.
        foreach ($lignes as $num=>$ligne) {
            if ($ligne == '') { // pas de trim à cause des pre
                // ligne vide
                $this->_closeBloc();
            } else {
                // detection de debut de bloc (liste, tableau, hr, titre)
                foreach ($this->_blocList as $bloc) {
                    if ($bloc->detect($ligne)) {
                        break;
                    }
                }
                // c'est le debut d'un bloc (ou ligne d'un bloc en cours)
                if ($bloc->getType() != $this->_currentBloc->getType()) {
                    // on ferme le precedent si c'etait un different
                    $this->_closeBloc(); 
                    $this->_currentBloc = $bloc;
                    if($this->_openBloc()) {
                        $this->_newtext[] = $this->_currentBloc->getRenderedLine();
                    } else {
                        $this->_newtext[] = $this->_currentBloc->getRenderedLine();
                        $this->_newtext[] = $this->_currentBloc->close();
                        $this->_isBlocOpen = false;
                        $this->_currentBloc = new Pluf_Text_Wiki_BlockRenderer($this);
                    }
                } else {
                    $this->_currentBloc->setMatch($bloc->getMatch());
                    $this->_newtext[] = $this->_currentBloc->getRenderedLine();
                }
                if($this->inlineParser->getError()) {
                    $this->errors[$num+1] = $ligne;
                }
            }
        }
        $this->_closeBloc();
        return implode("\n", $this->_newtext);
    }

    /**
     * Returns configuration object.
     *
     * @return WikiRendererConfig
     */
    function getConfig() 
    {
        return $this->config;
    }

    /**
     * Retourne l'objet inlineParser (WikiInlineParser) utilisé dans le moteur
     * @access public
     * @see WikiInlineParser
     * @return WikiInlineParser
     */
    function getInlineParser() 
    {
        return $this->inlineParser;
    }

    /**
     * renvoi la liste des erreurs detectées par le moteur
     * @access public
     * @return array
     */
    function getErrors() 
    {
        return $this->errors;
    }

    /**
     * renvoi la version de wikirenderer
     * @access public
     * @return string   version
     */
    function getVersion()
    {
        return '2.1';
    }

    /**
     * ferme un bloc
     * @access private
     */
    function _closeBloc() 
    {
        if ($this->_isBlocOpen) {
            $this->_isBlocOpen = false;
            $this->_newtext[] = $this->_currentBloc->close();
            $this->_currentBloc = new Pluf_Text_Wiki_BlockRenderer($this);
        }
    }

    /**
     * ouvre un bloc et le referme eventuellement suivant sa nature
     * @return boolean  indique si le bloc reste ouvert ou pas
     * @access private
     */
    function _openBloc() 
    {
        if (!$this->_isBlocOpen) {
            $this->_newtext[] = $this->_currentBloc->open();
            $this->_isBlocOpen = true;
            return !$this->_currentBloc->closeNow();
        } else {
            return true;
        }
    }
}
