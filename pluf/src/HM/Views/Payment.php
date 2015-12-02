<?php
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');
Pluf::loadFunction('Pluf_Shortcuts_RenderToResponse');
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('Pluf_Shortcuts_GetFormForModel');

/**
 * لایه نمایش برای پرداخت را ایجاد می‌کند
 *
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class HM_Views_Payment
{

    /**
     * پیش شرط‌های دسترسی به فهرست پرداخت‌ها
     *
     * @var unknown
     */
    public $payments_precond = array(
            'Pluf_Precondition::staffRequired'
    );

    /**
     * فهرستی از تمام پرداخت‌های موجود ایجاد می‌کند.
     *
     * \note این نمایش تنها از متد GET حمایت می‌کند.
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function payments ($request, $match)
    {
        throw new Pluf_Exception_NotImplemented();
    }

    public function get ($request, $match)
    {
        $payment = Pluf_Shortcuts_GetObjectOr404('HM_Payment', $match[1]);
        return new Pluf_HTTP_Response_Json($payment);
    }

    public $update_precond = array(
            'Pluf_Precondition::loginRequired'
    );

    public function update ($request, $match)
    {
        $payment = Pluf_Shortcuts_GetObjectOr404('HM_Payment', $match[1]);
        HM_Precondition::paymentOwner($request, $payment);
        $params = array(
                'payment' => $payment
        );
        $form = new HM_Form_UpdatePayment(
                array_merge($request->POST, $request->FILES), $params);
        $payment = $form->update();
        return new Pluf_HTTP_Response_Json($payment);
    }

    public $delete_precond = array(
            'Pluf_Precondition::loginRequired'
    );

    public function delete ($request, $match)
    {
        throw new Pluf_Exception_NotImplemented();
    }
}