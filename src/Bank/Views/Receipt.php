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
Pluf::loadFunction('Bank_Shortcuts_GetEngineOr404');
Pluf::loadFunction('Bank_Shortcuts_GetReceiptOr404');

/**
 *
 * @author maso <mostafa.barmsohry@dpq.co.ir>
 *        
 */
class Bank_Views_Receipt
{

    /**
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public function find ($request, $match)
    {
        $pag = new Pluf_Paginator(new Bank_Receipt());
        $pag->configure(array(), 
                array( // search
                        'title',
                        'description'
                ), 
                array( // sort
                        'id',
                        'title',
                        'creation_dtime'
                ));
        $pag->action = array();
        $pag->items_per_page = 20;
        $pag->sort_order = array(
                'creation_dtime',
                'DESC'
        );
        $pag->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($pag->render_object());
    }

    /**
     * پرداخت جدیدی ایجاد می‌کند.
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public function create ($request, $match)
    {
        $receipt = Bank_Service::create($request, $request->REQUEST);
        return new Pluf_HTTP_Response_Json($receipt);
    }

    /**
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public function get ($request, $match)
    {
        $receipt = Bank_Shortcuts_GetReceiptOr404($match['id']);
        return new Pluf_HTTP_Response_Json($receipt);
    }

    /**
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public function getBySecureId ($request, $match)
    {
        $receipt = new Bank_Receipt();
        $sql = new Pluf_SQL('secure_id=%s', 
                array(
                        $match['secure_id']
                ));
        $receipt = $receipt->getOne($sql->gen());
        return new Pluf_HTTP_Response_Json($receipt);
    }

    /**
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public function update ($request, $match)
    {
        $receipt = Bank_Shortcuts_GetReceiptOr404($match['id']);
        return new Pluf_HTTP_Response_Json(Bank_Service::update($receipt));
    }

    /**
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public function updateBySecureId ($request, $match)
    {
        $receipt = new Bank_Receipt();
        $sql = new Pluf_SQL('secure_id=%s', 
                array(
                        $match['secure_id']
                ));
        $receipt = $receipt->getOne($sql->gen());
        $backend = $receipt->get_backend();
        $engine = $backend->get_engine();
        if ($engine->update($receipt)) {
            $receipt->update();
        }
        return new Pluf_HTTP_Response_Json($receipt);
    }

    /**
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public function delete ($request, $match)
    {
        $receipt = Bank_Shortcuts_GetReceiptOr404($match['id']);
        $receipt->delete();
        return new Pluf_HTTP_Response_Json($receipt);
    }
}
