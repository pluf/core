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

use Pluf\HTTP\Error404;
use Pluf\HTTP\Request;
use Pluf\HTTP\Response;
use Pluf;

/**
 * Pluf Request Dispatcher
 *
 * Dispatchs the input request in turned it into a response
 *
 * @author maso
 *        
 */
class Dispatcher
{

    private array $views = [];

    /**
     * Creates new instance of the dispatcher
     *
     * @return Dispatcher
     */
    public static function getInstance(): Dispatcher
    {
        Logger::debug('New instance of Pluf Dispatcher is created.');
        return new Dispatcher();
    }

    /**
     *
     * @param Request $request
     * @return Response
     */
    public function dispatch(Request $request): Response
    {
        $response = new Response(null);
        $appliedProcessors = [];
        $processors = $this->loadConfigProcessors();
        $views = $this->getViews();

        while (true) {
            /*
             * 1. Apply all ready to run processor
             */
            if (! $this->applyRequestProcessors($request, $response, $processors, $appliedProcessors)) {
                $processors = [];
                break;
            }

            /*
             * 2. Find the next loop view
             *
             * Search for appropriate view and set the current view
             */
            $to_match = $request->query;
            $match = [];
            $view = null;
            for ($i = 0; $i < count($views); $i ++) {
                if ($this->isMethodSupported($request, $views[$i]) && //
                preg_match($views[$i]['regex'], $to_match, $match)) {
                    $view = $views[$i];
                    break;
                }
            }

            if (! isset($view)) {
                $response->setStatusCode(404)->setBody(new Error404('Requested View not found.'));
                break;
            }

            // If this is root, follow childrens
            // 0. load processors
            $processors = $this->loadViewProcessors($view);

            // 1. update match
            $request->match = array_merge($match, $request->match);
            // 2. update params
            if (array_key_exists('params', $view) && is_array($view['params'])) {
                $request->params = array_merge($view['params'], $request->params);
            }
            if (isset($view['sub'])) {
                // 1. update view
                $views = $view['sub'];
                if (is_string($views)) {
                    $views = include $views;
                }
                // 2. update query
                $request->query = substr($to_match, strlen($match[0]));
                continue;
            }
            // This is end of the tree
            break;
        }
        $this->applyRequestProcessors($request, $response, $processors, $appliedProcessors);
        return $this->applyResponseProcessors($request, $response, $appliedProcessors);
    }

    /**
     * Loads Ctrl layer
     *
     * @param
     *            string File including the views.
     * @return bool Success.
     */
    public function setViews($file): Dispatcher
    {
        if (is_array($file)) {
            $this->views = $file;
            Logger::debug('An array of contollers is set');
        } elseif (file_exists($file)) {
            $this->views = include $file;
            Logger::debug('Views are loaded from {}', $file);
        }
        return $this;
    }

    /*
     * Gets list of views
     */
    private function getViews(): array
    {
        return $this->views;
    }

    /**
     * Checks if the method of the view is matche with the request
     *
     * @param Request $request
     *            to match
     * @param array $view
     *            view to match with request
     * @return bool true if the request match
     */
    private function isMethodSupported(Request $request, array $view): bool
    {
        if (! isset($view['http-method'])) {
            return true;
        }
        $methods = $view['http-method'];

        return (! is_array($methods) && $methods !== $request->method) || //
        (is_array($methods) && ! in_array($request->method, $methods));
    }

    private function applyResponseProcessors(Request $request, Response $response, $processors): Response
    {
        /*
         * Apply all processor in reverse
         */
        foreach ($processors as $processor) {
            try {
                $response = $processor->response($request, $response);
            } catch (\Exception $ex) {
                Logger::debug('Fail to apply response processor {}. Continue to run rest of the chain.', $processor);
            }
        }
        return $response;
    }

    private function applyRequestProcessors(Request $request, Response $response, $processors, &$applyedProcessors): bool
    {
        /*
         * Apply all processor in reverse
         */
        foreach ($processors as $processor) {
            try {
                array_unshift($applyedProcessors, $processor);
                $processor->request($request);
            } catch (\Exception $ex) {
                $response->setBody($ex)
                    ->setStatusCode(500);
                Logger::debug('Fail to apply request processor {}. The chain is stoped.', $processor);
                return false;
            }
        }
        return true;
    }

    private function loadViewProcessors($view)
    {
        $processors = [];
        if (array_key_exists('processors', $view) && is_array($view['processors'])) {
            foreach ($view['processors'] as $processorName) {
                $processors[] = new $processorName();
            }
        }
        return $processors;
    }

    private function loadConfigProcessors()
    {
        $processors = [];
        /*
         * # Load processors
         *
         * Load all processors based on configuration and then run them on
         * request. and response
         */
        foreach (Pluf::getConfig('processors', []) as $processorName) {
            $processors[] = new $processorName();
        }
        return $processors;
    }
}

