<?php
namespace Pluf\Views;

use Pluf;
use Pluf\HTTP\Request;
use Pluf\HTTP\RequestMapper;
use Pluf\HTTP\Error403;
use Pluf\Data\Query;
use Pluf\HTTP\Error404;
use Pluf\HTTP\Error500;
use Pluf\ObjectValidator;
use Pluf\ObjectMapper;

class ItemView extends \Pluf\Views
{

    /*
     * Fetchs model name from inputs
     *
     * 1. from params
     */
    protected function getModelName(Request $request, array $match, array $params): string
    {
        if (isset($params['model'])) {
            return $params['model'];
        }
        // TODO: maso, 2020: search in match and request form model name
        throw new Error500('The model class was not provided in the parameters.');
    }

    /*
     * Call determined preconditions in the $p and check if preconditions are statisfied.
     * Preconditions could be determined in the $p array as 'precond' feild.
     *
     * A precondition is a function which is called in some situations before some actions.
     * Here is an example to define preconditions in $p:
     *
     * $p = array(
     * 'precond' => array(
     * 'My_Precondition_Class::precondFunc',
     * 'My_Precondition_Class::precondFunc2'
     * )
     * );
     *
     * Value of 'precond' could be array or a single item.
     *
     * Each precondition function will be called with three argument respectively as following:
     * - $request: Pluf_HTTp_Request
     * - $object: Pluf_Model
     * - $parent: Pluf_Model, which is a parent of $object model
     *
     */
    protected function checkPreconditions(Request $request, array $match, array $params, Object $item): void
    {
        if (! isset($params['precond'])) {
            return;
        }
        $preconds = $params['precond'];
        if (! is_array($preconds)) {
            $preconds = array(
                $preconds
            );
        }
        foreach ($preconds as $precond) {
            $res = call_user_func_array(explode('::', $precond), [
                $request,
                $match,
                $params,
                $item
            ]);
            if ($res !== true) {
                // TODO: maso, 2020: throw error number
                throw new Error403('CRUD precondition is not satisfied.');
            }
        }
    }

    /*
     * Finds item with the $modelId
     */
    protected function getObjectOr404(string $modelName, $modelId)
    {
        $items = Pluf::getDataRepository($modelName)->get(new Query([
            'filter' => [
                [
                    'id',
                    '=',
                    $modelId
                ]
            ]
        ]));
        if (sizeof($items) == 0) {
            throw new Error404('Request resource with ID:' . $modelId . ' not found');
        }
        return $items[0];
    }

    /**
     * Access an item
     *
     *
     * 'model' - Class name string, required.
     *
     *
     * 'modelId' - Id of of the current model to update
     *
     * @param
     *            Request Request object
     * @param
     *            array Match
     * @param
     *            array Extra parameters
     * @return object Response object (can be a redirect)
     */
    public function getItem(Request $request, array $match, array $params)
    {
        // Set the default
        $modelName = self::getModelName($params);
        $modelId = $match['modelId'];
        $item = self::getObjectOr404($modelName, $modelId);
        self::checkPreconditions($request, $match, $params, $item);
        return $item;
    }

    /**
     * Delete an object (Part of the CRUD series).
     *
     * The minimal extra parameter is the model class name. The list
     * of extra parameters is:
     *
     * 'model' - Class name string, required.
     *
     * 'post_delete_redirect' - View to redirect after saving, required.
     *
     * 'id' - Index in the match to fin the id of the object to delete (1)
     *
     * 'login_required' - Do we require login (false)
     *
     * 'template' - Template to use ('"model class"_confirm_delete.html')
     *
     * 'post_delete_redirect_keys' - Which keys of the model to pass to
     * the view (array())
     *
     * 'extra_context' - Array of key/values to be added to the
     * context (array())
     *
     * Other extra parameters may be as follow:
     *
     * 'permanently' - if is exist and its value is false the object will not be deleted permanently and
     * only the `deleted` field of that will be set to true. Note that this option assumes that the removing
     * object has a feild named `deleted`
     *
     * @param Request $request
     *            object
     * @param array $match
     * @param array $p
     *            parameters
     * @return Object deleted item
     */
    public function deleteItem(Request $request, array $match, array $params)
    {
        $item = self::getItem($request, $match, $params);
        $item->delete();
        return $item;
    }

    /**
     * Update an object (Part of the CRUD series).
     *
     * The minimal extra parameter is the model class name. The list
     * of extra parameters is:
     *
     * 'model' - Class name string, required.
     *
     * 'model_id' - Id of of the current model to update
     *
     * 'extra_context' - Array of key/values to be added to the
     * context (array())
     *
     * 'extra_form' - Array of key/values to be added to the
     * form generation (array())
     *
     * 'template' - Template to use ('"model class"_update_form.html')
     *
     * @param Request $request
     *            object
     * @param array $match
     * @param array $p
     *            parameters
     * @return Object updated item
     */
    public function updateItem(Request $request, array $match, array $params)
    {
        $item = self::getItem($request, $match, $params);
        $objectName = get_class($item);
        $mapper = ObjectMapper::getInstance($request);
        if (! $mapper->hasMore()) {
            throw new Error403('No item in request to update');
        }
        $newItem = $mapper->next(new $objectName());
        $newItem->id = $item->id;
        ObjectValidator::getInstance()->check($newItem);
        $item = Pluf::getDataRepository($objectName)->update($newItem);
        return $item;
    }
}

