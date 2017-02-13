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
class Config_Service
{

    public static $inMemory = array(
            // example entry
            'key' => array(
                    'value' => 'value',
                    'derty' => false
            )
    );

    /**
     *
     * @param unknown $key            
     * @param unknown $defValue            
     * @return boolean|unknown|string
     */
    public static function get ($key, $defValue)
    {
        if (array_key_exists($key, self::$inMemory)) {
            $entary = self::$inMemory[$key];
        } else {
            $entary = array(
                    'value' => $defValue,
                    'derty' => false
            );
            // TODO: maso, 2017: load value
            $sql = new Pluf_SQL('type=%s AND configuration.key=%s', 
                    array(
                            Pluf_ConfigurationType::SYSTEM,
                            $key
                    ));
            $config = new Pluf_Configuration();
            $config = $config->getOne(
                    array(
                            'filter' => $sql->gen()
                    ));
            if (isset($config)) {
                $entary['value'] = $config->value;
            } else {
                $entary['derty'] = true;
            }
        }
        self::$inMemory[$key] = $entary;
        return $entary['value'];
    }

    /**
     *
     * @param unknown $key            
     * @param unknown $value            
     */
    public static function set ($key, $value)
    {
        self::$inMemory[$key] = array(
                value => $value,
                derty => true
        );
    }

    /**
     */
    public static function flush ()
    {
        foreach (self::$inMemory as $key => $val) {
            if ($val['derty']) {
                // TODO: maso, 2017: load value
                $sql = new Pluf_SQL('type=%s AND configuration.key=%s', 
                        array(
                                Pluf_ConfigurationType::SYSTEM,
                                $key
                        ));
                $config = new Pluf_Configuration();
                $config = $config->getOne(
                        array(
                                'filter' => $sql->gen()
                        ));
                if (isset($config)) {
                    $config->value = $val['value'];
                    $config->save();
                } else {
                    $config = new Pluf_Configuration();
                    $config->value = $val['value'];
                    $config->key = $key;
                    $config->type = Pluf_ConfigurationType::SYSTEM;
                    $config->create();
                }
            }
        }
    }
}