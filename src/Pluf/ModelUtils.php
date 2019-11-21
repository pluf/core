<?php

class Pluf_ModelUtils
{

    public static function getModelName($model): String
    {
        $modelName = $model->_a['model'];

        return $modelName;
    }

    public static function getAssocTable($from, $to): String
    {
        $hay = array(
            strtolower($from->_a['model']),
            strtolower($to->_a['model'])
        );
        sort($hay);
        $table = $from->_con->pfx . $hay[0] . '_' . $hay[1] . '_assoc';
        $table = self::skipeName($table);
        return $table;
    }

    public static function getTable($model) : String {
        $table = $model->_con->pfx . $model->_a['table'];
        $name = self::skipeName($table);
        return $name;
    }
    
    public static function getAssocField($model) : String {
        $name = self::skipeName(strtolower($model->_a['model']) . '_id');
        $name = $model->_con->qn($name);
        return $name;
    }

    public static function skipeName(String $name) : String {
        $name = str_replace('\\', '_', $name);
        return $name;
    }
}