<?php
// Import
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;
/**
 * Render class of GraphQl
 */
class Pluf_GraphQl_Model_Test_1749372910 {
    public function render($rootValue, $query) {
        // render object types variables
         $Test_ModelRecurse = null;
        // render code
         //
        $Test_ModelRecurse = new ObjectType([
            'name' => 'Test_ModelRecurse',
            'fields' => function () use (&$Test_ModelRecurse){
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
                    //Foreinkey value-parent_id: Array(    [type] => Pluf_DB_Field_Foreignkey    [blank] => 1    [model] => Test_ModelRecurse    [relate_name] => children    [name] => parent    [graphql_name] => parent)
                    'parent_id' => [
                            'type' => Type::int(),
                            'resolve' => function ($root) {
                                return $root->parent_id;
                            },
                    ],
                    //Foreinkey object-parent_id: Array(    [type] => Pluf_DB_Field_Foreignkey    [blank] => 1    [model] => Test_ModelRecurse    [relate_name] => children    [name] => parent    [graphql_name] => parent)
                    'parent' => [
                            'type' => $Test_ModelRecurse,
                            'resolve' => function ($root) {
                                return $root->get_parent();
                            },
                    ],
                    // relations: forenkey
                    
                    //Foreinkey list-parent_id: Array()
                    'children' => [
                            'type' => Type::listOf($Test_ModelRecurse),
                            'resolve' => function ($root) {
                                return $root->get_children_list();
                            },
                    ],
                    
                ];
            }
        ]);$rootType =$Test_ModelRecurse;
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
