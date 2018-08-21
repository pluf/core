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

/**
 * Render a result based on GraphQl
 *
 * @author maso<mostafa.barmshory@dpq.co.ir>
 * @since 4.0.0
 */
class Pluf_Graphql
{

    public $compiled_schema = null;

    public $class = null;

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
     */
    function render($c)
    {
        $schema = 'Pluf_Schema';

        // 1. root type
        $rootType = get_class($c);
        $schema = $schema . '_' . $rootType;
        if ($c instanceof Pluf_Paginator) {
            $itemType = get_class($c->model);
            $schema = $schema . '_' . $itemType;
        }

        // load schema
        if (! class_exists($schema, false)) {
            $compiled_schema = $this->cache . '/' . $schema . '.phps';
            if (! file_exists($compiled_schema) or Pluf::f('debug')) {
                $compiler = new Pluf_Graphql_Compiler($rootType, $itemType);
                $compiler->write($compiled_schema);
            }
            include $compiled_schema;
        }

        // render result
        ob_start();
        try {
            call_user_func(array(
                $this->class,
                'render'
            ), $c);
        } catch (Exception $e) {
            ob_clean();
            throw $e;
        }
        $result = ob_get_contents();
        ob_end_clean();

        return $result;
    }

    /**
     * Get the full name of the compiled schema.
     *
     * Ends with .phps to prevent execution from outside if the cache folder
     * is not secured but to still have the syntax higlightings by the tools
     * for debugging.
     *
     * @return array of Full path to the compiled template and the key of the cache
     */
    function getCompiledTemplateName()
    {
        // The compiled template not only depends on the file but also
        // on the cache path in which it can be found.
        $_tmp = md5($this->cache . $this->rootType);
        return array(
            'Pluf_Schema_' . $_tmp,
            $this->cache . '/Pluf_Schema-' . $_tmp . '.phps'
        );
    }
}

