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

/**
 * Abstract definistion of modules
 *
 * @author maso
 *        
 */
abstract class Module
{

    const MODULE_DEFAULT_URL_PATH = 'urls.php';

    /**
     * Module key
     *
     * @var string
     */
    private ?string $key = null;

    /**
     * Flag to show state of the module
     *
     * If the module is loaded, then this flag is true.
     *
     * @var boolean
     */
    private bool $loaded = false;

    /**
     * Creates new instance of a module with the given key
     *
     * @param string $key
     *            of the module
     */
    public function __construct(string $key)
    {
        $this->key = $key;
        // TODO: maso, 2020: load if key is empty
    }

    /**
     * Checks if the module is loaded.
     *
     * @return boolean
     */
    public function isLoaded()
    {
        return $this->loaded;
    }

    /**
     * Set the module as loaded.
     *
     * @param bool $loaded
     *            state of the module
     */
    private function setLoaded(bool $loaded)
    {
        $this->loaded = $loaded;
    }

    /**
     * Gets the key of the module
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Load and return default modul views
     *
     * @return array of views
     */
    public function getViews()
    {
        $apiPrefix = \Pluf::f('view_api_prefix', '');
        $apiBase = \Pluf::f('view_api_base', '');

        $reflection = new \ReflectionObject($this);
        $directory = dirname($reflection->getFileName());
        if (is_readable($directory . '/' . self::MODULE_DEFAULT_URL_PATH)) {
            return [
                array(
                    'app' => $reflection->getNamespaceName(),
                    'regex' => '#^' . $apiPrefix . '/' . strtolower($this->getKey()) . '#',
                    'base' => $apiBase,
                    'sub' => require $directory . '/' . self::MODULE_DEFAULT_URL_PATH
                )
            ];
        }
        return array();
    }

    /**
     * Initialize a module
     *
     * @param \Pluf $bootstrap
     */
    public abstract function init(\Pluf $bootstrap): void;

    /**
     * Gets list of modules
     *
     * @return Module[] list of modules
     */
    public static function getModules(): array
    {
        $apps = \Pluf::f('installed_apps', array());
        $modules = array();
        foreach ($apps as $app) {
            $moduleName = "Pluf\\" . $app . "\\Module";
            if (class_exists($moduleName)) {
                $modules[] = new $moduleName($app);
            }
        }

        // TODO: maos, 2020: catch the modules
        return $modules;
    }

    /**
     * Loads all module controllers
     *
     * @param Request $request
     * @return array
     */
    public static function loadControllers(?Request $request = null): array
    {
        $modules = self::getModules();
        $views = array();
        foreach ($modules as $module) {
            $mviews = $module->getViews();
            $views = array_merge_recursive($views, $mviews);
        }
        return $views;
    }

    /**
     * Load all modules
     *
     * NOTE: This method is not intend to called directlly.
     */
    public static function loadModules(): void
    {
        $modules = self::getModules();
        $bootstrap = new \Pluf();
        foreach ($modules as $module) {
            $loaded = false;
            try {
                $module->init($bootstrap);
                $loaded = true;
            } catch (\Exception $ex) {
                Logger::error('Fail to load module ' . $module->getKey(), $ex);
            }
            $module->setLoaded($loaded);
        }
    }
}

