<?php
// Import
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;
/**
 * Render class of GraphQl
 */
class Pluf_GraphQl_TestRender_1182655016 {
    public function render($rootValue, $query) {
        // render object types variables
         $Test_ManyToManyTwo = null;
         $Test_ManyToManyOne = null;
        // render code
         //
        $Test_ManyToManyTwo = new ObjectType([
            'name' => 'Test_ManyToManyTwo',
            'fields' => function () use (&$Test_ManyToManyOne){
                return [
                    // List of basic fields
                    
                    //id: Array(    [type] => Pluf_DB_Field_Sequence    [blank] => 1)
                    'id' => [
                        'type' => Type::int(),
                        'resolve' => function ($root) {
                            return $root->id;
                        },
                    ],
                    //two: Array(    [type] => Pluf_DB_Field_Varchar    [blank] =>     [size] => 100)
                    'two' => [
                        'type' => Type::string(),
                        'resolve' => function ($root) {
                            return $root->two;
                        },
                    ],
                    // relations: forenkey
                    
                    
                    //Foreinkey list-twos: Array()
                    'ones' => [
                            'type' => Type::listOf($Test_ManyToManyOne),
                            'resolve' => function ($root) {
                                return $root->get_ones_list();
                            },
                    ],
                ];
            }
        ]); //
        $Test_ManyToManyOne = new ObjectType([
            'name' => 'Test_ManyToManyOne',
            'fields' => function () use (&$Test_ManyToManyTwo){
                return [
                    // List of basic fields
                    
                    //id: Array(    [type] => Pluf_DB_Field_Sequence    [blank] => 1)
                    'id' => [
                        'type' => Type::int(),
                        'resolve' => function ($root) {
                            return $root->id;
                        },
                    ],
                    //Foreinkey value-twos: Array(    [type] => Pluf_DB_Field_Manytomany    [blank] => 1    [model] => Test_ManyToManyTwo    [relate_name] => ones)
                    'twos' => [
                            'type' => Type::listOf($Test_ManyToManyTwo),
                            'resolve' => function ($root) {
                                return $root->get_twos_list();
                            },
                    ],
                    //one: Array(    [type] => Pluf_DB_Field_Varchar    [blank] =>     [size] => 100)
                    'one' => [
                        'type' => Type::string(),
                        'resolve' => function ($root) {
                            return $root->one;
                        },
                    ],
                    // relations: forenkey
                    
                    
                ];
            }
        ]);$itemType =$Test_ManyToManyTwo;$rootType =new ObjectType([
            'name' => 'Pluf_paginator',
            'fields' => function () use (&$itemType){
                return [
                    'counts' => [
                        'type' => Type::int(),
                        'resolve' => function ($root) {
                            return $root->fetchItemsCount();
                        }
                    ],
                    'current_page' => [
                        'type' => Type::int(),
                        'resolve' => function ($root) {
                            return $root->current_page;
                        }
                    ],
                    'items_per_page' => [
                        'type' => Type::int(),
                        'resolve' => function ($root) {
                            return $root->items_per_page;
                        }
                    ],
                    'page_number' => [
                        'type' => Type::int(),
                        'resolve' => function ($root) {
                            return $root->getNumberOfPages();
                        }
                    ],
                    'items' => [
                        'type' => Type::listOf($itemType),
                        'resolve' => function ($root) {
                            return $root->fetchItems();
                        }
                    ],
                ];
            }
        ]);
        try {
            $schema = new Schema([
                'query' => $rootType
            ]);
            $result = GraphQL::executeQuery($schema, $query, $rootValue);
            return $result->toArray();
        } catch (Exception $e) {
            throw new Pluf_Exception_BadRequest($e->getMessage());
        }
    }
}