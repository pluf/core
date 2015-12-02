<?php
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');
Pluf::loadFunction('Pluf_Shortcuts_RenderToResponse');
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('Pluf_Shortcuts_GetFormForModel');

/**
 * لایه نمایش برای دستیابی به نرم افزار ساختمان‌ها را ایجاد می‌کند
 *
 * علاوه بر کنترل‌هایی که در افزونه SaaS ایجاد شده، در اینجا یک سری کنترل جدید
 * در رابطه
 * به نرم‌افزار ایجاد شده است. برای نمونه دسترسی به فهرست پرداخت‌های یک
 * آپارتمان.‌
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class HM_Views_Application extends Pluf_Views
{
    public $payments_precond = array(
            'SaaS_Precondition::baseAccess'
    );

    /**
     * فهرستی از پرداختهای آپارتمان تعیین می‌کند.
     *
     * @param unknown $request            
     * @param unknown $match            
     * @throws Pluf_Exception_NotImplemented
     */
    public function payments ($request, $match)
    {
        $count = 20;
        // maso, 1394: گرفتن فهرست مناسبی از پرداختها
        $pag = new Pluf_Paginator(new HM_Payment());
        $pag->forced_where = new Pluf_SQL('apartment=%s', 
                array(
                        $request->application->id
                ));
        $list_display = array(
                'id' => __('payment id'),
                'title' => __('title'),
                'description' => __('description'),
                'amount' => __('amount'),
                'creation_dtime' => __('create')
        );
        $search_fields = array(
                'hm_payment.title', // خصوصیت تکرار می‌شود باید جدول تعیین شود.
                'part',
                'description',
                'amount'
        );
        $sort_fields = array(
                'title',
                'amount',
                'creation_dtime'
        );
        $pag->configure($list_display, $search_fields, $sort_fields);
        $pag->list_filters = array(
                'id',
                'part',
                'amount'
        );
        $pag->items_per_page = $count;
        $pag->model_view = 'with_apartment';
        $pag->no_results_text = __('No payment is added yet.');
        $pag->sort_order = array(
                'creation_dtime',
                'DESC'
        );
        $pag->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($pag->render_object());
    }

    /**
     * پیش نیازهای دسترسی به آپارتمان
     *
     * @var unknown
     */
    public $createPayment_precond = array(
            'SaaS_Precondition::applicationOwner'
    );

    /**
     * یک پرداخت را برای تمام واحد‌ها ایجاد می‌کند
     *
     * @param unknown $request            
     * @param unknown $match            
     * @throws Pluf_Exception_NotImplemented
     */
    public function createPayment ($request, $match)
    {
        $params = array(
                'part' => null
        );
        $form = new HM_Form_CreatePayment(
                array_merge($request->POST, $request->FILES), $params);
        $form->save(false);
        $parts = $request->application->get_part_list();
        $payments = array();
        foreach ($parts as $key => $part) {
            $payment = $form->fill();
            $payment->part = $part;
            $payment->create();
            $payments[] = $payment;
        }
        return new Pluf_HTTP_Response_Json($payments);
    }
}