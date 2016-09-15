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
Pluf::loadFunction('Pluf_Shortcuts_RenderToResponse');

/**
 * یک نمونه جدید از پرداخت ایجاد می‌کند
 *
 * در صورتی که پیش از این نمونه‌ای برای پرداخت ایجاد شده باشد آن را به عنوان
 * نتیجه برمی‌گرداند.
 *
 * @param SaaSBank_Receipt $object            
 * @return SaaSBank_Receipt
 */
function SaaSBank_Shortcuts_receiptFactory ($object)
{
    if ($object == null || ! isset($object))
        return new Bank_Receipt();
    return $object;
}

/**
 * یک متور پرداخت را پیدا می‌کند.
 *
 * @param unknown $type            
 * @throws SaaSBank_Exception_EngineNotFound
 * @return unknown
 */
function SaaSBank_Shortcuts_GetEngineOr404 ($type)
{
    $items = SaaSBank_Service::engines();
    foreach ($items as $item) {
        if ($item->getType() === $type) {
            return $item;
        }
    }
    throw new SaaSBank_Exception_EngineNotFound();
}

/**
 *
 * @param unknown $id            
 * @throws Pluf_HTTP_Error404
 * @return SaaSBank_Backend
 */
function SaaSBank_Shortcuts_GetBankOr404 ($id)
{
    $item = new SaaSBank_Backend($id);
    if ((int) $id > 0 && $item->id == $id) {
        return $item;
    }
    throw new Pluf_HTTP_Error404("Backend not found (" . $id . ")");
}

/**
 *
 * @param unknown $id            
 * @throws Pluf_HTTP_Error404
 * @return SaaSBank_Receipt
 */
function SaaSBank_Shortcuts_GetReceiptOr404 ($id)
{
    $item = new SaaSBank_Receipt($id);
    if ((int) $id > 0 && $item->id == $id) {
        return $item;
    }
    throw new Pluf_HTTP_Error404("Receipt not found (" . $id . ")");
}