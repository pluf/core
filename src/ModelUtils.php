<?php
namespace Pluf;

class ModelUtils
{

    /**
     * Generates full path to the model
     *
     * @param Model $model
     * @return String
     */
    public static function getModelName(Model $model): String
    {
        return '\\' . $model->getClass()->getName();
    }

    public static function getAssocTable(Model $from, Model $to): String
    {
        $hay = array(
            $from->tableName,
            $to->tableName
        );
        sort($hay);
        $table = $from->_con->pfx . $hay[0] . '_' . $hay[1] . '_assoc';
        $table = self::skipeName($table);
        return $table;
    }

    public static function getTable(Model $model): String
    {
        $table = $model->_con->pfx . $model->tableName;
        $name = self::skipeName($table);
        return $name;
    }

    public static function getAssocField(Model $model): String
    {
        $name = self::skipeName($model->tableName . '_id');
        $name = $model->_con->qn($name);
        return $name;
    }

    public static function skipeName(String $name): String
    {
        $name = str_replace('\\', '_', $name);
        return strtolower($name);
    }

    /**
     * Get a form to create an entity of type of the given model.
     *
     * @param
     *            Model The model.
     * @param
     *            array Data to bound the form (null)
     *            
     * @return Model Form to create the model.
     */
    public static function getCreateForm(Model $model, Model $data = null)
    {
        $extra = array(
            'model' => $model
        );
        return new $model($data, $extra, null);
    }

    /**
     * Get a form to update an entity of type ot the given model.
     *
     * @param
     *            Model The model.
     * @param
     *            array Data to bound the form (null)
     *            
     * @return Object Form to update for this model.
     */
    public static function getUpdateForm(Model $model, Model $data = null)
    {
        $extra = array(
            'model' => $model
        );
        return new Form\FormModelUpdate($data, $extra, null);
    }

    // ----------------------------------------------------------
    // Condidate to move to ModelUtils
    // ----------------------------------------------------------

    /**
     * Get cached model
     *
     * @param \ReflectionClass $class
     */
    public static function getModelCache(\ReflectionClass $class)
    {
        $className = '\\' . $class->getName();
        $catch = $GLOBALS['_PX_models_init_cache'];
        if (array_key_exists($className, $catch)) {
            return $catch[$className];
        }
        return null;
    }

    /**
     * Get cached model
     *
     * @param \ReflectionClass $class
     */
    public static function putModelCache(\ReflectionClass $class, $data)
    {
        $className = '\\' . $class->getName();
        $GLOBALS['_PX_models_init_cache'][$className] = $data;
    }

    public static function getModelRelations(Model $model, string $type)
    {
        $className = '\\' . $model->getClass()->getName();
        $relations = $GLOBALS['_PX_models_related'][$type];
        if (array_key_exists($className, $relations)) {
            return $relations[$className];
        } else {
            \Pluf\Log::warn('No relation of  type:"' . $type . '" defined for Entity "' . $model->getClass()->getName() . '"');
        }
        return null;
    }
}