<?php
/* -*- tab-width: 4; indent-tabs-mode: nil; c-basic-offset: 4 -*- */
/*
# ***** BEGIN LICENSE BLOCK *****
# This file is part of Plume Framework, a simple PHP Application Framework.
# Copyright (C) 2001-2006 Loic d'Anterroches and contributors.
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

// We directly load the functions we are often going to use in the
// views. This makes the code cleaner.
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');
Pluf::loadFunction('Pluf_Shortcuts_RenderToResponse');
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('Pluf_Shortcuts_GetFormForModel');

/**
 * The views of the Todo application.
 *
 * As the application is rather simple, all the views are within one class
 * but for a complex application views will be spread over several classes.
 * In that case the recommended structure is to have your views in:
 * - YourApp/Views/ViewOne.php
 * - YourApp/Views/ViewTwo.php
 *
 * For example, we could have broken the current views of the Todo app in:
 * - Todo/Views.php : The install/uninstall/main views
 * - Todo/Views/Item.php : Views related to the items
 * - Todo/Views/Cat.php : Views related to the categories
 *
 * Of course you are flexible to use the structure you want, but a little
 * ordering eases the maintenance on the long run.
 *
 * Each method is answering a request. The method is called by the 
 * dispatcher if a dispatcher is used. The content of each method can
 * also be directly put into a .php file if no dispatcher is used.
 */
class Todo_Views
{
    /**
     * Display the main page of the application.
     *
     * The main page is only displaying data. So we load the categories
     * and for each category we display the corresponding items.
     *
     * @param Pluf_HTTP_Request Request object
     * @param array Matches against the regex of the dispatcher
     * @return Pluf_HTTP_Response or can throw Exception
     */
    public function main($request, $match)
    {
        // In the main page we want a list of all the Todo lists with
        // a link to edit each of them, a link to see the content and
        // a link to create a new list.
        // Get the complete list of Todo_List object
        $lists = Pluf::factory('Todo_List')->getList();
        // Create a context for the template
        $context = new Pluf_Template_Context(array('page_title' => 'Home',
                                                   'lists' => $lists)
                                             );
        // Load a template
        $tmpl = new Pluf_Template('todo/index.html');
        // Render the template and send the response to the user
        return new Pluf_HTTP_Response($tmpl->render($context));
    }


    /**
     * Display the viewItem page of the application.
     *
     * @param Pluf_HTTP_Request Request object
     * @param array Matches against the regex of the dispatcher
     * @return Pluf_HTTP_Response or can throw Exception
     */
    public function viewItem($request, $match)
    {
        // Basically the same as the viewList view but with a Todo_Item
        $item_id = $match[1];
        // We now are loading the corresponding item
        $item = new Todo_Item($item_id);
        // And check that the item has been found
        if ($item->id != $item_id) {
            return new Pluf_HTTP_Response_NotFound('The item has not been found.');
        }
        // Now we get the list in wich the item is
        $list = $item->get_list();
        // We have the item and the list, just display them. Instead of
        // creating a context, then a template and then rendering it
        // within a response object, we are going to use a shortcut
        // function. Using shortcuts is better as you end up having a
        // cleaner code.
        return Pluf_Shortcuts_RenderToResponse('todo/item/view.html',
                                               array('page_title' => 'View Item',
                                                     'list' => $list,
                                                     'item' => $item));
    }

    /**
     * Display the addItem page of the application.
     *
     * @param Pluf_HTTP_Request Request object
     * @param array Matches against the regex of the dispatcher
     * @return Pluf_HTTP_Response or can throw Exception
     */
    public function addItem($request, $match)
    {
        // The workflow of the addition of an item is simple
        // If the request of GET method a form is displayed
        // If it is a POST method, the form is submitted and the
        // content is proceeded to create the new item.
        // We create a Todo_Item item as we are creating one here
        $item = new Todo_Item();
        $list = Pluf_Shortcuts_GetObjectOr404('Todo_List', $match[1]);
        if ($request->method == 'POST') {
            // We get the data submitted by the user and initialize
            // the form with.
            $form = Pluf_Shortcuts_GetFormForModel($item, $request->POST);
            if ($form->isValid()) {
                // If no errors, we can save the Todo_Item from the data
                $item = $form->save();
                // We redirect the user to the page of the Todo_List in which
                // we have created the item.
                // We redirect the user to the page of the Todo_List
                // in which we have updated the item. We are using a
                // shortcut to get the URL directly from the view name
                // of interest. This allows us to not hard code the
                // path to the view in the view itself.
                $url = Pluf_HTTP_URL_urlForView('Todo_Views::viewList',
                                                array($item->list));
                return new Pluf_HTTP_Response_Redirect($url);
            }
        } else {
            // As we already now the list in which we are going to add
            // the item, we pass it as initial value. The user can
            // change it in the select box.
            $initial = array('list'=>$list->id);
            $form = Pluf_Shortcuts_GetFormForModel($item, $initial);
        }
        // Here we are with a GET request or a POST request with errors
        // So we create the rendering view of the form
        // We create a new rendering view
        return Pluf_Shortcuts_RenderToResponse('todo/item/add.html',
                                               array('page_title' => 'Create a Todo Item',
                                                     'list' => $list,
                                                     'form' => $form));
    }

    /**
     * Display the updateItem page of the application.
     *
     * @param Pluf_HTTP_Request Request object
     * @param array Matches against the regex of the dispatcher
     * @return Pluf_HTTP_Response or can throw Exception
     */
    public function updateItem($request, $match)
    {
        // Updating an item is somehow like creating an object but you
        // need first to load it to populate the form The workflow of
        // the update of an item is simple If the request of GET
        // method a form is displayed If it is a POST method, the form
        // is submitted and the content is proceeded to update item.
        // We create a Todo_Item item as we are updating one here

        // Here we are going to use another shortcut to get the item
        // or return a 404 error page if failing.
        $item = Pluf_Shortcuts_GetObjectOr404('Todo_Item', $match[1]);
        $new_data = $item->getData();
        if ($request->method == 'POST') {
            // We get the data submitted by the user
            $form = Pluf_Shortcuts_GetFormForModel($item, $request->POST);
            if ($form->isValid()) {
                // The form is valid, we save it.
                $item = $form->save();
                // We redirect the user to the page of the Todo_List
                // in which we have updated the item. We are using a
                // shortcut to get the URL directly from the view name
                // of interest. This allows us to not hard code the
                // path to the view in the view itself.
                $url = Pluf_HTTP_URL_urlForView('Todo_Views::viewList',
                                                array($item->list));
                return new Pluf_HTTP_Response_Redirect($url);
            }
        } else {
            $form = Pluf_Shortcuts_GetFormForModel($item, $item->getData());
        }
        // We proceed the same way by creating a context for a template
        // and providing the results to the user.
        return Pluf_Shortcuts_RenderToResponse('todo/item/update.html',
                                 array('page_title' => 'Update a Todo Item',
                                       'item' => $item,
                                       'form' => $form));
    }

    /**
     * Display the deleteItem page of the application.
     *
     * @param Pluf_HTTP_Request Request object
     * @param array Matches against the regex of the dispatcher
     * @return Pluf_HTTP_Response or can throw Exception
     */
    public function deleteItem($request, $match)
    {
        // A delete of an item is like an update. First you check that
        // the item is available, then you delete it if the request is
        // a POST request, else you provide a form to ask confirmation
        // before deletion.
        $item = Pluf_Shortcuts_GetObjectOr404('Todo_Item', $match[1]);
        if ($request->method == 'POST') {
            // Store the list id.
            $list_id = $item->list;
            // We can here directly delete the Todo_Item. Note that if
            // your object is linking to other objects you need to be
            // sure that you are taking into consideration the
            // foreignkey and manytomany relationships.
            $item->delete();
            $url = Pluf_HTTP_URL_urlForView('Todo_Views::viewList',
                                            array($list_id));
            return new Pluf_HTTP_Response_Redirect($url);
        }
        // Here we are with a GET request we show a form with a
        // confirmation button to delete the item.
        return Pluf_Shortcuts_RenderToResponse('todo/item/delete.html',
                                               array('page_title' => 'Delete a Todo Item',
                                                     'item' => $item));
    }

    /**
     * Display the listLists page of the application.
     *
     * @param Pluf_HTTP_Request Request object
     * @param array Matches against the regex of the dispatcher
     * @return Pluf_HTTP_Response or can throw Exception
     */
    public function listLists($request, $match)
    {
        // The list of lists is like the home, so we just return
        // the same content.
        return $this->main($request, $match);
    }

    /**
     * Display the viewList page of the application.
     *
     * @param Pluf_HTTP_Request Request object
     * @param array Matches against the regex of the dispatcher
     * @return Pluf_HTTP_Response or can throw Exception
     */
    public function viewList($request, $match)
    {
        // We are showing the content of the list.
        // That is, all the items in the list.
        $list = Pluf_Shortcuts_GetObjectOr404('Todo_List', $match[1]);
        // Now we get the items in the list
        $items = $list->get_todo_item_list();
        // We have the items and the list, just display them
        // Create a context for the template
        return Pluf_Shortcuts_RenderToResponse('todo/list/view.html',
                                               array('page_title' => 'View List',
                                                     'list' => $list,
                                                     'items' => $items));
    }

    /**
     * Display the updateList page of the application.
     *
     * @param Pluf_HTTP_Request Request object
     * @param array Matches against the regex of the dispatcher
     * @return Pluf_HTTP_Response or can throw Exception
     */
    public function updateList($request, $match)
    {
        // Take a look at updateItem() to have explanations. Here you
        // can see that the code is quite compact without comments.
        $list = Pluf_Shortcuts_GetObjectOr404('Todo_List', $match[1]);
        if ($request->method == 'POST') {
            $form = new Todo_Form_List($request->POST);
            if ($form->isValid()) {
                $list->setFromFormData($form->cleaned_data);
                $list->update();
                $url = Pluf_HTTP_URL_urlForView('Todo_Views::viewList',
                                                array($list->id));
                return new Pluf_HTTP_Response_Redirect($url);
            }
        } else {
            $form = new Todo_Form_List($list->getData());
        }
        return Pluf_Shortcuts_RenderToResponse('todo/list/update.html',
                                 array('page_title' => 'Edit a Todo List',
                                       'list' => $list,
                                       'form' => $form));
    }

    /**
     * Display the deleteList page of the application.
     *
     * @param Pluf_HTTP_Request Request object
     * @param array Matches against the regex of the dispatcher
     * @return Pluf_HTTP_Response or can throw Exception
     */
    public function deleteList($request, $match)
    {
        // Here we show how a list can be delete with the associated
        // Todo_Item.
        $list = Pluf_Shortcuts_GetObjectOr404('Todo_List', $match[1]);
        if ($request->method == 'POST') {
            // We need first to delete all the Todo_Item in the list.
            $items = $list->get_todo_item_list();
            foreach ($items as $item) {
                $item->delete();
            }
            // Then we can delete the list
            $list->delete();
            // You can also drop directly the list and the todo items
            // will be automatically dropped at the same time.
            $url = Pluf_HTTP_URL_urlForView('Todo_Views::main',
                                            array());
            return new Pluf_HTTP_Response_Redirect($url);
        }
        // Here we are with a GET request we show a form with a
        // confirmation button to delete the list.
        // To show the items to be deleted, we get them
        $items = $list->get_todo_item_list();
        return Pluf_Shortcuts_RenderToResponse('todo/list/delete.html',
                                 array('page_title' => 'Delete a Todo List',
                                       'list' => $list,
                                       'items' => $items));
    }

    /**
     * Display the addList page of the application.
     *
     * @param Pluf_HTTP_Request Request object
     * @param array Matches against the regex of the dispatcher
     * @return Pluf_HTTP_Response or can throw Exception
     */
    public function addList($request, $match)
    {
        // The workflow of the addition of an item is simple
        // If the request of GET method a form is displayed
        // If it is a POST method, the form is submitted and the
        // content is proceeded to create the new list.
        // We create a Todo_List item as we are creating one here
        $list = new Todo_List();
        if ($request->method == 'POST') {
            // We create the form bounded to the data submitted.
            $form = new Todo_Form_List($request->POST);
            if ($form->isValid()) {            
                // If no errors, we can save the Todo_List from the data
                
                $list->setFromFormData($form->cleaned_data);
                $list->create();
                // We redirect the user to the page of the Todo_List
                $url = Pluf_HTTP_URL_urlForView('Todo_Views::viewList',
                                                array($list->id));
                return new Pluf_HTTP_Response_Redirect($url);
            }
        } else {
            $form = new Todo_Form_List();
        }
        return Pluf_Shortcuts_RenderToResponse('todo/list/add.html',
                                 array('page_title' => 'Add a Todo List',
                                       'form' => $form));
    }

}
