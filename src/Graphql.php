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
namespace Pluf;

use Pluf\Data\ModelUtils;
use Pluf\Graphql\Compiler;
use Pluf;

/**
 * Render a result based on GraphQl
 *
 * @author maso<mostafa.barmshory@dpq.co.ir>
 * @since 4.0.0
 */
class Graphql
{

    private $cache = null;

    /**
     * Creates new instance of the engine
     *
     * If the schema file name is not provided, it will default to
     *
     * Pluf::f('graphql_schema')
     *
     * If the cache folder name is not provided, it will default to
     *
     * Pluf::f('tmp_folder')
     *
     *
     * @param Object $rootObject
     *            to render as result
     * @param string $schema
     *            name of schema file
     * @param array $cache
     *            a folder path to store the schema file
     */
    function __construct($cache = null)
    {
        if (null == $cache) {
            $this->cache = Pluf::f('tmp_folder');
        } else {
            $this->cache = $cache;
        }
    }

    /**
     * Render the template with the given context and return the content.
     *
     * @param $c Object
     *            Context.
     * @param $rootValue string
     *            GraphQl query for example {id, items{id}}
     * @return
     */
    function render($c, $query)
    {
        // 1. root type
        $itemType = null;
        // if ($c instanceof Pluf_Paginator) {
        // $rootType = 'Pluf_Paginator';
        // $itemType = ModelUtils::getModelCacheKey($c->model);
        // $schema = 'Pluf_GraphQl_Schema__Pluf_Paginator_' . $itemType;
        // } else {
        $rootType = ModelUtils::getModelCacheKey($c);
        $schema = 'Pluf_GraphQl_Schema_' . ModelUtils::skipeName($rootType);
        // }

        // 2. load schema
        $this->loadSchema($schema, $rootType, $itemType);

        // render result
        return $this->generateResult($schema, $c, $query);
    }

    private function loadSchema($schema, $rootType, $itemType)
    {
        if (class_exists($schema, false)) {
            return;
        }
        $compiled_schema = $this->cache . '/' . $schema . '.phps';
        if (! file_exists($compiled_schema) or Pluf::f('debug')) {
            $compiler = new Compiler($rootType, $itemType);
            $compiler->write($schema, $compiled_schema);
        }
        include $compiled_schema;
    }

    private function generateResult($schema, $c, $query)
    {
        $compiler = new $schema();
        $result = $compiler->render($c, $query);
        if (array_key_exists('errors', $result)) {
            throw new \Pluf\Exception('Fail to run GraphQl query: ' . $this->beautifyErrorMessage($result['errors']));
        }
        return $result['data'];
    }

    private function beautifyErrorMessage($errors)
    {
        // return print_r($errors, TRUE);
        $messages = array();
        foreach ($errors as $error) {
            $msg = '[line: ' . $error['locations'][0]['line'] . ', column: ' . $error['locations'][0]['column'] . ': ' . $error['message'] . ']';
            array_push($messages, $msg);
        }
        return implode(',', $messages);
    }
}

