<?php
// Import
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;
/**
 * Render class of GraphQl
 */
class Pluf_GraphQl_TestRender_2002679244 {
    public function render($rootValue, $query) {
        // render object types variables
         $Test_ModelCount = null;
        // render code
         //
        $Test_ModelCount = new ObjectType([
            'name' => 'Test_ModelCount',
            'fields' => function () {
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
                    
                    
                ];
            }
        ]);$rootType =$Test_ModelCount;
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
