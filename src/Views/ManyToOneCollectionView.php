<?php
namespace Pluf\Views;

use Pluf\HTTP\Request;
use Pluf\SQL;
use Pluf\Data\Model;

class ManyToOneCollectionView extends AbstractCollectionView
{

    // // TODO: maso, 2017: document
    // private static function CRUD_getParentModel($p)
    // {
    // if (! isset($p['parent'])) {
    // throw new Exception('The parent class was not provided in the parameters.');
    // }
    // return $p['parent'];
    // }

    // /**
    // * Checks if one to many relation exist between two entity
    // *
    // * @param Request $request
    // * @param \Pluf\Data\Model $parent
    // * @param \Pluf\Data\Model $object
    // * @param array $p
    // * parameters
    // * @throws \Pluf\HTTP\Error404 if relation does not exist
    // */
    // private static function CRUD_assertManyToOneRelation($parent, $object, $p)
    // {
    // if (! isset($p['parentKey'])) {
    // throw new Exception('The parentKey was not provided in the parameters.');
    // }
    // $key = $p['parentKey'];
    // if ($object->__get($key) !== $parent->id) {
    // throw new \Pluf\HTTP\Error404('Invalid relation');
    // }
    // }
    public function getItems(Request $request, array $match, array $p)
    {
        if (array_key_exists('parentId', $request->REQUEST)) {
            $parentId = $request->REQUEST['parentId'];
        } else {
            $parentId = $match['parentId'];
        }
        $sql = new SQL($p['parentKey'] . '=%s', $parentId);
        if (isset($p['sql'])) {
            $sqlMain = new SQL($p['sql']);
            $sql = $sqlMain->SAnd($sql);
        }
        $p['sql'] = $sql;
        return $this->findObject($request, $match, $p);
    }

    /**
     * Clear Many to on relations
     *
     * @param \Pluf\HTTP\Request $request
     * @param array $match
     * @param array $p
     * @return NULL
     */
    public function clearManyToOne(Request $request, $match = array(), $p = array())
    {
        // XXX: clean list
        return null;
    }

    public function get($request, $match, $p)
    {
        // Set the default
        if (array_key_exists('modelId', $request->REQUEST)) {
            $modelId = $request->REQUEST['modelId'];
        } else {
            $modelId = $match['modelId'];
        }
        $object = Pluf_Shortcuts_GetObjectOr404(self::CRUD_getModel($p), $modelId);
        if (array_key_exists('parentId', $request->REQUEST)) {
            $parentId = $request->REQUEST['parentId'];
        } else {
            $parentId = $match['parentId'];
        }
        $parent = Pluf_Shortcuts_GetObjectOr404(self::CRUD_getParentModel($p), $parentId);
        // TODO: maso, 2017: assert relation
        self::CRUD_assertManyToOneRelation($parent, $object, $p);
        self::CRUD_checkPreconditions($request, $p, $object, $parent);
        return self::CRUD_response($request, $p, $object);
    }

    public function createManyToOne($request, $match, $p)
    {
        if (array_key_exists('parentId', $request->REQUEST)) {
            $parentId = $request->REQUEST['parentId'];
        } else {
            $parentId = $match['parentId'];
        }
        $parent = Pluf_Shortcuts_GetObjectOr404(self::CRUD_getParentModel($p), $parentId);

        $default = array(
            'extra_context' => array(),
            'extra_form' => array()
        );
        $p = array_merge($default, $p);
        // Set the default
        $model = self::CRUD_getModel($p);
        $object = $model instanceof Model ? $model : new $model();
        $form = Pluf_Shortcuts_GetFormForModel($object, $request->REQUEST, $p['extra_form']);
        $object = $form->save(false);
        $object->{$p['parentKey']} = $parent;
        $object->create();

        return self::CRUD_response($request, $p, $object);
    }

    public function updateManyToOne($request, $match, $p)
    {
        if (array_key_exists('parentId', $request->REQUEST)) {
            $parentId = $request->REQUEST['parentId'];
        } else {
            $parentId = $match['parentId'];
        }
        $parent = Pluf_Shortcuts_GetObjectOr404(self::CRUD_getParentModel($p), $parentId);

        $default = array(
            'extra_context' => array(),
            'extra_form' => array()
        );
        $p = array_merge($default, $p);
        // Set the default
        $model = self::CRUD_getModel($p);
        $object = Pluf_Shortcuts_GetObjectOr404($model, $match['modelId']);
        // TODO: maso, 2017: check relateion
        self::CRUD_assertManyToOneRelation($parent, $object, $p);
        self::CRUD_checkPreconditions($request, $p, $object, $parent);
        $form = Pluf_Shortcuts_GetFormForUpdateModel($object, $request->REQUEST, $p['extra_form']);
        $object = $form->save();
        return self::CRUD_response($request, $p, $object);
    }

    public function deleteManyToOne($request, $match, $p)
    {
        if (array_key_exists('parentId', $request->REQUEST)) {
            $parentId = $request->REQUEST['parentId'];
        } else {
            $parentId = $match['parentId'];
        }
        $parent = Pluf_Shortcuts_GetObjectOr404(self::CRUD_getParentModel($p), $parentId);

        $default = array(
            'extra_context' => array(),
            'extra_form' => array()
        );
        $p = array_merge($default, $p);
        // Set the default
        $model = self::CRUD_getModel($p);
        $object = Pluf_Shortcuts_GetObjectOr404($model, $match['modelId']);
        // TODO: maso, 2017: check relateion
        self::CRUD_assertManyToOneRelation($parent, $object, $p);
        $objectCopy = Pluf_Shortcuts_GetObjectOr404($model, $match['modelId']);
        $objectCopy->id = 0;
        self::CRUD_checkPreconditions($request, $p, $object, $parent);
        $object->delete();
        return self::CRUD_response($request, $p, $objectCopy);
    }
}

