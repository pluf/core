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
namespace Pluf\PlufTest\Graphql;

use PHPUnit\Framework\TestCase;
use Pluf\Bootstrap;
use Pluf\Migration;
use Pluf\Graphql;
use Pluf\Paginator;
use Pluf\Test;

/**
 *
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class PaginatorTest extends TestCase
{

    /**
     *
     * @beforeClass
     */
    public static function installApplication1()
    {
        $conf = include __DIR__ . '/../conf/config.php';
        $conf['installed_apps'] = array(
            'Pluf',
            'Test'
        );
        Bootstrap::start($conf);
        $m = new Migration($conf['installed_apps']);
        $m->install();
    }

    /**
     *
     * @afterClass
     */
    public static function removeDatabses1()
    {
        $m = new Migration(array(
            'Pluf',
            'Test'
        ));
        $m->unInstall();
    }

    /**
     *
     * @test
     */
    public function testRenderPaginatorAndLoad()
    {
        $types = [
            // Paginated list
            'Pluf_Paginator' => '\Pluf\Test\Model',
            'Pluf_Paginator' => '\Pluf\Test\ModelRecurse',
            'Pluf_Paginator' => '\Pluf\Test\ModelCount',
            'Pluf_Paginator' => '\Pluf\Test\RelatedToTestModel',
            'Pluf_Paginator' => '\Pluf\Test\RelatedToTestModel2',
            'Pluf_Paginator' => '\Pluf\Test\ManyToManyOne',
            'Pluf_Paginator' => '\Pluf\Test\ManyToManyTwo'
        ];
        foreach ($types as $rootType => $itemType) {
            $class_name = 'Pluf_GraphQl_TestRender_' . rand();
            $filename = dirname(__FILE__) . '/../tmp/' . $class_name . '.phps';
            if (file_exists($filename)) {
                unlink($filename);
            }
            $compiler = new Graphql\Compiler($rootType, $itemType);
            $compiler->write($class_name, $filename);
            $this->assertTrue(file_exists($filename));

            include $filename;
            class_exists($class_name);

            $builder = new Paginator\Builder(new Test\Model());
            $rootValue = $builder->build();

            $compiler = new $class_name();
            $result = $compiler->render($rootValue, '{items{id}}');
            $this->assertFalse(array_key_exists('errors', $result));
            $this->assertTrue(array_key_exists('data', $result));
        }
    }
}



