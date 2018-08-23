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
class ' . $className . ' { 
    public function render($rootValue, $query) {
        // render object types variables';
        foreach ($this->compiledTypes as $item) {
            $schema_content .= '
         $' . $item . ' = null;';
        }
        $schema_content .= '
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

        $preModels = $this->getRelatedModels($model);
        $requiredModel = '';
        if (sizeof($preModels) > 0) {
            $requiredModel = 'use (&$' . implode(', &$', $preModels) . ')';
        }

        // compile the model
        $result = ' //
        ' . $this->getNameOf($type) . ' = new ObjectType([
            \'name\' => \'' . $name . '\',
            \'fields\' => function () ' . $requiredModel . '{
                return ' . $this->compileFields($model) . ';
            }
        ]);';

        // compile related models
        foreach ($preModels as $typeName) {
            $result .= $this->createModelType($typeName);
        }

        return $result;
    }

    private function getNameOf($type)
    {
        return '$' . $type;
    }

    private function compileFields($model)
    {
        $cols = $model->_a['cols'];
        $fields = '[
                    // List of basic fields
                    ' . $this->compileBasicFields($cols) . '
                    // relations: forenkey 
                    ' . $this->compileRelationFields('foreignkey', $model) . '
                    ' . $this->compileRelationFields('manytomany', $model) . '
                ]';
        return $fields;
    }

    private function compileBasicFields($cols)
    {
        $fields = '';
        foreach ($cols as $key => $field) {
            // Check if it is graphqlField
            if (array_key_exists('graphqlField', $field)) {
                if (! $field['graphqlField']) {
                    continue;
                }
            }

            // Foreignkey
            $fieldType = $field['type'];
            if ($fieldType === 'Pluf_DB_Field_Foreignkey') {
                $fields .= $this->compileFieldForeignkey($key, $field);
                continue;
            }

            // ManyToMany
            if ($fieldType === 'Pluf_DB_Field_Manytomany') {
                $fields .= $this->compileFieldManytomany($key, $field);
                continue;
            }

            // set field name
            $name = $key;
            if (array_key_exists('graphqlName', $field)) {
                $name = $field['graphqlName'];
            }

            $fields .= '
                    //' . $this->fieldComment($key, $field) . '
                    \'' . $name . '\' => [
                        ' . $this->compileField($key, $field) . '
                    ],';
        }
        return $fields;
    }

    /**
     * Compile and add OneToMany or ManyToMany relations
     *
     * These relations are created automatically and ther is no related field
     * in the model definitions.
     *
     * @param string $type
     *            Relation type: 'foreignkey' or 'manytomany'.
     * @param Pluf_Model $mainModel
     *            main model wihch is the target of compile
     */
    private function compileRelationFields($type, $mainModel)
    {
        $res = '';
        $current_model = $mainModel->_a['model'];
        if (isset($GLOBALS['_PX_models_related'][$type][$current_model])) {
            $relations = $GLOBALS['_PX_models_related'][$type][$current_model];
            foreach ($relations as $related) {
                if ($related != $current_model) {
                    $model = new $related();
                } else {
                    $model = clone $mainModel;
                }
                $fkeys = $model->getRelationKeysToModel($current_model, $type);
                foreach ($fkeys as $fkey => $val) {
                    $name = (isset($val['relate_name'])) ? $val['relate_name'] : $related;
                    $res .= '
                    //Foreinkey list-' . $this->fieldComment($fkey, []) . '
                    \'' . $name . '\' => [
                            \'type\' => Type::listOf(' . $this->getNameOf($related) . '),
                            \'resolve\' => function ($root) {
                                return $root->get_' . $name . '_list();
                            },
                    ],';
                }
            }
        }
        return $res;
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
            case 'Pluf_DB_Field_Manytomany':
                return '';
            default:
                // TODO: Unsupported type exceptions
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
        $res = '
                    //Foreinkey value-' . $this->fieldComment($key, $field) . '
                    \'' . $key . '\' => [
                            \'type\' => Type::int(),
                            \'resolve\' => function ($root) {
                                return $root->' . $key . ';
                            },
                    ],';
        if (array_key_exists('graphqlName', $field)) {
            $name = $field['graphqlName'];
            $type = $this->getNameOf($field['model']);

            $functionName = $key;
            if (array_key_exists('name', $field)) {
                $functionName = $field['name'];
            }
            $res .= '
                    //Foreinkey object-' . $this->fieldComment($key, $field) . '
                    \'' . $name . '\' => [
                            \'type\' => ' . $type . ',
                            \'resolve\' => function ($root) {
                                return $root->get_' . $functionName . '();
                            },
                    ],';
        }
        return $res;
    }

    
    private function compileFieldManytomany($key, $field){
        // type
        $type = $this->getNameOf($field['model']);
        // function
        $functionName = $key;
        if (array_key_exists('name', $field)) {
            $functionName = $field['name'];
        }
        // name
        $name = $key;
        if (array_key_exists('graphqlName', $field)) {
            $name = $field['graphqlName'];
        }
        /*
         * TODO: maso, 2018: support for pagination in list function
         * XXX: maso, 2018: check for security access
         * 
         * if(parent is accessable) then 
         *      All children are too
         */ 
        return '
                    //Foreinkey value-' . $this->fieldComment($key, $field) . '
                    \'' . $name . '\' => [
                            \'type\' => Type::listOf(' . $type . '),
                            \'resolve\' => function ($root) {
                                return $root->get_' . $functionName . '_list();
                            },
                    ],';
    }
    
    private function fieldComment($key, $field)
    {
        return $key . ': ' . str_replace([
            "\r\n",
            "\n",
            "\r"
        ], "", print_r($field, true));
    }

    private function getRelatedModels($model)
    {
        $cols = $model->_a['cols'];
        $preModels = [];
        foreach ($cols as $field) {
            $fieldType = $field['type'];
            if ($fieldType === 'Pluf_DB_Field_Foreignkey' || $fieldType === 'Pluf_DB_Field_Manytomany') {
                if (! in_array($field['model'], $preModels)) {
                    array_push($preModels, $field['model']);
                }
            }
        }

        $current_model = $model->_a['model'];

        $types = [
            'foreignkey',
            'manytomany'
        ];
        foreach ($types as $type) {
            if (isset($GLOBALS['_PX_models_related'][$type][$current_model])) {
                $relations = $GLOBALS['_PX_models_related'][$type][$current_model];
                foreach ($relations as $related) {
                    if (! in_array($related, $preModels)) {
                        array_push($preModels, $related);
                    }
                }
            }
        }

        return $preModels;
    }
}


