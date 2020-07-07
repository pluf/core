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
namespace Pluf\Template\Context;

use Pluf\Signal;
use Pluf\Template\ContextVars;
use Pluf;

/**
 * Class storing the data that are then used in the template.
 *
 * This class automatically set the 'request' key with the current
 * request and use and add more keys based on the processors.
 */
class Request extends \Pluf\Template\Context
{

    function __construct($request, $vars = array())
    {
        $vars = array_merge(array(
            'request' => $request
        ), $vars);
        foreach (Pluf::f('template_context_processors', array()) as $proc) {
            $vars = array_merge($proc($request), $vars);
        }
        $params = array(
            'request' => $request,
            'context' => $vars
        );
        /**
         * [signal]
         *
         * Pluf_Template_Context_Request::construct
         *
         * [sender]
         *
         * Pluf_Template_Context_Request
         *
         * [description]
         *
         * This signal allows an application to dynamically modify the
         * context array.
         *
         * [parameters]
         *
         * array('request' => $request,
         * 'context' => array());
         */
        Signal::send('Pluf_Template_Context_Request::construct', 'Pluf_Template_Context_Request', $params);
        $this->_vars = new ContextVars($params['context']);
    }
}
