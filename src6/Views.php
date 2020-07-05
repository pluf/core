<?php
/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. (http://dpq.co.ir)
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

use Pluf\HTTP\Request;
use Pluf\HTTP\Response;
use Pluf\HTTP\Response\Redirect;

/**
 * Basic Pluf View
 *
 * To start a module as fast as possible, you need a basic views such as model
 * CRUD and list. Here is a list of a basic views which are very common in your
 * desing. This class is a collection of utilities to develop a new view.
 */
class Views
{

    /**
     * Simple redirection view.
     *
     * @param
     *            Request Request object
     * @param
     *            array Match
     * @param
     *            string Redirection URL (not a view)
     */
    public function redirectTo(Request $request, array $match, string $url): Redirect
    {
        return new Response\Redirect($url);
    }

    /**
     * Simple content view.
     *
     * The content is a text and will be route to the client directly.
     *
     * @param
     *            Request Request object
     * @param
     *            array Match
     * @param
     *            string Content of the page
     */
    function simpleContent(Request $request, array $match, $content): Response
    {
        return new Response($content);
    }

    /*
     * fetch model from input request
     *
     * 1. model is defined directly in parameters
     * 2. ?
     */
    private static function getItemModelName(Request $request, array $match, array $params): string
    {
        if (! isset($params['model'])) {
            throw new Exception('The model class was not provided in the parameters.');
        }
        return $params['model'];
    }

    /**
     * Creates a template and returns as result
     *
     * @param \Pluf\HTTP\Request $request
     * @param array $match
     * @return \Pluf\HTTP\Response
     */
    function loadTemplate($request, $match)
    {
        $template = $match[1];
        $extra_context = array();
        // create and show a template
        $context = new Template\Context\Request($request, $extra_context);
        $tmpl = new Template($template);
        return new Response($tmpl->render($context));
    }
}


