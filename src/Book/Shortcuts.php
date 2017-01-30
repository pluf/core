<?php

/**
 * صفحه تعیین شده با شناسه را پیدا می‌کند.
 * 
 * در صورتی که صحفه پیدا نشود خطا ۴۰۴ به عنوان نتیجه تولید و منتشر خواهد شد.
 *
 * @param int شناسه صفحه را تعیین می‌کند
 * @throws Book_Exception_PageNotFound
 * @return Book_Page صفحه‌ای که پیدا شده است.
 */
// function Book_Shortcuts_GetPageOr404 ($id)
// {
//     $item = new Book_Page($id);
//     if ((int) $id > 0 && $item->id == $id) {
//         return $item;
//     }
//     throw new Book_Exception_PageNotFound(
//             "Wiki page not found (Page id:" . $id . ")");
// }

/**
 * کتاب با شناسه تعیین شده را بر می‌گرداند.
 *
 * در صورتی که کتاب پیدا نشود خطا ۴۰۴ را به عنوان نتیجه متشر خواهد کرد.
 *
 * @param unknown $id            
 * @throws Book_Exception_BookNotFound
 * @return Book_Book معاد با شناسه ورودی
 */
// function Book_Shortcuts_GetBookOr404 ($id)
// {
//     $item = new Book_Book($id);
//     if ((int) $id > 0 && $item->id == $id) {
//         return $item;
//     }
//     throw new Book_Exception_PageNotFound(
//             "Wiki book not found (Page id:" . $id . ")");
// }

// function Book_Shortcuts_GetBookListCount ($request)
// {
//     $count = 20;
//     if (array_key_exists('_px_count', $request->REQUEST)) {
//         $count = $request->GET['_px_count'];
//         if ($count > 20) {
//             $count = 20;
//         }
//     }
//     return $count;
// }

// function Book_Shortcuts_GetPageListCount ($request)
// {
//     $count = 20;
//     if (array_key_exists('_px_count', $request->GET)) {
//         $count = $request->GET['_px_count'];
//         if ($count > 20) {
//             $count = 20;
//         }
//     }
//     return $count;
// }
