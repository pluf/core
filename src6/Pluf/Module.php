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
namespace Pluf\Pluf;

class Module extends \Pluf\Module
{

    const moduleJsonPath = __DIR__ . '/module.json';

    const relations = array(
        'Pluf_Search_Occ' => array(
            'relate_to' => array(
                'Pluf_Search_Word'
            )
        )
    );

    const urlsPath = __DIR__ . '/urls.php';

    public function init(\Pluf $bootstrap): void
    {
        /**
         * For each model having a Engine::FOREIGNKEY or a Engine::MANY_TO_MANY colum, details
         * must be added here.
         * These details are used to generated the methods
         * to retrieve related models from each model.
         */
        \Pluf_Signal::connect('Pluf_Dispatcher::postDispatch', array(
            '\\Pluf\\Logger',
            'flushHandler'
        ), 'Pluf_Dispatcher');
    }
}

