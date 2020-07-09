<?php
namespace Pluf\Data;

use Pluf;

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

    public const MODEL_VIEW_CACHE_KEY = '_PX_models_views';

    public static function getModelCacheKey($model): string
    {
        if (! is_string($model)) {
            $objr = new \ReflectionObject($model);
            $model = $objr->getName();
        }
        if (strpos($model, '\\')) {
            $model = '\\' . $model;
        }
        return $model;
    }

    public static function loadFromCache(\Pluf\Data\Model $model): bool
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

    public static function putModelToCache(\Pluf\Data\Model $model): void
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

    /**
     * Gets list of related model to the type
     *
     * @param ModelDescription|string $type
     * @return array
     */
    public static function getRelatedModels($type): array
    {
        if ($type instanceof ModelDescription) {
            $modelDescription = $type;
        } else {
            $modelDescription = ModelDescription::getInstance($type);
        }
        $preModels = [];
        foreach ($modelDescription as $property) {
            if ($property->isRelation()) {
                $name = ModelUtils::getModelCacheKey($property->inverseJoinModel);
                if (! in_array($name, $preModels)) {
                    array_push($preModels, $name);
                }
            }
        }
        return $preModels;
    }

    /**
     * Gets relation property from $smd to $tmd with name $relation
     *
     * @param ModelDescription $smd
     * @param ModelDescription $tmd
     * @param string $relation
     * @throws Exception
     * @return ModelProperty
     */
    public static function getRelationProperty(ModelDescription $smd, ModelDescription $tmd, ?string $relation = null): ModelProperty
    {
        if (! isset($relation)) {
            foreach ($smd as $property) {
                if ($property->isRelation() && $tmd->isInstanceOf($property->inverseJoinModel)) {
                    return $property;
                }
            }
        }

        // check relation
        $relationProperty = $smd->$relation;
        if (! isset($relationProperty)) {
            throw new Exception([
                'message' => 'The property wtih name {name} does not exist in type {type}',
                'type' => $smd->type,
                'name' => $relation->name
            ]);
        }
        // check type
        if (! $relationProperty->isRelation()) {
            throw new Exception([
                'message' => 'The property wtih name {name} is not a relation type in {type}',
                'type' => $smd->type,
                'name' => $relation->name
            ]);
        }
        // check target model
        if (! $tmd->isInstanceOf($relationProperty->inverseJoinModel)) {
            throw new Exception([
                'message' => 'The type {target} does not match with relation type {type} from {source}',
                'type' => $relationProperty->inverseJoinModel,
                'source' => $smd->type,
                'target' => $tmd->type
            ]);
        }

        return $relationProperty;
    }

    // /**
    // * Get the model relations and signals.
    // *
    // * If not in debug mode, it will automatically cache the
    // * information. This allows one include file when many
    // * applications and thus many includes are needed.
    // *
    // * Signals and relations are cached in the same file as the way to
    // * go for signals is to put them in the relations.php file.
    // *
    // * @param
    // * bool Use the cache (true)
    // */
    // public static function loadRelations($usecache = true)
    // {
    // $GLOBALS[ModelUtils::MODEL_KEY] = array();
    // $GLOBALS[ModelUtils::MODEL_CACHE_KEY] = array();

    // $apps = Pluf::f('installed_apps', array());
    // $cache = Pluf::f('tmp_folder', '/tmp') . '/Pluf_relations_cache_' . md5(serialize($apps)) . '.phps';

    // if ($usecache and file_exists($cache)) {
    // list ($GLOBALS[ModelUtils::MODEL_KEY], $GLOBALS['_PX_models_related'], $GLOBALS['_PX_signal']) = include $cache;
    // return;
    // }

    // $m = $GLOBALS[ModelUtils::MODEL_KEY];
    // foreach ($apps as $app) {
    // $moduleName = "\\Pluf\\" . $app . "\\Module";
    // if (class_exists($moduleName)) {
    // // Load PSR4 modules
    // $m = array_merge_recursive($m, $moduleName::relations);
    // } else {
    // // Load PSR1 modules
    // $m = array_merge_recursive($m, require $app . '/relations.php');
    // }
    // }
    // $GLOBALS[ModelUtils::MODEL_KEY] = $m;

    // $_r = array(
    // 'relate_to' => array(),
    // 'relate_to_many' => array()
    // );
    // foreach ($GLOBALS[ModelUtils::MODEL_KEY] as $model => $relations) {
    // foreach ($relations as $type => $related) {
    // foreach ($related as $related_model) {
    // if (! isset($_r[$type][$related_model])) {
    // $_r[$type][$related_model] = array();
    // }
    // $_r[$type][$related_model][] = $model;
    // }
    // }
    // }
    // $_r[Engine::FOREIGNKEY] = $_r['relate_to'];
    // $_r[Engine::MANY_TO_MANY] = $_r['relate_to_many'];
    // $GLOBALS['_PX_models_related'] = $_r;

    // // $GLOBALS['_PX_signal'] is automatically set by the require
    // // statement and possibly in the configuration file.
    // if ($usecache) {
    // $s = var_export(array(
    // $GLOBALS[ModelUtils::MODEL_KEY],
    // $GLOBALS['_PX_models_related'],
    // $GLOBALS['_PX_signal']
    // ), true);
    // if (@file_put_contents($cache, '<?php return ' . $s . ';' . "\n", LOCK_EX)) {
    // chmod($cache, 0755);
    // }
    // }
    // }
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

    public static function loadViewsFromCache(\Pluf\Data\Model $model)
    {
        $key = self::getModelCacheKey($model);
        if (isset($GLOBALS[self::MODEL_VIEW_CACHE_KEY][$key])) {
            return $GLOBALS[self::MODEL_VIEW_CACHE_KEY][$key];
        }
        return false;
    }

    public static function putViewsToCache(\Pluf\Data\Model $model, array $views)
    {
        $key = self::getModelCacheKey($model);
        $GLOBALS[self::MODEL_VIEW_CACHE_KEY][$key] = $views;
    }

    public static function getModelName($model): String
    {
        $modelName = $model->_a['model'];

        return $modelName;
    }

    /**
     *
     * @deprecated
     */
    public static function getAssocTable(\Pluf\Data\Model $from, \Pluf\Data\Model $to): String
    {
        $hay = array(
            strtolower($from->_a['model']),
            strtolower($to->_a['model'])
        );
        sort($hay);
        $prefix = $from->getEngine()
            ->getSchema()
            ->getPrefix();
        return self::skipeName($prefix . $hay[0] . '_' . $hay[1] . '_assoc');
    }

    public static function getTable($model): String
    {
        $table = $model->_con->pfx . $model->_a['table'];
        $name = self::skipeName($table);
        return $name;
    }

    public static function getAssocField($model): String
    {
        $name = self::skipeName(strtolower($model->_a['model']) . '_id');
        $name = $model->getEngine()
            ->getSchema()
            ->qn($name);
        return $name;
    }

    public static function skipeName(String $name): String
    {
        $name = str_replace('\\', '_', $name);
        return $name;
    }
}

