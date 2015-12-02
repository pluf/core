<?php
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');
Pluf::loadFunction('Pluf_Shortcuts_RenderToResponse');
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('Pluf_Shortcuts_GetFormForModel');

/**
 * لایه نمایش برای واحد را ایجاد می‌کند
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class HM_Views_Part
{

    /**
     * یک واحد را به عنوان واحد پیش فرض تعیین می‌کند.
     *
     * @param unknown $request            
     * @param unknown $match            
     * @return Pluf_HTTP_Response_Json
     */
    public function setActive ($request, $match)
    {
        $part_id = $match[1];
        $part = Pluf_Shortcuts_GetObjectOr404('HM_Part', $part_id);
        $request->session->setData('part', $part->id);
        return new Pluf_HTTP_Response_Json($part);
    }

    /**
     *
     * @param unknown $request            
     * @param unknown $match            
     * @return Pluf_HTTP_Response_Json
     */
    public function getActive ($request, $match)
    {
        $part_id = $request->session->getData('part', '');
        if ($part_id === '') {
            return new Pluf_HTTP_Response_Json(new HM_Part());
        }
        $part = Pluf_Shortcuts_GetObjectOr404('HM_Part', $part_id);
        return new Pluf_HTTP_Response_Json($part);
    }

    /**
     * فهرستی از تمام واحدهای یک آپارتمان ایجاد می‌کند.
     *
     * \note این نمایش تنها از متد GET حمایت می‌کند.
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function parts ($request, $match)
    {
        $application = Pluf_Shortcuts_GetObjectOr404('SaaS_Application', 
                $match[1]);
        
        // maso, 1394: گرفتن فهرست مناسبی از پیام‌ها
        $pag = new Pluf_Paginator(new HM_Part());
        $pag->list_filters = array(
                'id',
                'title'
        );
        $pag->forced_where = new Pluf_SQL('apartment=%s', 
                array(
                        $application->id
                ));
        $pag->action = array(
                'HM_Views_Part::part'
        );
        $list_display = array(
                'title' => __('part title')
        );
        $search_fields = array(
                'id',
                'part_number',
                'title'
        );
        $sort_fields = array(
                'id',
                'title',
                'part_number',
                'creation_date',
                'modif_dtime'
        );
        $pag->configure($list_display, $search_fields, $sort_fields);
        $pag->items_per_page = $this->getListCount($request);
        $pag->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($pag->render_object());
    }

    /**
     *
     * @var unknown
     */
    public $create_precond = array(
            'Pluf_Precondition::loginRequired'
    );

    /**
     *
     * @param unknown $request            
     * @param unknown $match            
     * @return Pluf_HTTP_Response_Json
     */
    public function create ($request, $match)
    {
        // اضافه کردن یک واحد جدید
        if ($request->method === 'POST') {
            $application = Pluf_Shortcuts_GetObjectOr404('SaaS_Application', 
                    $match[1]);
            if ($request->user->isAnonymous() || ! $request->user->hasPerm(
                    'SaaS.software-owner', $application)) {
                throw new Pluf_Exception_PermissionDenied(
                        "You are not application owner.");
            }
            
            $params = array(
                    'apartment' => $application,
                    'part' => null
            );
            $form = new HM_Form_Part(
                    array_merge($request->POST, $request->FILES), $params);
            $part = $form->save();
            return new Pluf_HTTP_Response_Json($part);
        }
        // maso, 1394: متدهای دیگر حمایت نشده است
        throw new Pluf_Exception_NotImplemented();
    }

    /**
     * پش فرض‌های دستکاری‌های داده‌های آپارتمان را ایجاد می‌کند
     *
     * @var unknown
     */
    public $part_precond = array(
            'Pluf_Precondition::loginRequired'
    );

    /**
     * داستکاری داده‌های واحدها را ایجاد می‌کند
     *
     * @param unknown $request            
     * @param unknown $match            
     * @throws HM_Exception_EmptyApartment
     * @throws Pluf_Exception_PermissionDenied
     * @throws Pluf_Exception_MismatchParameter
     * @throws Pluf_Exception_NotImplemented
     * @return Pluf_HTTP_Response_Json
     */
    public function part ($request, $match)
    {
        $application = Pluf_Shortcuts_GetObjectOr404('SaaS_Application', 
                $match[1]);
        $part = Pluf_Shortcuts_GetObjectOr404('HM_Part', $match[2]);
        if ($part->apartment != $application->id) {
            throw new Pluf_Exception_MismatchParameter();
        }
        // Get information
        if ($request->method === 'GET') {
            return new Pluf_HTTP_Response_Json($part);
        }
        // Update information
        if ($request->method === 'POST') {
            $params = array(
                    'apartment' => $application,
                    'part' => $part
            );
            $form = new HM_Form_Part(
                    array_merge($request->POST, $request->FILES), $params);
            $part = $form->update();
            return new Pluf_HTTP_Response_Json($part);
        }
        
        // maso, 1394: متدهای دیگر حمایت نشده است
        throw new Pluf_Exception_NotImplemented();
    }

    /**
     * پیش شرط‌هایی که برای فهرست کردن پرداخت‌های واحد مورد نیاز است
     * را تعیین می‌کند.
     *
     * @var unknown
     */
    public $payments_precond = array();

    /**
     * فهرست پرداخت‌ها را برای یک واحد تعیین می‌کند.
     *
     * @param unknown $request            
     * @param unknown $match            
     * @throws Pluf_Exception_NotImplemented
     */
    public function payments ($request, $match)
    {
        $count = 20;
        /*
         * TODO: maso, 1394: پارامترهای جستجو استفاده نشده است.
         * سه پارامتر زیر باید در جستجو استفاده شود، اگر توسط کاربر تعیین شده
         * باشد
         * -after
         * -before
         * -count
         */
        $application = Pluf_Shortcuts_GetObjectOr404('SaaS_Application', 
                $match[1]);
        $part = Pluf_Shortcuts_GetObjectOr404('HM_Part', $match[2]);
        if ($part->apartment !== $application->id) {
            throw new Pluf_Exception_MismatchParameter();
        }
        // maso, 1394: گرفتن فهرست مناسبی از پرداختها
        $pag = new Pluf_Paginator(new HM_Payment());
        $pag->list_filters = array(
                'id',
                'verified',
                'amount'
        );
        $pag->forced_where = new Pluf_SQL('part=%s AND deleted=0', 
                array(
                        $part->id
                ));
        $pag->action = array(
                'HM_Views_Part::part'
        );
        $list_display = array(
                'id' => 'id',
                'title' => 'title',
                'amount' => 'amount',
                'creation_dtime' => 'creation_dtime',
                'modif_dtime' => 'modif_dtime'
        );
        $search_fields = array(
                'title',
                'description',
                'amount'
        );
        $sort_fields = array(
                'title',
                'amount',
                'creation_dtime',
                'modif_dtime'
        );
        $pag->configure($list_display, $search_fields, $sort_fields);
        $pag->items_per_page = $this->getListCount($request);
        $pag->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($pag->render_object());
    }

    /**
     * پیش شرطهای ایجاد یک پرداخت را تعیین می‌کند
     *
     * @var unknown
     */
    public $payment_precond = array();

    /**
     * یک پرداخت را برای یک واحد ایجاد می‌کند.
     *
     * @param unknown $request            
     * @param unknown $match            
     * @throws Pluf_Exception_NotImplemented
     */
    public function payment ($request, $match)
    {
        if ($request->method != 'GET') {
            throw new Pluf_Exception_GetMethodSuported();
        }
        $application = Pluf_Shortcuts_GetObjectOr404('SaaS_Application', 
                $match[1]);
        $part = Pluf_Shortcuts_GetObjectOr404('HM_Part', $match[2]);
        if ($part->apartment !== $application->id) {
            throw new Pluf_Exception_MismatchParameter();
        }
        $payment = Pluf_Shortcuts_GetObjectOr404('HM_Payment', $match[3]);
        if ($part->id != $payment->part) {
            throw new Pluf_Exception_PermissionDenied(
                    "Payment is not related to the part.");
        }
        
        // maso, 1394: دریافت اطلاعات
        if ($request->method == 'GET') {
            return new Pluf_HTTP_Response_Json($payment);
        }
        
        // XXX: maso, 1394: بررسی امکان دسترسی به این کار
        if ($request->method == 'POST') {
            $params = array(
                    'part' => $part,
                    'payment' => $payment
            );
            $form = new HM_Form_UpdatePayment(
                    array_merge($request->POST, $request->FILES), $params);
            $payment = $form->update();
            return new Pluf_HTTP_Response_Json($payment);
        }
        
        if ($request->method == 'DELETE') {
            $payment->deleted = true;
            if (! $payment->update()) {
                throw new Pluf_Exception(__('fail to update the payment'));
            }
            return new Pluf_HTTP_Response_Json($payment);
        }
        
        throw new Pluf_Exception_NotImplemented();
    }

    /**
     * پیش شرطهای ایجاد یک پرداخت را تعیین می‌کند
     *
     * @var unknown
     */
    public $createPayment_precond = array(
            'HM_Precondition::apartmentOwner'
    );

    /**
     * یک پرداخت را برای یک واحد ایجاد می‌کند.
     *
     * @param unknown $request            
     * @param unknown $match            
     * @throws Pluf_Exception_NotImplemented
     */
    public function createPayment ($request, $match)
    {
        $part = Pluf_Shortcuts_GetObjectOr404('HM_Part', $match[1]);
        $params = array(
                'part' => $part
        );
        $form = new HM_Form_CreatePayment(
                array_merge($request->POST, $request->FILES), $params);
        $payment = $form->save();
        return new Pluf_HTTP_Response_Json($payment);
    }

    /**
     * تعداد گزینه‌های یک لیست را تعیین می‌کند.
     *
     * TODO: maso, 1394: این تعداد می‌تواند برای کاربران متفاوت باشد.
     *
     * @param unknown $request            
     * @return number
     */
    private function getListCount ($request)
    {
        $count = 20;
        if (array_key_exists('_px_count', $request->GET)) {
            $count = $request->GET['_px_count'];
            if ($count > 20) {
                $count = 20;
            }
        }
        return $count;
    }
}