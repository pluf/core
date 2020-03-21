<?php
// Import
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;
/**
 * Render class of GraphQl
 */
class Pluf_GraphQl_TestRender_200549611 {
    public function render($rootValue, $query) {
        // render object types variables
         $Test_Model = null;
         $Test_RelatedToTestModel = null;
         $Test_RelatedToTestModel2 = null;
        // render code
         //
        $Test_Model = new ObjectType([
            'name' => 'Test_Model',
            'fields' => function () use (&$Test_RelatedToTestModel, &$Test_RelatedToTestModel2){
                return [
                    // List of basic fields
                    
                    //id: Array(    [type] => Pluf_DB_Field_Sequence    [blank] => 1)
                    'id' => [
                        'type' => Type::int(),
                        'resolve' => function ($root) {
                            return $root->id;
                        },
                    ],
                    //title: Array(    [type] => Pluf_DB_Field_Varchar    [blank] =>     [size] => 100)
                    'title' => [
                        'type' => Type::string(),
                        'resolve' => function ($root) {
                            return $root->title;
                        },
                    ],
                    //description: Array(    [type] => Pluf_DB_Field_Text    [blank] => 1)
                    'description' => [
                        'type' => Type::string(),
                        'resolve' => function ($root) {
                            return $root->description;
                        },
                    ],
                    // relations: forenkey
                    
                    //Foreinkey list-testmodel: Array()
                    'Test_RelatedToTestModel' => [
                            'type' => Type::listOf($Test_RelatedToTestModel),
                            'resolve' => function ($root) {
                                return $root->get_Test_RelatedToTestModel_list();
                            },
                    ],
                    //Foreinkey list-testmodel_1: Array()
                    'first_rttm' => [
                            'type' => Type::listOf($Test_RelatedToTestModel2),
                            'resolve' => function ($root) {
                                return $root->get_first_rttm_list();
                            },
                    ],
                    //Foreinkey list-testmodel_2: Array()
                    'second_rttm' => [
                            'type' => Type::listOf($Test_RelatedToTestModel2),
                            'resolve' => function ($root) {
                                return $root->get_second_rttm_list();
                            },
                    ],
                    
                ];
            }
        ]); //
        $Test_RelatedToTestModel = new ObjectType([
            'name' => 'Test_RelatedToTestModel',
            'fields' => function () use (&$Test_Model){
                return [
                    // List of basic fields
                    
                    //id: Array(    [type] => Pluf_DB_Field_Sequence    [blank] => 1)
                    'id' => [
                        'type' => Type::int(),
                        'resolve' => function ($root) {
                            return $root->id;
                        },
                    ],
                    //Foreinkey value-testmodel: Array(    [type] => Pluf_DB_Field_Foreignkey    [blank] =>     [model] => Test_Model)
                    'testmodel' => [
                            'type' => Type::int(),
                            'resolve' => function ($root) {
                                return $root->testmodel;
                            },
                    ],
                    //dummy: Array(    [type] => Pluf_DB_Field_Varchar    [blank] =>     [size] => 100)
                    'dummy' => [
                        'type' => Type::string(),
                        'resolve' => function ($root) {
                            return $root->dummy;
                        },
                    ],
                    // relations: forenkey
                    
                    
                ];
            }
        ]); //
        $Test_RelatedToTestModel2 = new ObjectType([
            'name' => 'Test_RelatedToTestModel2',
            'fields' => function () use (&$Test_Model){
                return [
                    // List of basic fields
                    
                    //id: Array(    [type] => Pluf_DB_Field_Sequence    [blank] => 1)
                    'id' => [
                        'type' => Type::int(),
                        'resolve' => function ($root) {
                            return $root->id;
                        },
                    ],
                    //Foreinkey value-testmodel_1: Array(    [type] => Pluf_DB_Field_Foreignkey    [blank] =>     [model] => Test_Model    [relate_name] => first_rttm)
                    'testmodel_1' => [
                            'type' => Type::int(),
                            'resolve' => function ($root) {
                                return $root->testmodel_1;
                            },
                    ],
                    //Foreinkey value-testmodel_2: Array(    [type] => Pluf_DB_Field_Foreignkey    [blank] =>     [model] => Test_Model    [relate_name] => second_rttm)
                    'testmodel_2' => [
                            'type' => Type::int(),
                            'resolve' => function ($root) {
                                return $root->testmodel_2;
                            },
                    ],
                    //dummy: Array(    [type] => Pluf_DB_Field_Varchar    [blank] =>     [size] => 100)
                    'dummy' => [
                        'type' => Type::string(),
                        'resolve' => function ($root) {
                            return $root->dummy;
                        },
                    ],
                    // relations: forenkey
                    
                    
                ];
            }
        ]);$rootType =$Test_Model;
        try {
            $schema = new Schema([
                'query' => $rootType
            ]);
            $result = GraphQL::executeQuery($schema, $query, $rootValue);
            return $result->toArray();
        } catch (Exception $e) {
            throw new \Pluf\Exception_BadRequest($e->getMessage());
        }
    }
}
