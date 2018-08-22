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

    private $compiledTypes;

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
        $this->compiledTypes = array();
        $renderCode = '';
        if ($this->rootType === 'Pluf_Paginator') {
            $renderCode .= $this->createModelType($this->itemType);
            $renderCode .= '$itemType =' . $this->getNameOf($this->itemType) . ';';
            $renderCode .= '$rootType =' . $this->createPaginatorType();
        } else {
            $renderCode .= $this->createModelType($this->rootType);
            $renderCode .= '$rootType =' . $this->getNameOf($this->rootType) . ';';
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
    public function render($rootValue, $query) {
        // render object types variables
        $' . implode("= null, $", $this->compiledTypes) . '= null;
        // render code
        ' . $renderCode . '
        try {
            $schema = new Schema([
                \'query\' => $rootType
            ]);
            $result = GraphQL::executeQuery($schema, $query, $rootValue);
            return $result->toArray();
        } catch (Exception $e) {
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
        return 'new ObjectType([
            \'name\' => \'Pluf_paginator\',
            \'fields\' => function () use (&$itemType){
                return [
                    \'counts\' => [
                        \'type\' => Type::int(),
                        \'resolve\' => function ($root) {
                            return $root->counts;
                        }
                    ],
                    \'current_page\' => [
                        \'type\' => Type::int(),
                        \'resolve\' => function ($root) {
                            return $root->current_page;
                        }
                    ],
                    \'items_per_page\' => [
                        \'type\' => Type::int(),
                        \'resolve\' => function ($root) {
                            return $root->items_per_page;
                        }
                    ],
                    \'page_number\' => [
                        \'type\' => Type::int(),
                        \'resolve\' => function ($root) {
                            return $root->page_number;
                        }
                    ],
                    \'items\' => [
                        \'type\' => Type::listOf($itemType),
                        \'resolve\' => function ($root) {
                            return $root->items;
                        }
                    ],
                ];
            }
        ]);';
    }

    /**
     *
     * @param string $type
     *            model name
     * @return string
     */
    private function createModelType($type)
    {
        if (in_array($type, $this->compiledTypes)) {
            // type is compiled before
            return '';
        }
        array_push($this->compiledTypes, $type);

        $model = new $type();
        $name = $model->_a['model'];
        if (array_key_exists('graphqlName', $model->_a)) {
            $name = $model->_a['graphqlName'];
        }

        $result = '';

        $cols = $model->_a['cols'];
        $preModels = [];
        foreach ($cols as $key => $field) {
            $fieldType = $field['type'];
            if ($fieldType === 'Pluf_DB_Field_Foreignkey' || $fieldType === 'Pluf_DB_Field_Manytomany') {
                $result .= $this->createModelType($field['model']);
                array_push($preModels, '&' . $this->getNameOf($field['model']));
            }
        }

        $requiredModel = '';
        if (sizeof($preModels) > 0) {
            $requiredModel = 'use (' . implode(', ', $preModels) . ')';
        }

        return $result . ' //
        ' . $this->getNameOf($type) . ' = new ObjectType([
            \'name\' => \'' . $name . '\',
            \'fields\' => function () ' . $requiredModel . '{
                return ' . $this->compileFields($model) . ';
            }
        ]);';
    }

    private function getNameOf($type)
    {
        return '$' . $type;
    }

    private function compileFields($model)
    {
        $cols = $model->_a['cols'];
        $fields = '[
                    // List of basic fields';
        foreach ($cols as $key => $field) {
            // Check if it is graphqlField
            if (array_key_exists('graphqlField', $field)) {
                if (! $field['graphqlField']) {
                    continue;
                }
            }

            // set field name
            $name = $key;
            if (array_key_exists('graphqlName', $field)) {
                $name = $field['graphqlName'];
            }

            $fields .= '
                    //' . $key . ': ' . str_replace(array(
                "\r\n",
                "\n",
                "\r"
            ), "", print_r($field, true)) . '
                    \'' . $name . '\' => [
                        ' . $this->compileField($key, $field) . '
                    ],';
        }
        $fields .= ']';
        return $fields;
    }

    private function compileField($key, $field)
    {
        // set type
        switch ($field['type']) {
            case 'Pluf_DB_Field_Sequence':
                $res = 'Type::id()';
                break;
            case 'Pluf_DB_Field_Date':
            case 'Pluf_DB_Field_Datetime':
            case 'Pluf_DB_Field_Email':
            case 'Pluf_DB_Field_File':
            case 'Pluf_DB_Field_Serialized':
            case 'Pluf_DB_Field_Slug':
            case 'Pluf_DB_Field_Text':
            case 'Pluf_DB_Field_Varchar':
                $res = 'Type::string()';
                break;
            case 'Pluf_DB_Field_Integer':
                $res = 'Type::int()';
                break;
            case 'Pluf_DB_Field_Float':
                $res = 'Type::float()';
                break;
            case 'Pluf_DB_Field_Boolean':
                $res = 'Type::boolean()';
                break;

            case 'Pluf_DB_Field_Foreignkey':
                return $this->compileFieldForeignkey($key, $field);
            case 'Pluf_DB_Field_Manytomany':
                return $this->compileFieldManytomany($key, $field);
            default:
                // TODO: Unsupported type
                return '';
        }

        // for primetives
        return '\'type\' => ' . $res . ',
                        \'resolve\' => function ($root) {
                            return $root->' . $key . ';
                        },';
    }

    private function compileFieldForeignkey($key, $field)
    {
        $res = '\'type\' => Type::int(),
                            \'resolve\' => function ($root) {
                                return $root->' . $key . ';
                            },';
        if (array_key_exists('graphqlName', $field)) {
            $name = $field['graphqlName'];
            $type = $this->getNameOf($field['model']);

            $functionNmae = $key;
            if (array_key_exists('name', $field)) {
                $functionName = $field['name'];
            }
            $res .= '
                            \'type\' => ' . $type . ',
                            \'resolve\' => function ($root) {
                                return $root->get_' . $functionName . '();
                            },';
        }
        return $res;
    }

    private function compileFieldManytomany()
    {}
}


