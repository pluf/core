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
 * Simple queue system to delay the processing of tasks.
 *
 * What you do is that you push in the queue an object and what was
 * done on the object. Then later one, a simple script will go through
 * the queue and will check if something has to be done for the given
 * object.
 *
 * For example, you have articles with for each articles a list of
 * authors. You want to index the article with also the name of the
 * authors. So it means that you need to update the index when an
 * author is changed and when an article is changed. But you do not
 * want to do the indexing of all the articles of an author when you
 * update the author information (if this take 0.5s per article, with
 * 100 articles, you would have to wait nearly 1 minute!).
 *
 * So when you update an author you push in the queue: "author x has
 * been updated". Then you have a script that will go in the queue,
 * find that the author has been updated and index each of his
 * articles.
 */
class Pluf_Queue extends Pluf_Model
{

    function init ()
    {
        $this->_a['verbose'] =  'message queue' ;
        $this->_a['table'] = 'pluf_queue';
        $this->_a['cols'] = array(
                // It is mandatory to have an "id" column.
                'id' => array(
                        'type' => 'Pluf_DB_Field_Sequence',
                        // It is automatically added.
                        'blank' => true
                ),
                'version' => array(
                        'type' => 'Pluf_DB_Field_Integer',
                        'blank' => true
                ),
                'model_class' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 150,
                        'verbose' => __('model class')
                ),
                'model_id' => array(
                        'type' => 'Pluf_DB_Field_Integer',
                        'blank' => false,
                        'verbose' => __('model id')
                ),
                'action' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 150,
                        'verbose' => __('action')
                ),
                'lock' => array(
                        'type' => 'Pluf_DB_Field_Integer',
                        'blank' => false,
                        'verbose' => __('lock status'),
                        'default' => 0,
                        'choices' => array(
                                __('Free') => 0,
                                __('In progress') => 1,
                                __('Completed') => 2
                        )
                ),
                'creation_dtime' => array(
                        'type' => 'Pluf_DB_Field_Datetime',
                        'blank' => true,
                        'verbose' => __('created at')
                ),
                'modif_dtime' => array(
                        'type' => 'Pluf_DB_Field_Datetime',
                        'blank' => true,
                        'verbose' => __('modified at')
                )
        );
        $this->_a['idx'] = array(
                'lock_idx' => array(
                        'type' => 'normal',
                        'col' => 'lock'
                )
        );
    }

    function preSave ($create = false)
    {
        if ($this->id == '') {
            $this->creation_dtime = gmdate('Y-m-d H:i:s');
        }
        $this->modif_dtime = gmdate('Y-m-d H:i:s');
    }

    /**
     * Add an object to the queue.
     *
     * @param
     *            Pluf_Model Your model
     * @param
     *            string Action for the object
     */
    public static function addTo ($object, $action = '')
    {
        $q = new Pluf_Queue();
        $q->model_class = $object->_model;
        $q->model_id = $object->id;
        $q->lock = 0;
        $q->action = $action;
        $q->create();
    }
}

