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
namespace Pluf\Graphql;

use Pluf\Data\ModelDescription;
use Pluf\Data\ModelProperty;
use Pluf\Data\ModelUtils;
use Pluf\Data\Schema;
use Pluf\HTTP\Error500;

/**
 * Create a compilre render
 *
 * @author maso
 *        
 */
class Compiler
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
        $this->rootType = ModelUtils::getModelCacheKey($rootType);
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
        // if ($this->rootType === 'Pluf_Paginator') {
        // $renderCode .= $this->createModelType($this->itemType);
        // $renderCode .= '$itemType =' . $this->getNameOf($this->itemType) . ';';
        // $renderCode .= '$rootType =' . $this->createPaginatorType();
        // } else {
        $renderCode .= $this->createModelType($this->rootType);
        $renderCode .= '$rootType = $' . ModelUtils::skipeName($this->rootType) . ';';
        // }
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
         $' . ModelUtils::skipeName($item) . ' = null;';
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
            throw new \Pluf\HTTP\Error500($e->getMessage());
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
        throw new Error500(sprintf('Cannot write the GraphQl render function: %s', $fileName));
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
        // $model = new $type();
        $name = ModelUtils::getModelCacheKey($type);
        // $orginName = $name;

        if (in_array($name, $this->compiledTypes)) {
            // type is compiled before
            return '';
        }
        array_push($this->compiledTypes, $name);

        $md = ModelDescription::getInstance($type);
        if (isset($md->graphql_name)) {
            $name = $md->graphql_name;
        }

        $preModels = ModelUtils::getRelatedModels($md);
        $requiredModel = '';
        if (sizeof($preModels) > 0) {
            $names = array();
            foreach ($preModels as $pm) {
                $names[] = ModelUtils::skipeName($pm);
            }
            $requiredModel = 'use (&$' . implode(', &$', $names) . ')';
        }

        // compile the model
        $result = ' //
        $' . ModelUtils::skipeName($name) . ' = new ObjectType([
            \'name\' => \'' . $name . '\',
            \'fields\' => function () ' . $requiredModel . '{
                return ' . $this->compileFields($md) . ';
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
        return '$' . ModelUtils::skipeName(ModelUtils::getModelCacheKey($type));
    }

    private function compileFields(ModelDescription $model): string
    {
        $fields = '';
        foreach ($model as $field) {
            // Check if it is graphql_field
            if (! $field->graphql_field || ! $field->readable) {
                continue;
            }
            switch ($field->type) {
                case Schema::ONE_TO_MANY:
                case Schema::MANY_TO_MANY:
                    $fields .= $this->compileFieldManytomany($field);
                    break;
                case Schema::MANY_TO_ONE:
                    $fields .= $this->compileFieldForeignkey($field);
                    break;
                default:
                    // set field name
                    $name = $field->name;
                    if (isset($field->graphql_name)) {
                        $name = $field->graphql_name;
                    }

                    $fields .= '
                    //' . $this->fieldComment($field) . '
                    \'' . $name . '\' => [
                        ' . $this->compileField($field) . '
                    ],';
                    break;
            }
        }
        return '[' . $fields . ']';
    }

    // /**
    // * Compile and add OneToMany or ManyToMany relations
    // *
    // * These relations are created automatically and ther is no related field
    // * in the model definitions.
    // *
    // * @param string $type
    // * Relation type: Engine::FOREIGNKEY Engine::Many_To_many
    // * @param \Pluf\Data\Model $mainModel
    // * main model wihch is the target of compile
    // */
    // private function compileRelationFields($type, $mainModel)
    // {
    // $res = '';
    // $current_model = ModelUtils::getModelCacheKey($mainModel);
    // $relations = ModelUtils::getRelatedModels($mainModel, $type);
    // foreach ($relations as $related) {
    // if ($related != $current_model) {
    // $model = new $related();
    // } else {
    // $model = clone $mainModel;
    // }
    // $fkeys = $model->getRelationKeysToModel($current_model, $type);
    // foreach ($fkeys as $fkey => $val) {
    // $name = (isset($val['relate_name'])) ? $val['relate_name'] : $related;
    // $res .= '
    // //Foreinkey list-' . $this->fieldComment($fkey, []) . '
    // \'' . $name . '\' => [
    // \'type\' => Type::listOf(' . $this->getNameOf($related) . '),
    // \'resolve\' => function ($root) {
    // return $root->get_' . $name . '_list();
    // },
    // ],';
    // }
    // }
    // return $res;
    // }
    private function compileField(ModelProperty $field)
    {
        // set type
        switch ($field->type) {
            case Schema::SEQUENCE:
            case Schema::FOREIGNKEY:
                $res = 'Type::int()';
                break;
            case Schema::DATE:
            case Schema::DATETIME:
            case Schema::EMAIL:
            case Schema::FILE:
            case Schema::SERIALIZED:
            case Schema::SLUG:
            case Schema::TEXT:
            case Schema::VARCHAR:
            case Schema::GEOMETRY:
                $res = 'Type::string()';
                break;
            case Schema::INTEGER:
                $res = 'Type::int()';
                break;
            case Schema::FLOAT:
                $res = 'Type::float()';
                break;
            case Schema::BOOLEAN:
                $res = 'Type::boolean()';
                break;

            case Schema::MANY_TO_ONE:
            case Schema::MANY_TO_MANY:
            case Schema::ONE_TO_MANY:
            default:
                throw new Error500('Unsupported data tyep');
        }

        // for primetives
        return '\'type\' => ' . $res . ',
                        \'resolve\' => function ($root) {
                            return $root->' . $field->name . ';
                        },';
    }

    /*
     * Converts a field to a string command
     */
    private function compileFieldForeignkey(ModelProperty $field): string
    {
        $name = $field->name;
        if (isset($field->graphql_name)) {
            $name = $field->graphql_name;
        }
        $typeVar = $this->getNameOf($field->inverseJoinModel);

        $functionName = $field->name;
        $res = '
                    //Foreinkey object-' . $this->fieldComment($field) . '
                    \'' . $name . '\' => [
                            \'type\' => ' . $typeVar . ',
                            \'resolve\' => function ($root) {
                                return $root->get_' . $functionName . '();
                            },
                    ],';
        return $res;
    }

    private function compileFieldManytomany(ModelProperty $field): string
    {
        $type = $this->getNameOf($field->inverseJoinModel);
        $functionName = $field->name;
        $name = $field->name;
        if (isset($field->graphql_name)) {
            $name = $field->graphql_name;
        }
        /*
         * TODO: maso, 2018: support for pagination in list function
         * XXX: maso, 2018: check for security access
         *
         * if(parent is accessable) then
         * All children are too
         */
        return '
                    //relation value-' . $this->fieldComment($field) . '
                    \'' . $name . '\' => [
                            \'type\' => Type::listOf(' . $type . '),
                            \'resolve\' => function ($root) {
                                return $root->get_' . $functionName . '_list();
                            },
                    ],';
    }

    /*
     * Converts field into a single line string as comment
     */
    private function fieldComment(ModelProperty $field): string
    {
        // return $field->name . ': ' . str_replace([
        // "\r\n",
        // "\n",
        // "\r"
        // ], "", print_r($field, true));
        return '';
    }
}


