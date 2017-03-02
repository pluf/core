<?php
/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. (http://dpq.co.ir)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\IncompleteTestError;
require_once 'Pluf.php';

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class PlufGettextTest extends TestCase
{

//     protected function setUp ()
//     {
//         Pluf::start(dirname(__FILE__) . '/../conf/pluf.config.php');
//         if (! isset($GLOBALS['_PX_locale']))
//             $GLOBALS['_PX_locale'] = null;
//         $this->tmp = $GLOBALS['_PX_locale'];
//     }

//     protected function tearDown ()
//     {
//         $GLOBALS['_PX_locale'] = $this->tmp;
//     }

//     public function testParseFile ()
//     {
//         $trans = Pluf_Gettext::parseLangFile(dirname(__FILE__) . '/sample1.lang');
//         $this->assertEquals(
//                 'Les fonctions de tampon d\'affichage ne sont pas disponibles.', 
//                 $trans['The output buffering functions are not available.']);
//     }

//     public function testParseFilePlurals ()
//     {
//         $trans = Pluf_Gettext::parseLangFile(
//                 dirname(__FILE__) . '/sample-plurals.lang');
//         $this->assertEquals('Plume CMS Installation 2', 
//                 $trans['~~PLURALS~~']['PLUME CMS InstallationPlume CMS Installations'][2]);
//         $GLOBALS['_PX_locale'] = $trans;
//         $this->assertEquals('Plume CMS Installation 2', 
//                 _n('PLUME CMS Installation', 'Plume CMS Installations', 2));
//         $this->assertEquals('Plume CMS Installation 2', 
//                 _n('PLUME CMS Installation', 'Plume CMS Installations', 100));
//     }
}

?>