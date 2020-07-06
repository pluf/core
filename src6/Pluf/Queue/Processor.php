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
namespace Pluf\Pluf\Queue;

use Pluf\Signal;
use Pluf\Pluf\Queue;
use Pluf;

/**
 * Class to process a Pluf_Queue.
 *
 * This class is very simple as basically this is just a signal
 * handler. It goes throught the queue, get a free item, lock it,
 * process it by sending a signal then mark it as done.
 */
class Processor
{

    /**
     * Get an item to process.
     *
     * @return mixed False if no item to proceed.
     */
    public static function getItem()
    {
        $item = false;
        $db = Pluf::db();
        $db->begin();
        // In a transaction to not process the same item at
        // the same time from to processes.
        $gqueue = new Queue();
        $items = $gqueue->getList(array(
            'filter' => $db->qn('lock') . '=0',
            'order' => 'creation_dtime ASC'
        ));
        if ($items->count() > 0) {
            $item = $items[0];
            $item->lock = 1;
            $item->update();
        }
        $db->commit();
        if ($item === false)
            return false;
        // try to get the corresponding object
        $obj = Pluf::factory($item->model_class, $item->model_id);
        if ($obj->id != $item->model_id)
            $obj = null;
        return array(
            'queue' => $item,
            'item' => $obj
        );
    }

    public static function process()
    {
        while (false !== ($q = self::getItem())) {
            /**
             * [signal]
             *
             * Pluf_Queue_Processor::process
             *
             * [sender]
             *
             * Pluf_Queue_Processor
             *
             * [description]
             *
             * This signal allows an application to perform an action on a
             * queue item. The item is set to null if none existing.
             *
             * You must not modify the 'queue' object.
             *
             * [parameters]
             *
             * array('item' => $item, 'queue' => $queue);
             */
            Signal::send('Pluf_Queue_Processor::process', 'Pluf_Queue_Process', $q);
            $q['queue']->lock = 2;
            $q['queue']->update();
        }
    }
}
