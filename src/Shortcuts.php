<?php
namespace Pluf;

use Pluf\HTTP\Response;
use Pluf\HTTP\Request;
use Pluf\Form\FormModelUpdate;

class Shortcuts
{

    /*
     * This file is part of Pluf Framework, a simple PHP Application Framework.
     * Copyright (C) 2010-2020 Phoinex Scholars Co. http://dpq.co.ir
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
    public static function getRequestParamOr403($request, $id)
    {
        if (array_key_exists($id, $request->REQUEST)) {
            return $request->REQUEST[$id];
        }
        throw new Exception(sprintf("Parameter not found (name: %s).", $id));
    }

    public static function getRequestParam($request, $id)
    {
        if (array_key_exists($id, $request->REQUEST)) {
            return $request->REQUEST[$id];
        }
        return null;
    }

    /**
     * Get an object by id or raise a 404 error.
     *
     * @param
     *            string Model
     * @param
     *            int Id of the model to get
     * @return Model The found object.
     */
    public static function getObjectOr404($object, $id)
    {
        $item = new $object($id);
        if ((int) $id > 0 && $item->id == $id) {
            return $item;
        }
        throw new Exception("Object not found (" . $object . "," . $id . ")");
    }

    /**
     * Get an object by SQL or raise a 404 error.
     *
     * Usage:
     * <pre>
     * $obj = Pluf_Shortcuts_GetOneOr404('MyApp_Model',
     * 'path=%s AND status=%s',
     * array('welcome', 1));
     * </pre>
     *
     * @param
     *            string Model
     * @param
     *            string Base SQL request
     * @param
     *            string Parameters for the base SQL
     * @return Object The found object
     */
    function getOneOr404($object, $bsql, $psql)
    {
        $sql = new SQL($bsql, $psql);
        $item = Bootstrap::factory($object)->getOne(array(
            'filter' => $sql->gen()
        ));
        if ($item != null) {
            return $item;
        }
        throw new Exception();
    }

    /**
     * Render a template file and an array as a reponse.
     *
     * If a none null request object is given, the context used will
     * automatically be a Pluf_Template_Context_Request context and thus
     * the context will be populated using the
     * 'template_context_processors' functions.
     *
     * @param
     *            string Template file name
     * @param
     *            array Associative array for the context
     * @param
     *            Pluf_HTTP_Request Request object (null)
     * @return Response The response with the rendered template
     */
    public static function renderToResponse($tplfile, $params, $request = null)
    {
        $tmpl = new Template($tplfile);
        if (is_null($request)) {
            $context = new Template\Context($params);
        } else {
            $context = new Template\Context\Request($request, $params);
        }
        return new Response($tmpl->render($context));
    }

    /**
     * Get a given form from a model.
     *
     * @param
     *            Object The model.
     * @param
     *            array Data to bound the form (null)
     * @param
     *            array Extra parameters (array())
     * @param
     *            string Label suffix (null)
     * @return FormModelUpdate Form for this model.
     */
    public static function getFormForModel($model, $data = null, $extra = array(), $label_suffix = null)
    {
        $extra['model'] = $model;
        return new Form\FormModelCreate($data, $extra, $label_suffix);
    }

    /**
     * Get a given form from a model to update.
     *
     * @param
     *            Object The model.
     * @param
     *            array Data to bound the form (null)
     * @param
     *            array Extra parameters (array())
     * @param
     *            string Label suffix (null)
     * @return Object Form to update for this model.
     */
    public static function getFormForUpdateModel($model, $data = null, $extra = array(), $label_suffix = null)
    {
        $extra['model'] = $model;
        return new Form\FormModelUpdate($data, $extra, $label_suffix);
    }

    /**
     * Compute folder size
     *
     * @param string $dir
     * @return number
     */
    public static function folderSize($dir)
    {
        $count_size = 0;
        $count = 0;
        $dir_array = scandir($dir);
        foreach ($dir_array as $key => $filename) {
            if ($filename != ".." && $filename != ".") {
                if (is_dir($dir . "/" . $filename)) {
                    $new_foldersize = Pluf_Shortcuts_folderSize($dir . "/" . $filename);
                    $count_size = $count_size + $new_foldersize;
                } else if (is_file($dir . "/" . $filename)) {
                    $count_size = $count_size + filesize($dir . "/" . $filename);
                    $count ++;
                }
            }
        }
        return $count_size;
    }

    /**
     * Returns association table name (without prefix) for given models.
     *
     * @param string $modelName1
     *            name of model (of type Model)
     * @param string $modelName2
     *            name of model (of type Model)
     * @return string name of association table for given modeles.
     *        
     * @deprecated use ModelUtils::getAssocTable($from,$to);
     */
    public static function getAssociationTableName($from, $to)
    {
        return ModelUtils::getAssocTable($from, $to);
    }

    /**
     * Returns column name for given model as foreign key in an association table.
     *
     * @param string $modelName
     *            name of model (of type Model)
     * @return string column name for given model as foreign key in an association table.
     */
    public static function getForeignKeyName(Model $model): string
    {
        return $model->tableName . '_id';
    }

    /**
     * Returns list count for given request.
     *
     * If count is not set in request or count is more than a threshold (50) returns a default value (50).
     *
     * @param Request $request
     *            to process
     * @return number
     */
    public static function getListCount($request)
    {
        $count = 50;
        if (array_key_exists('_px_ps', $request->GET)) {
            $count = $request->GET['_px_ps'];
            if ($count == 0 || $count > 50) {
                $count = 50;
            }
        }
        return $count;
    }
}
