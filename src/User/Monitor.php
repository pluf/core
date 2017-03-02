<?php

/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. http://dpq.co.ir
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

/**
 *
 * @author maso<mostafa.barmshory@dpq.co.ir>
 * @author hadi<mohammad.hadi.mansouri@dpq.co.ir>
 * @since 0.1.0
 */
class User_Monitor
{

    /**
     * Retruns permision status
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public static function permisson ($request, $match)
    {
        $result = array(
                'interval' => 100000,
                'type' => 'scaler'
        );
        
        // Check user
        if ($request->user->isAnonymous()) {
            $result['value'] = false;
            return $result;
        }
        
        // Get permission
        $per = new Pluf_Permission();
        $sql = new Pluf_SQL('code_name=%s', 
                array(
                        $match['property']
                ));
        $items = $per->getList(
                array(
                        'filter' => $sql->gen()
                ));
        if ($items->count() == 0) {
            $result['value'] = false;
            return $result;
        }
        
        // Check permission
        $result['value'] = $request->user->hasPerm($items[0]->toString());
        return $result;
    }
}
