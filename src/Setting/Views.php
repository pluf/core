<?php
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('Pluf_Shortcuts_GetFormForModel');

/**
 * لایه نمایش مدیریت گروه‌ها را به صورت پیش فرض ایجاد می‌کند
 *
 * @author maso
 *        
 */
class Setting_Views extends Pluf_Views
{

    /**
     * مقدار یک خصوصیت را تعیین می‌کند.
     *
     * @param Pluf_HTTP_Request $request            
     * @param array $match            
     */
    public function get ($request, $match)
    { // Set the default
        $sql = new Pluf_SQL('tenant=%s AND type=%s AND Pluf_Configuration.key=%s', 
                array(
                        $request->tenant->id,
                        Pluf_ConfigurationType::APPLICATION,
                        $match['key']
                ));
        $model = new Pluf_Configuration();
        $model = $model->getOne(
                array(
                        'filter' => $sql->gen()
                ));
        if (! isset($model)) {
            $model = new Pluf_Configuration();
        }
        return new Pluf_HTTP_Response_Json($model);
    }

    /**
     * یک تنظیم را به روز می‌کند.
     *
     * در صورتی که تنظیم موجود نباشد آن را ایجاد کرده و مقدار تیعیین شده را
     * در آن قرار می‌دهد.
     *
     * @param Pluf_HTTP_Request $request            
     * @param array $match            
     */
    public function update ($request, $match)
    { // Set the default
        $sql = new Pluf_SQL('tenant=%s AND type=%s AND Pluf_Configuration.key=%s', 
                array(
                        $request->tenant->id,
                        Pluf_ConfigurationType::APPLICATION,
                        $match['key']
                ));
        $model = new Pluf_Configuration();
        $model = $model->getOne(
                array(
                        'filter' => $sql->gen()
                ));
        if (! isset($model)) {
            $model = new Pluf_Configuration();
            $form = Pluf_Shortcuts_GetFormForModel($model, $request->REQUEST);
            $model = $form->save(false);
            $model->tenant = $request->tenant;
            $model->type = Pluf_ConfigurationType::APPLICATION;
            $model->key = $match['key'];
            $model->create();
        } else {
            $form = Pluf_Shortcuts_GetFormForModel($model, $request->REQUEST);
            $model = $form->save();
        }
        return new Pluf_HTTP_Response_Json($model);
    }

    /**
     * مقدار یک خصوصیت را تعیین می‌کند.
     *
     * @param Pluf_HTTP_Request $request            
     * @param array $match            
     */
    public function delete ($request, $match)
    {
        $sql = new Pluf_SQL('tenant=%s AND type=%s AND Pluf_Configuration.key=%s', 
                array(
                        $request->tenant->id,
                        Pluf_ConfigurationType::APPLICATION,
                        $match['key']
                ));
        $model = new Pluf_Configuration();
        $model = $model->getOne(
                array(
                        'filter' => $sql->gen()
                ));
        if (! isset($model)) {
            $model = new Pluf_Configuration();
        } else {
            $model->delete();
        }
        return new Pluf_HTTP_Response_Json($model);
    }
}
