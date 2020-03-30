<?php
use Pluf\ModelUtils;
use Pluf\Db\Engine;

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
         $' . Pluf_ModelUtils::skipeName($item) . ' = null;';
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
            var_dump($e);
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
                            return $root->fetchItemsCount();
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
                            return $root->getNumberOfPages();
                        }
                    ],
                    \'items\' => [
                        \'type\' => Type::listOf($itemType),
                        \'resolve\' => function ($root) {
                            return $root->fetchItems();
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
        $model = new $type();
        $name = ModelUtils::getModelCacheKey($model);
        $orginName = $name;

        if (in_array($name, $this->compiledTypes)) {
            // type is compiled before
            return '';
        }
        array_push($this->compiledTypes, $name);
        if (array_key_exists('graphql_name', $model->_a)) {
            $name = $model->_a['graphql_name'];
        }

        $preModels = $this->getRelatedModels($model);
        $requiredModel = '';
        if (sizeof($preModels) > 0) {
            $names = array();
            foreach ($preModels as $pm) {
                $names[] = Pluf_ModelUtils::skipeName($pm);
            }
            $requiredModel = 'use (&$' . implode(', &$', $names) . ')';
        }

        // compile the model
        $result = ' //
        ' . $this->getNameOf($orginName) . ' = new ObjectType([
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
        return '$' . Pluf_ModelUtils::skipeName($type);
    }

    private function compileFields($model)
    {
        $cols = $model->_a['cols'];
        $fields = '[
                    // List of basic fields
                    ' . $this->compileBasicFields($cols) . '
                    // relations: forenkey
                    ' . $this->compileRelationFields(Engine::FOREIGNKEY, $model) . '
                    ' . $this->compileRelationFields(Engine::MANY_TO_MANY, $model) . '
                ]';
        return $fields;
    }

    private function compileBasicFields($cols)
    {
        $fields = '';
        foreach ($cols as $key => $field) {
            // Check if it is graphql_field
            if (array_key_exists('graphql_field', $field)) {
                if (! $field['graphql_field']) {
                    continue;
                }
            }
            if (array_key_exists('readable', $field)) {
                if (! $field['readable']) {
                    continue;
                }
            }

            // Foreignkey
            $fieldType = $field['type'];
            if ($fieldType === 'Foreignkey') {
                $fields .= $this->compileFieldForeignkey($key, $field);
                continue;
            }

            // ManyToMany
            if ($fieldType === 'Manytomany') {
                $fields .= $this->compileFieldManytomany($key, $field);
                continue;
            }

            // set field name
            $name = $key;
            if (array_key_exists('graphql_name', $field)) {
                $name = $field['graphql_name'];
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
     *            Relation type: Engine::FOREIGNKEY Engine::Many_To_many
     * @param Pluf_Model $mainModel
     *            main model wihch is the target of compile
     */
    private function compileRelationFields($type, $mainModel)
    {
        $res = '';
        $current_model = ModelUtils::getModelCacheKey($mainModel);
        $relations = ModelUtils::getRelatedModels($mainModel, $type);
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
        return $res;
    }

    private function compileField($key, $field)
    {
        // set type
        switch ($field['type']) {
            case 'Sequence':
                $res = 'Type::int()';
                break;
            case 'Date':
            case 'Datetime':
            case 'Email':
            case 'File':
            case 'Serialized':
            case 'Slug':
            case 'Text':
            case 'Varchar':
            case 'Geometry':
                $res = 'Type::string()';
                break;
            case 'Integer':
                $res = 'Type::int()';
                break;
            case 'Float':
                $res = 'Type::float()';
                break;
            case 'Boolean':
                $res = 'Type::boolean()';
                break;

            case 'Foreignkey':
            case 'Manytomany':
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
        if (array_key_exists('graphql_name', $field)) {
            $name = $field['graphql_name'];
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

    private function compileFieldManytomany($key, $field)
    {
        // type
        $type = $this->getNameOf($field['model']);
        // function
        $functionName = $key;
        if (array_key_exists('name', $field)) {
            $functionName = $field['name'];
        }
        // name
        $name = $key;
        if (array_key_exists('graphql_name', $field)) {
            $name = $field['graphql_name'];
        }
        /*
         * TODO: maso, 2018: support for pagination in list function
         * XXX: maso, 2018: check for security access
         *
         * if(parent is accessable) then
         * All children are too
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
            if ($fieldType === Engine::FOREIGNKEY || $fieldType === Engine::MANY_TO_MANY) {
                if (! in_array($field['model'], $preModels)) {
                    array_push($preModels, $field['model']);
                }
            }
        }

        $types = [
            Engine::FOREIGNKEY,
            Engine::MANY_TO_MANY
        ];
        foreach ($types as $type) {
            $relations = ModelUtils::getRelatedModels($model, $type);
            foreach ($relations as $related) {
                if (! in_array($related, $preModels)) {
                    array_push($preModels, $related);
                }
            }
        }

        return $preModels;
    }
}


