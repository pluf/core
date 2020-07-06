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
namespace Pluf;

use Pluf\HTTP\Response;

// Pluf_Shortcuts_RenderToResponse
// Pluf_Shortcuts_GetFormForUpdateModel
// Pluf_Shortcuts_GetFormForUpdateModel
// Pluf_Shortcuts_folderSize
// Pluf_Shortcuts_GetAssociationTableName
// Pluf_Shortcuts_GetListCount
// Pluf_Shortcuts_GetForeignKeyName
// Pluf_Shortcuts_GetForeignKeyName
class Shortcuts
{

    public static function GetRequestParam($request, $id)
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
     */
    public static function GetObjectOr404($object, $id)
    {
        $item = new $object($id);
        if ((int) $id > 0 && $item->id == $id) {
            return $item;
        }
        throw new \Pluf\HTTP\Error404("Object not found (" . $object . "," . $id . ")");
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
     *            \Pluf\HTTP\Request Request object (null)
     * @return \Pluf\HTTP\Response The response with the rendered template
     */
    public static function RenderToResponse($tplfile, $params, $request = null)
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
     * Compute folder size
     *
     * @param string $dir
     * @return number
     */
    function folderSize($dir)
    {
        $count_size = 0;
        $count = 0;
        $dir_array = scandir($dir);
        foreach ($dir_array as /* $key =>  */$filename) {
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
     * Returns column name for given model as foreign key in an association table.
     *
     * @param string $modelName
     *            name of model (of type \Pluf\Data\Model)
     * @return string column name for given model as foreign key in an association table.
     * @deprecated use Schema::getAssocField($model);
     */
    public static function GetForeignKeyName($modelName)
    {
        return strtolower($modelName) . '_id';
    }

    /**
     * Returns list count for given request.
     *
     * If count is not set in request or count is more than a threshold (50) returns a default value (50).
     *
     * @param \Pluf\HTTP\Request $request
     * @return number
     */
    public static function GetListCount($request)
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




