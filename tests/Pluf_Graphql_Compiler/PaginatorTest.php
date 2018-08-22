// <?php
// /*
//  * This file is part of Pluf Framework, a simple PHP Application Framework.
//  * Copyright (C) 2010-2020 Phoinex Scholars Co. (http://dpq.co.ir)
//  *
//  * This program is free software: you can redistribute it and/or modify
//  * it under the terms of the GNU General Public License as published by
//  * the Free Software Foundation, either version 3 of the License, or
//  * (at your option) any later version.
//  *
//  * This program is distributed in the hope that it will be useful,
//  * but WITHOUT ANY WARRANTY; without even the implied warranty of
//  * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
//  * GNU General Public License for more details.
//  *
//  * You should have received a copy of the GNU General Public License
//  * along with this program. If not, see <http://www.gnu.org/licenses/>.
//  */
// use PHPUnit\Framework\TestCase;
// use PHPUnit\Framework\IncompleteTestError;
// require_once 'Pluf.php';

// set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/../apps');

// /**
//  *
//  * @backupGlobals disabled
//  * @backupStaticAttributes disabled
//  */
// class Pluf_Graphql_Compiler_PaginatorTest extends TestCase
// {

//     /**
//      *
//      * @before
//      */
//     public function setUp()
//     {
//         $conf = include __DIR__ . '/../conf/config.php';
//         $conf['installed_apps'] = array(
//             'Test'
//         );
//         Pluf::start($conf);
//     }

//     /**
//      *
//      * @test
//      */
//     public function testRenderPaginatorAndLoad()
//     {
//         $types = [
//             // Paginated list
//             'Pluf_Paginator' => 'Test_Model',
//             'Pluf_Paginator' => 'Test_ModelRecurse',
//             'Pluf_Paginator' => 'Test_ModelCount',
//             'Pluf_Paginator' => 'Test_RelatedToTestModel',
//             'Pluf_Paginator' => 'Test_RelatedToTestModel2',
//             'Pluf_Paginator' => 'Test_ManyToManyOne',
//             'Pluf_Paginator' => 'Test_ManyToManyTwo',
//         ];
//         foreach ($types as $rootType => $itemType) {
//             $class_name = 'Pluf_GraphQl_TestRender_' . rand();
//             $filename = dirname(__FILE__) . '/../tmp/' . $class_name . '.phps';
//             if (file_exists($filename)) {
//                 unlink($filename);
//             }
//             $compiler = new Pluf_Graphql_Compiler($rootType, $itemType);
//             $compiler->write($class_name, $filename);
//             $this->assertTrue(file_exists($filename));

//             include $filename;
//             class_exists($class_name);
//         }
//     }
// }



