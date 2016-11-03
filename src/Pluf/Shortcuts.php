<?php
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

/**
 * Shortcut methods to perform some operations.
 *
 * Modelled on the django.shortcuts.
 */

/**
 * Get an object by id or raise a 404 error.
 *
 * @param
 *            string Model
 * @param
 *            int Id of the model to get
 * @return Object The found object.
 */
function Pluf_Shortcuts_GetObjectOr404 ($object, $id)
{
    $item = new $object($id);
    if ((int) $id > 0 && $item->id == $id) {
        return $item;
    }
    throw new Pluf_HTTP_Error404("Object not found (" . $object . "," . $id . ")");
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
function Pluf_Shortcuts_GetOneOr404 ($object, $bsql, $psql)
{
    $sql = new Pluf_SQL($bsql, $psql);
    $item = Pluf::factory($object)->getOne(array(
            'filter' => $sql->gen()
    ));
    if ($item != null) {
        return $item;
    }
    throw new Pluf_HTTP_Error404();
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
 * @return Pluf_HTTP_Response The response with the rendered template
 */
function Pluf_Shortcuts_RenderToResponse ($tplfile, $params, $request = null)
{
    $tmpl = new Pluf_Template($tplfile);
    if (is_null($request)) {
        $context = new Pluf_Template_Context($params);
    } else {
        $context = new Pluf_Template_Context_Request($request, $params);
    }
    return new Pluf_HTTP_Response($tmpl->render($context));
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
 * @return Object Form for this model.
 */
function Pluf_Shortcuts_GetFormForModel ($model, $data = null, $extra = array(), 
        $label_suffix = null)
{
    $extra['model'] = $model;
    return new Pluf_Form_Model($data, $extra, $label_suffix);
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
function Pluf_Shortcuts_GetFormForUpdateModel ($model, $data = null, $extra = array(),
    $label_suffix = null)
{
    $extra['model'] = $model;
    return new Pluf_Form_UpdateModel($data, $extra, $label_suffix);
}

function Pluf_Shortcuts_LoadModels ($moduleJson)
{
    $db = Pluf::db();
    $schema = new Pluf_DB_Schema($db);
    if(!isset($moduleJson['model'])){
        return;
    }
    $models = $moduleJson['model'];
    foreach ($models as $model) {
        $schema->model = new $model();
        $schema->createTables();
    }
}

function Pluf_Shortcuts_LoadPermissions ($moduleJson)
{
    $db = Pluf::db();
    $schema = new Pluf_DB_Schema($db);
    if(!isset($moduleJson['permisson'])){
        return;
    }
    $permissons = $moduleJson['permisson'];
    foreach ($permissons as $permisson) {
        $p = new Pluf_Permission();
        $p->name = $permisson['name'];
        $p->code_name = $permisson['code_name'];
        $p->description = $permisson['description'];
        $p->application = $moduleJson['name'];
        $p->version = $moduleJson['version'];
        $p->create();
    }
}

function Pluf_Shortcuts_Monitors ($moduleJson)
{
    $db = Pluf::db();
    $schema = new Pluf_DB_Schema($db);
    if(!isset($moduleJson['monitor'])){
        return;
    }
    $monitors = $moduleJson['monitor'];
    foreach ($monitors as $monitor) {
        $model = new Pluf_Monitor();
        $model->setFromFormData($monitor);
        $model->create();
    }
}
