<?php

class Pluf_ModelUtils extends \Pluf\ModelUtils
{

    public static function getModelName($model): String
    {
        $modelName = $model->_a['model'];

        return $modelName;
    }

    /**
     *
     * @deprecated
     */
    public static function getAssocTable(Pluf_Model $from, Pluf_Model $to): String
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

    /**
     * Get a form to create an entity of type of the given model.
     *
     * @param
     *            Pluf_Model The model.
     * @param
     *            array Data to bound the form (null)
     *            
     * @return Pluf_Form_Model Form to create the model.
     */
    public static function getCreateForm($model, $data = null)
    {
        $extra = array(
            'model' => $model
        );
        return new Pluf_Form_Model($data, $extra, null);
    }

    /**
     * Get a form to update an entity of type ot the given model.
     *
     * @param
     *            Pluf_Model The model.
     * @param
     *            array Data to bound the form (null)
     *            
     * @return Object Form to update for this model.
     */
    public static function getUpdateForm($model, $data = null)
    {
        $extra = array(
            'model' => $model
        );
        return new Pluf_Form_UpdateModel($data, $extra, null);
    }
}