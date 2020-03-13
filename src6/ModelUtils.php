<?php
namespace Pluf;

use Pluf;
use Pluf_Model;

/**
 * Utilities to work with model
 *
 * @author maso
 *        
 */
class ModelUtils
{

    public const MODEL_CACHE_KEY = '_PX_models_init_cache';

    public const MODEL_KEY = '_PX_models';

    public static function getModelCacheKey(Pluf_Model $model)
    {
        $objr = new \ReflectionObject($model);
        $key = $objr->getName();
        if (strpos($key, '\\')) {
            $key = '\\' . $key;
        }
        return $key;
    }

    public static function loadFromCache(Pluf_Model $model): bool
    {
        $key = self::getModelCacheKey($model);
        if (isset($GLOBALS[self::MODEL_CACHE_KEY][$key])) {
            $init_cache = $GLOBALS[self::MODEL_CACHE_KEY][$key];

            $model->_cache = $init_cache['cache'];
            $model->_m = $init_cache['m'];
            $model->_a = $init_cache['a'];
            $model->_fk = $init_cache['fk'];
            $model->_data = $init_cache['data'];

            return true;
        }
        return false;
    }

    public static function putModelToCache(Pluf_Model $model): void
    {
        $key = self::getModelCacheKey($model);
        if (isset($GLOBALS[self::MODEL_CACHE_KEY][$key])) {
            return;
        }
        $GLOBALS[self::MODEL_CACHE_KEY][$key] = array(
            'cache' => $model->_cache,
            'm' => $model->_m,
            'a' => $model->_a,
            'fk' => $model->_fk,
            'data' => $model->_data
        );
    }

    public static function getRelatedModels(Pluf_Model $model, string $type)
    {
        $key = self::getModelCacheKey($model);
        $relations = [];
        if (isset($GLOBALS['_PX_models_related'][$type][$key])) {
            $relations = $GLOBALS['_PX_models_related'][$type][$key];
        }
        return $relations;
    }

    /**
     * Get the model relations and signals.
     *
     * If not in debug mode, it will automatically cache the
     * information. This allows one include file when many
     * applications and thus many includes are needed.
     *
     * Signals and relations are cached in the same file as the way to
     * go for signals is to put them in the relations.php file.
     *
     * @param
     *            bool Use the cache (true)
     */
    public static function loadRelations($usecache = true)
    {
        $GLOBALS[ModelUtils::MODEL_KEY] = array();
        $GLOBALS[ModelUtils::MODEL_CACHE_KEY] = array();

        $apps = Pluf::f('installed_apps', array());
        $cache = Pluf::f('tmp_folder', '/tmp') . '/Pluf_relations_cache_' . md5(serialize($apps)) . '.phps';

        if ($usecache and file_exists($cache)) {
            list ($GLOBALS[ModelUtils::MODEL_KEY], $GLOBALS['_PX_models_related'], $GLOBALS['_PX_signal']) = include $cache;
            return;
        }

        $m = $GLOBALS[ModelUtils::MODEL_KEY];
        foreach ($apps as $app) {
            $moduleName = "\\Pluf\\" . $app . "\\Module";
            if (class_exists($moduleName)) {
                // Load PSR4 modules
                $m = array_merge_recursive($m, $moduleName::relations);
            } else {
                // Load PSR1 modules
                $m = array_merge_recursive($m, require $app . '/relations.php');
            }
        }
        $GLOBALS[ModelUtils::MODEL_KEY] = $m;

        $_r = array(
            'relate_to' => array(),
            'relate_to_many' => array()
        );
        foreach ($GLOBALS[ModelUtils::MODEL_KEY] as $model => $relations) {
            foreach ($relations as $type => $related) {
                foreach ($related as $related_model) {
                    if (! isset($_r[$type][$related_model])) {
                        $_r[$type][$related_model] = array();
                    }
                    $_r[$type][$related_model][] = $model;
                }
            }
        }
        $_r['foreignkey'] = $_r['relate_to'];
        $_r['manytomany'] = $_r['relate_to_many'];
        $GLOBALS['_PX_models_related'] = $_r;

        // $GLOBALS['_PX_signal'] is automatically set by the require
        // statement and possibly in the configuration file.
        if ($usecache) {
            $s = var_export(array(
                $GLOBALS[ModelUtils::MODEL_KEY],
                $GLOBALS['_PX_models_related'],
                $GLOBALS['_PX_signal']
            ), true);
            if (@file_put_contents($cache, '<?php return ' . $s . ';' . "\n", LOCK_EX)) {
                chmod($cache, 0755);
            }
        }
    }

    public static function getModelsFromModule(string $app): array
    {
        $path = $app . '/module.json';

        $moduleName = "\\Pluf\\" . $app . "\\Module";
        if (class_exists($moduleName)) {
            $modulRef = new \ReflectionClass($moduleName);
            $path = dirname($modulRef->getFileName()) . '/module.json';
        }

        if (false == ($file = Pluf::fileExists($path))) {
            return [];
        }
        $myfile = fopen($file, "r") or die("Unable to open module.json!");
        $json = fread($myfile, filesize($file));
        fclose($myfile);
        $moduel = json_decode($json, true);
        if (! array_key_exists('model', $moduel)) {
            return [];
        }
        return $moduel['model'];
    }
}

