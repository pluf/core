<?php
namespace Pluf;

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
        return '\\' . $objr->getName();
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

    public static function putModelToCache(Pluf_Model $model)
    {
        $key = self::getModelCacheKey($model);
        if (isset($GLOBALS[self::MODEL_CACHE_KEY][$key])) {
            return true;
        }
        $GLOBALS[self::MODEL_CACHE_KEY][$key] = array(
            'cache' => $model->_cache,
            'm' => $model->_m,
            'a' => $model->_a,
            'fk' => $model->_fk,
            'data' => $model->_data
        );
    }
}

