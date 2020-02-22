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
 * classe de base pour la transformation des élements de type bloc
 * @abstract
 */
class Pluf_Text_Wiki_BlockRenderer
{

    /**
     * @var string code identifiant le type de bloc
     * @access protected
     */
    public $type='';

    /**
     * @var string  chaine contenant le tag XHTML d'ouverture du bloc
     * @access protected
     */
    public $_openTag='';

    /**
     * @var string  chaine contenant le tag XHTML de fermeture du bloc
     * @access protected
     */
    public $_closeTag='';

    /**
     * @var boolean indique si le bloc doit être immediatement fermé aprés détection
     * @access protected
     */
    public $_closeNow=false;

    /**
     * @var WikiRenderer      référence à la classe principale
     * @access protected
     */
    public $engine=null;

    /**
     * @var array liste des élements trouvés par l'expression régulière regexp
     * @access protected
     */
    public $_detectMatch=null;

    /**
     * @var string expression régulière permettant de reconnaitre le bloc
     * @access protected
     */
    public $regexp='';

    /**
     * constructeur à surcharger pour définir les valeurs des différentes proprietés
     * @param WikiRender $wr l'objet moteur wiki
     */

    function __construct($wr) {
        $this->engine = $wr;
    }

    /**
     * renvoi une chaine correspondant à l'ouverture du bloc
     * @return string
     * @access public
     */

    function open() {
        return $this->_openTag;
    }

    /**
     * renvoi une chaine correspondant à la fermeture du bloc
     * @return string
     * @access public
     */

    function close() {
        return $this->_closeTag;
    }

    /**
     * indique si le bloc doit etre immédiatement fermé
     * @return string
     * @access public
     */

    function closeNow() {
        return $this->_closeNow;
    }

    /**
     * test si la chaine correspond au debut ou au contenu d'un bloc
     * @param string $string
     * @return boolean true: appartient au bloc
     * @access public
     */

    function detect($string) {
        return preg_match($this->regexp, $string, $this->_detectMatch);
    }

    /**
     * renvoi la ligne, traitée pour le bloc. A surcharger éventuellement.
     * @return string
     * @access public
     */

    function getRenderedLine() {
        return $this->_renderInlineTag($this->_detectMatch[1]);
    }

    /**
     * renvoi le type du bloc en cours de traitement
     * @return string
     * @access public
     */

    function getType() {
        return $this->type;
    }

    /**
     * définit la liste des élements trouvés par l'expression régulière regexp
     * @return array
     * @access public
     */

    function setMatch($match) {
        $this->_detectMatch = $match;
    }

    /**
     * renvoi la liste des élements trouvés par l'expression régulière regexp
     * @return array
     * @access public
     */

    function getMatch() {
        return $this->_detectMatch;
    }

    /**
     * traite le rendu des signes de type inline (qui se trouvent necessairement dans des blocs
     * @param   string  $string une chaine contenant une ou plusieurs balises wiki
     * @return  string  la chaine transformée en XHTML
     * @access protected
     * @see WikiRendererInline
     */

    function _renderInlineTag($string) {
        $parser = $this->engine->getInlineParser();
        return $parser->parse($string);
    }

    /**
     * détection d'attributs de bloc (ex:  >°°attr1|attr2|attr3°° la citation )
     * @todo à terminer pour une version ulterieure
     * @access protected
     */

    function _checkAttributes(&$string) {
        $bat=$this->engine->config->blocAttributeTag;
        if(preg_match("/^$bat(.*)$bat(.*)$/",$string,$result)) {
            $string=$result[2];
            return explode($this->engine->config->inlineTagSeparator,$result[1]);
        } else
            return false;
    }

}
