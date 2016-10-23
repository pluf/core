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

/**
 * For each model having a 'foreignkey' or a 'manytomany' colum, details
 * must be added here.
 * These details are used to generated the methods
 * to retrieve related models from each model.
 */
$user_model = Pluf::f('pluf_custom_user', 'Pluf_User');
$group_model = Pluf::f('pluf_custom_group', 'Pluf_Group');

Pluf_Signal::connect('Pluf_Dispatcher::postDispatch', 
        array(
                'Pluf_Log',
                'flushHandler'
        ), 'Pluf_Dispatcher');

return array(
        $user_model => array(
                'relate_to_many' => array(
                        $group_model,
                        'Pluf_Permission'
                )
        ),
        $group_model => array(
                'relate_to_many' => array(
                        'Pluf_Permission'
                )
        ),
        'Pluf_Message' => array(
                'relate_to' => array(
                        $user_model
                )
        ),
        'Pluf_RowPermission' => array(
                'relate_to' => array(
                        'Pluf_Permission'
                )
        ),
        'Pluf_Search_Occ' => array(
                'relate_to' => array(
                        'Pluf_Search_Word'
                )
        )
);
