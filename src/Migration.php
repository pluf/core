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

/**
 * A class to manage the migration of the code from one version to
 * another, upward or downward.
 *
 * You can directly use the migrate.php script.
 *
 * Simple example usage:
 *
 * <pre>
 * $m = new Pluf_Migration('MyApp');
 * $m->migrate();
 *
 * // Install the application MyApp
 * $m = new Pluf_Migration('MyApp');
 * $m->install();
 * // Uninstall the application MyApp
 * $m->unInstall();
 *
 * $m = new Pluf_Migration();
 * $m->migrate(); // migrate all the installed app to the newest version.
 *
 * $m = new Pluf_Migration();
 * $m->migrate(3); // migrate (upgrade or downgrade) to version 3
 * </pre>
 */
class Migration
{

    protected $app = '';

    /**
     * < Application beeing migrated.
     */
    public $apps = array();

    /**
     * < Applications which are going to be migrated.
     */
    public $to_version = null;

    /**
     * < Target version for the migration.
     */
    public $dry_run = false;

    /**
     * < Set to true to not act.
     */
    public $display = false;

    /**
     * < Display on the console what is done.
     */

    /**
     * Create a new migration.
     *
     * @param
     *            mixed Application or array of applications to migrate.
     */
    public function __construct($app = null)
    {
        if (! is_null($app)) {
            if (is_array($app)) {
                $this->apps = $app;
            } else {
                $this->apps = array(
                    $app
                );
            }
        } else {
            $this->apps = Bootstrap::f('installed_apps');
        }
    }

    /**
     * Install the application.
     *
     * Basically run the base install function for each application
     * and then set the version to the latest migration.
     */
    public function install()
    {
        foreach ($this->apps as $app) {
            $this->installApp($app);
        }
        return true;
    }

    /**
     * Init app from data
     *
     * @return boolean
     */
    public function init($tenant = null)
    {
        // TODO: maso, init default tenant
        if (! isset($GLOBALS['_PX_request'])) {
            $GLOBALS['_PX_request'] = new HTTP\Request('/');
        }
        $GLOBALS['_PX_request']->tenant = $tenant;
        foreach ($this->apps as $app) {
            $this->initAppFromConfig($app);
        }
        return true;
    }

    /**
     * Uninstall the application.
     */
    public function unInstall()
    {
        $apps = array_reverse($this->apps);
        foreach ($apps as $app) {
            $this->installApp($app, true);
        }
        return true;
    }

    /**
     * Backup the application.
     *
     * @param
     *            string Path to the backup folder
     * @param
     *            string Backup name (null)
     */
    public function backup($path, $name = null)
    {
        // foreach ($this->apps as $app) {
        // $func = $app . '_Migrations_Backup_run';
        // Pluf::loadFunction($func);
        // if ($this->display) {
        // echo ($func . "\n");
        // }
        // if (! $this->dry_run) {
        // $ret = $func($path, $name);
        // }
        // }
        return true;
    }

    /**
     * Restore the application.
     *
     * @param
     *            string Path to the backup folder
     * @param
     *            string Backup name
     */
    public function restore($path, $name)
    {
        // foreach ($this->apps as $app) {
        // $func = $app . '_Migrations_Backup_restore';
        // Pluf::loadFunction($func);
        // if ($this->display) {
        // echo ($func . "\n");
        // }
        // if (! $this->dry_run) {
        // $ret = $func($path, $name);
        // }
        // }
        return true;
    }

    /**
     * Run the migration.
     */
    public function migrate($to_version = null)
    {
        $this->to_version = $to_version;
        foreach ($this->apps as $app) {
            $this->app = $app;
            $migrations = $this->findMigrations();
            // The run will throw an exception in case of error.
            $this->runMigrations($migrations);
        }
        return true;
    }

    /**
     * Un/Install the given application.
     *
     * @param
     *            string Application to install.
     * @param
     *            bool Uninstall (false)
     */
    public function installApp($app, $uninstall = false)
    {
        if ($uninstall) {
            return $this->uninstallAppFromConfig($app);
        }
        return $this->installAppFromConfig($app);
    }

    /**
     * Find the migrations for the current app.
     *
     * @return array Migrations names indexed by order.
     */
    public function findMigrations()
    {
        // $migrations = array();
        // if (false !== ($mdir = Pluf::fileExists($this->app . '/Migrations'))) {
        // $dir = new DirectoryIterator($mdir);
        // foreach ($dir as $file) {
        // $matches = array();
        // if (! $file->isDot() && ! $file->isDir() && preg_match('#^(\d+)#', $file->getFilename(), $matches)) {
        // $info = pathinfo($file->getFilename());
        // $migrations[(int) $matches[1]] = $info['filename'];
        // }
        // }
        // }
        return $migrations;
    }

    /**
     * Install the application based on application configuration
     *
     * @param string $app
     * @return boolean
     */
    public function installAppFromConfig($app)
    {
        $module = self::getModuleConfig($app);
        if ($module === false) {
            throw new Exception('Module file not found in path');
        }
        $db = Bootstrap::db();
        $schema = new DB\Schema($db);
        // Create modules
        if (array_key_exists('model', $module)) {
            $models = $module['model'];
            foreach ($models as $model) {
                $schema->model = new $model();
                $schema->createTables();
            }
        }
        return true;
    }

    /**
     * Load initial data if exist
     *
     * @param string $app
     */
    public function initAppFromConfig($app)
    {
        $module = self::getModuleConfig($app);
        if ($module === false) {
            throw new Exception('Module file not found in path');
        }

        // Load models
        if (array_key_exists('init', $module)) {
            $models = $module['init'];
            foreach ($models as $model => $values) {
                foreach ($values as $value) {
                    $p = new $model();
                    if (method_exists($p, 'initFromFormData')) {
                        $p->initFromFormData($value);
                    } else {
                        $p->setFromFormData($value);
                    }
                    if (! $p->create()) {
                        throw new Exception('Impossible to load init modules: ' . $model);
                    }
                }
            }
        }

        // Init Releations
        if (array_key_exists('init_assoc', $module)) {
            $relations = $module['init_assoc'];
            foreach ($relations as $models => $relates) {
                $model = explode('|', $models);
                $model0 = trim($model[0]);
                $model1 = trim($model[1]);
                $p0 = new $model0();
                $p1 = new $model1();
                foreach ($relates as $rel) {
                    $p0 = $p0->getOne($rel['from']);
                    $p1 = $p1->getOne($rel['to']);
                    $p0->setAssoc($p1);
                }
            }
        }
    }

    /**
     * Delete application
     *
     * @param string $app
     */
    public function uninstallAppFromConfig($app)
    {
        $module = self::getModuleConfig($app);
        if ($module === false) {
            throw new Exception('Module file not found in path');
        }
        $db = Bootstrap::db();
        $schema = new DB\Schema($db);
        // Delete modules
        if (array_key_exists('model', $module)) {
            $models = $module['model'];
            foreach ($models as $model) {
                $schema->model = new $model();
                $schema->dropTables();
            }
        }
        // TODO: delete permissions
        // TODO: delete monitors
        return true;
    }

    /**
     * Load module configuration
     *
     * @param string $app
     * @return boolean|mixed
     */
    public static function getModuleConfig($app)
    {
        $moduleName = "Pluf\\" . $app . "\\Module";
        $file = $moduleName::moduleJsonPath;
        $myfile = fopen($file, "r") or die("Unable to open module.json!");
        $json = fread($myfile, filesize($file));
        fclose($myfile);
        return json_decode($json, true);
    }

    /**
     * Run the migrations.
     *
     * From an array of possible migrations, it will first get the
     * current version of the app and then based on $this->to_version
     * will run the migrations in the right order or do nothing if
     * nothing to be done.
     *
     * @param
     *            array Possible migrations.
     */
    public function runMigrations($migrations)
    {
        if (empty($migrations)) {
            return;
        }
        $current = $this->getAppVersion($this->app);
        if ($this->to_version === null) {
            $to_version = max(array_keys($migrations));
        } else {
            $to_version = $this->to_version;
        }
        if ($to_version == $current) {
            return; // Nothing to do
        }
        $the_way = 'up'; // Tribute to Pat Metheny
        if ($to_version > $current) {
            // upgrade
            $min = $current + 1;
            $max = $to_version;
        } else {
            // downgrade
            $the_way = 'do';
            $max = $current;
            $min = $to_version + 1;
        }
        // Filter the migrations
        $to_run = array();
        foreach ($migrations as $order => $name) {
            if ($order < $min or $order > $max) {
                continue;
            }
            if ($the_way == 'up') {
                $to_run[] = array(
                    $order,
                    $name
                );
            } else {
                array_unshift($to_run, array(
                    $order,
                    $name
                ));
            }
        }
        asort($to_run);
        // Run the migrations
        foreach ($to_run as $migration) {
            $this->runMigration($migration, $the_way);
        }
    }

    /**
     * Run the given migration.
     */
    public function runMigration($migration, $the_way = 'up')
    {
        $target_version = ($the_way == 'up') ? $migration[0] : $migration[0] - 1;
        if ($this->display) {
            echo ($migration[0] . ' ' . $migration[1] . ' ' . $the_way . "\n");
        }
        if (! $this->dry_run) {
            if ($the_way == 'up') {
                $func = $this->app . '_Migrations_' . $migration[1] . '_up';
            } else {
                $func = $this->app . '_Migrations_' . $migration[1] . '_down';
            }
            Bootstrap::loadFunction($func);
            $func(); // Real migration run
            $this->setAppVersion($this->app, $target_version);
        }
    }

    /**
     * Set the application version.
     *
     * @param
     *            string Application
     * @param
     *            int Version
     * @return true
     */
    public function setAppVersion($app, $version)
    {
        $gschema = new DB\SchemaInfo();
        $sql = new SQL('application=%s', $app);
        $appinfo = $gschema->getList(array(
            'filter' => $sql->gen()
        ));
        if ($appinfo->count() == 1) {
            $appinfo[0]->version = $version;
            $appinfo[0]->update();
        } else {
            $schema = new DB\SchemaInfo();
            $schema->application = $app;
            $schema->version = $version;
            $schema->create();
        }
        return true;
    }

    /**
     * Remove the application information.
     *
     * @param
     *            string Application
     * @return true
     */
    public function delAppInfo($app)
    {
        $gschema = new DB\SchemaInfo();
        $sql = new SQL('application=%s', $app);
        $appinfo = $gschema->getList(array(
            'filter' => $sql->gen()
        ));
        if ($appinfo->count() == 1) {
            $appinfo[0]->delete();
        }
        return true;
    }

    /**
     * Get the current version of the app.
     *
     * @param
     *            string Application.
     * @return int Version.
     */
    public function getAppVersion($app)
    {
        try {
            $db = Bootstrap::db();
            $res = $db->select('SELECT version FROM ' . $db->pfx . 'schema_info WHERE application=' . $db->esc($app));
            return (int) $res[0]['version'];
        } catch (Exception $e) {
            // We should not be here, only in the case of nothing
            // installed. I am not sure if this is a good way to
            // handle this border case anyway. Maybe better to have an
            // 'install' method to run all the migrations in order.
            return 0;
        }
    }
}