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
 * Create a compilre render
 *
 * @author maso
 *        
 */
class Pluf_Graphql_Compiler
{

    private $rootType;

    private $itemType;

    /**
     * Creates new instance
     *
     * @param string $rootType
     *            the main data model
     * @param string $itemType
     *            the list model if is paginated
     */
    function __construct($rootType, $itemType = null)
    {
        $this->rootType = $rootType;
        $this->itemType = $itemType;
    }

    /**
     * Write graphql compiler
     *
     * @param string $outputFile
     */
    public function write($className, $outputFile)
    {
        $renderCode = '';
        if ($this->rootType === 'Pluf_Paginator') {
            $renderCode .= '$itemType =' . $this->createModelType($this->rootType);
            $renderCode .= '$rootType =' . $this->createPaginatorType();
        } else {
            $renderCode .= '$rootType =' . $this->createModelType($this->rootType);
        }
        $this->_write($className, $outputFile, $renderCode);
    }

    /**
     * Write the compiled template in the cache folder.
     * Throw an exception if it cannot write it.
     *
     * @return bool Success in writing
     */
    private function _write($className, $fileName, $renderCode)
    {
        $schema_content = '<?php 
// Import
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;
/**
 * Render class of GraphQl
 */
class ' . $className;
        $schema_content .= ' { 
    public static function render($rootValue, $query) {
        // render code
        ' . $renderCode . '
        try {
            $schema = new Schema($rootType);
            $result = GraphQL::executeQuery($rootType, $query, $rootValue);
            return $result->toArray();
        } catch( Exception $e) {
            throw new Pluf_Exception_BadRequest($e->getMessage());
        }
    }
}
';
        // mode "a" to not truncate before getting the lock
        $fp = @fopen($fileName, 'a');
        if ($fp !== false) {
            // Exclusive lock on writing
            flock($fp, LOCK_EX);
            // We have the unique pointeur, we truncate
            ftruncate($fp, 0);
            // Go back to the start of the file like a +w
            rewind($fp);
            fwrite($fp, $schema_content, strlen($schema_content));
            // Lock released, read access is possible
            flock($fp, LOCK_UN);
            fclose($fp);
            @chmod($fileName, 0777);
            return true;
        }
        throw new Exception(sprintf('Cannot write the GraphQl render function: %s', $fileName));
    }

    private static function createPaginatorType()
    {
        return '[
            \'counts\' =>  Type::int(),
            \'current_page\' => Type::int(),
            \'items_per_page\' => Type::int(),
            \'page_number\' => Type::int(),
            \'items\' => Type::listOf($itemType),
        ];';
    }

    private static function createModelType($type)
    {
        $result = '[
            \'id\' =>  Type::id(),
        ];';

        return $result;
    }
}


