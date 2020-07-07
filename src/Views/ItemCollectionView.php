<?php
namespace Pluf\Views;

use Pluf\HTTP\Request;
use Pluf\Data\Model;

class ItemCollectionView extends AbstractCollectionView
{

    // ---------------------------------------------------------------------------------
    // Item Collection CRUD
    // ---------------------------------------------------------------------------------

    /**
     * Convert a model into the data schema
     *
     * Data schema is used to fill form in clients.
     *
     * @param Request $request
     * @param array $match
     * @param array $p
     * @return array
     */
    public function getSchema(Request $request, $match, $p)
    {
        $modelName = self::getItemModelName($request, $match, $p);
        $obj = new $modelName();
        return $obj->getSchema();
    }

    /**
     * Create an object (Part of the CRUD series).
     *
     * The minimal extra parameter is the model class name. The list
     * of extra parameters is:
     *
     * 'model' - Class name string, required.
     *
     * 'extra_context' - Array of key/values to be added to the
     * context (array())
     *
     * 'extra_form' - Array of key/values to be added to the
     * form generation (array())
     *
     * 'template' - Template to use ('"model class"_create_form.html')
     *
     * @param
     *            Request Request object
     * @param
     *            array Match
     * @param
     *            array Extra parameters
     */
    public function putItems($request, $match, $p)
    {
        $default = array(
            'extra_context' => array(),
            'extra_form' => array()
        );
        $p = array_merge($default, $p);
        // Set the default
        $model = self::CRUD_getModel($p);
        $object = $model instanceof Model ? $model : new $model();
        // Read body of request
        // $entityBody = file_get_contents('php://input', 'r');
        // Check if body is a json array
        // Convert each item to an object model by using Form
        // Save models
        $form = Pluf_Shortcuts_GetFormForModel($object, $request->REQUEST, $p['extra_form']);
        $object = $form->save();

        return self::CRUD_response($request, $p, $object);
    }

    /**
     * List objects (Part of the CRUD series).
     *
     * @param Request $request
     * @param array $match
     */
    public function getItems($request, $match, $p = array())
    {
//         // Create page
//         $builder = new Pluf_Paginator_Builder(self::CRUD_getModelInstance($p));
//         if (array_key_exists('sql', $p)) {
//             if ($p['sql'] instanceof SQL) {
//                 $builder->setWhereClause($p['sql']);
//             } else {
//                 $builder->setWhereClause(new SQL($p['sql']));
//             }
//         }
//         if (array_key_exists('listFilters', $p)) {
//             $builder->setDisplayList($p['listFilters']);
//         }
//         if (array_key_exists('searchFields', $p)) {
//             $builder->setSearchFields($p['searchFields']);
//         }
//         if (array_key_exists('sortFields', $p)) {
//             $builder->setSortFields($p['sortFields']);
//         }
//         if (array_key_exists('view', $p)) {
//             $builder->setView($p['view']);
//         }
//         $builder->setRequest($request);
//         return $builder->build();
    }
}

