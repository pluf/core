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

error_reporting(E_ALL | E_STRICT);

$path = dirname(__FILE__).'/../../src/';
set_include_path(get_include_path().PATH_SEPARATOR.$path);

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'PHPUnit/Framework/IncompleteTestError.php';

require_once 'Pluf.php';

class PlufGettextTest extends PHPUnit_Framework_TestCase {
    
    protected function setUp()
    {
        Pluf::start(dirname(__FILE__).'/../conf/pluf.config.php');
        if (!isset($GLOBALS['_PX_locale'])) $GLOBALS['_PX_locale'] = null;
        $this->tmp = $GLOBALS['_PX_locale'];
    }

    protected function tearDown()
    {
        $GLOBALS['_PX_locale'] = $this->tmp;
    }
    public function testParseFile()
    {
        $trans = Pluf_Gettext::parseLangFile(dirname(__FILE__).'/sample1.lang');
        $this->assertEquals('Les fonctions de tampon d\'affichage ne sont pas disponibles.',
                            $trans['The output buffering functions are not available.']);
    }

    public function testParseFilePlurals()
    {
        $trans = Pluf_Gettext::parseLangFile(dirname(__FILE__).'/sample-plurals.lang');
        $this->assertEquals('Plume CMS Installation 2',
                            $trans['~~PLURALS~~']['PLUME CMS InstallationPlume CMS Installations'][2]);
        $GLOBALS['_PX_locale'] = $trans;
        $this->assertEquals('Plume CMS Installation 2', _n('PLUME CMS Installation', 'Plume CMS Installations', 2));
        $this->assertEquals('Plume CMS Installation 2', _n('PLUME CMS Installation', 'Plume CMS Installations', 100));
    }

}

?>