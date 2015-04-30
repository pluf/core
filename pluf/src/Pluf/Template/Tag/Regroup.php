<?php
/* -*- tab-width: 4; indent-tabs-mode: nil; c-basic-offset: 4 -*- */
/*
# ***** BEGIN LICENSE BLOCK *****
# This file is part of Plume Framework, a simple PHP Application Framework.
# Copyright (C) 2001-2010 Loic d'Anterroches and contributors.
#
# Plume Framework is free software; you can redistribute it and/or modify
# it under the terms of the GNU Lesser General Public License as published by
# the Free Software Foundation; either version 2.1 of the License, or
# (at your option) any later version.
#
# Plume Framework is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU Lesser General Public License for more details.
#
# You should have received a copy of the GNU Lesser General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
#
# ***** END LICENSE BLOCK ***** */

/**
 * Template tag <code>regroup</code>.
 *
 * Regroup a list of alike objects by a common attribute.
 *
 * This complex tag is best illustrated by use of an example:
 * say that people is a list of people represented by arrays with
 * first_name, last_name, and gender keys:
 * 
 * <code>
 * $people = array(
 *     array('first_name' => 'George',
 *           'last_name' => 'Bush',
 *           'gender' => 'Male'),
 *     array('first_name' => 'Bill',
 *           'last_name' => 'Clinton',
 *           'gender' => 'Male'),
 *     array('first_name' => 'Margaret',
 *           'last_name' => 'Thatcher',
 *           'gender' => 'Female'),
 *     array('first_name' => 'Condoleezza',
 *           'last_name' => 'Rice',
 *           'gender' => 'Female'),
 *     array('first_name' => 'Pat',
 *           'last_name' => 'Smith',
 *           'gender' => 'Unknow'),
 * );
 * </code>
 *
 * ...and you'd like to display a hierarchical list that is ordered by
 * gender, like this:
 *
 * <ul>
 *     <li>Male:
 *         <ul>
 *             <li>George Bush</li>
 *             <li>Bill Clinton</li>
 *         </ul>
 *     </li>
 *     <li>Female:
 *         <ul>
 *             <li>Margaret Thatcher</li>
 *             <li>Condoleezza Rice</li>
 *         </ul>
 *     </li>
 *     <li>Unknown:
 *         <ul>
 *             <li>Pat Smith</li>
 *         </ul>
 *     </li>
 * </ul>
 *
 * You can use the {regroup} tag to group the list of people by
 * gender. The following snippet of template code would accomplish this:
 *
 * <code>
 * {regroup $people, 'gender', 'gender_list'}
 * <ul>
 * {foreach $gender_list as $gender}
 *     <li>{$gender.grouper}:
 *         <ul>
 *         {foreach $gender.list as $item}
 *             <li>{$item.first_name} {$item.last_name}</li>
 *         {/foreach}
 *         </ul>
 *     </li>
 * {/foreach}
 * </ul>
 * </code>
 *
 * Let's walk through this example. {regroup} takes three arguments:
 * the object (array or instance of Pluf_Model or any object)
 * you want to regroup, the attribute to group by,and the name of the
 * resulting object. Here, we're regrouping the people list by the
 * gender attribute and calling the result gender_list. The result is
 * assigned in a context varible of the same name $gender_list.
 *
 * {regroup} produces a instance of ArrayObject (in this case, $gender_list)
 * of group objects. Each group object has two attributes:
 *
 * <ul>
 *     <li>grouper -- the item that was grouped by
 *         (e.g., the string "Male" or "Female").</li>
 *     <li>list -- an ArrayObject of all items in this group
 *         (e.g., an ArrayObject of all people with gender='Male').</li>
 * </ul>
 *
 * Note that {regroup} does not order its input!
 *
 * Based on concepts from the Django regroup template tag.
 */
class Pluf_Template_Tag_Regroup extends Pluf_Template_Tag
{
    /**
     * @see Pluf_Template_Tag::start()
     * @param mixed $data The object to group.
     * @param string $by The attribute ti group by.
     * @param string $assign The name of the resulting object.
     * @throws InvalidArgumentException If no argument is provided.
     */
    public function start($data, $by, $assign)
    {
        $grouped = array();
        $tmp = array();

        foreach ($data as $group) {
            if (is_object($group)) {
                if (is_subclass_of($group, 'Pluf_Model')) {
                    $raw = $group->getData();
                    if (!array_key_exists($by, $raw)) {
                        continue;
                    }
                } else {
                    $ref = new ReflectionObject($group);
                    if (!$ref->hasProperty($by)) {
                        continue;
                    }
                }
                $key = $group->$by;
                $list = $group;
            } else {
                if (!array_key_exists($by, $group)) {
                    continue;
                }
                $key = $group[$by];
                $list = new ArrayObject($group, ArrayObject::ARRAY_AS_PROPS);
            }

            if (!array_key_exists($key, $tmp)) {
                $tmp[$key] = array();
            }
            $tmp[$key][] = $list;
        }

        foreach ($tmp as $key => $list) {
            $grouped[] = new ArrayObject(array('grouper' => $key,
                                               'list' => $list),
                                         ArrayObject::ARRAY_AS_PROPS);
        }
        $this->context->set(trim($assign), $grouped);
    }
}
