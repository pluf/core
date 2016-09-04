<?php

/**
 * سرویس پرداخت‌ها را برای ماژولهای داخلی سیستم ایجاد می کند.
 * 
 * @author maso<mostafa.barmshory@dpq.co.ir>
 *
 */
class SaaSBank_Engine implements JsonSerializable
{

    const ENGINE_PREFIX = 'saasbank_engine_';

    /**
     *
     * @return string
     */
    public function getType ()
    {
        $name = strtolower(get_class($this));
        // NOTE: maso, 1395: تمام متورهای پرداخت باید در پوشه تعیین شده قرار
        // بگیرند
        if (strpos($name, SaaSBank_Engine::ENGINE_PREFIX) !== 0) {
            throw new SaaSBank_Exception_EngineLoad(
                    'Engine class must be placed in engine package.');
        }
        return substr($name, strlen(SaaSBank_Engine::ENGINE_PREFIX));
    }

    /**
     *
     * @return string
     */
    public function getSymbol ()
    {
        return $this->getType();
    }

    /**
     *
     * @return string
     */
    public function getTitle ()
    {
        return '';
    }

    /**
     *
     * @return string
     */
    public function getDescription ()
    {
        return '';
    }

    /**
     * یک پرداخت جدید در بانک ایجاد می‌کند
     *
     * اطلاعات ایجاد شده برای پرداخت می‌تواند در متا قرار گیرد. و تمام اطلاعات
     * مورد نیاز باید ار تقاضا به دست آید.
     *
     * خود روال بر اساس اطلاعات متا باید بفهمد که ایا قبلا این را ساخته یا اولین
     * بار
     * است.
     *
     * پرداخت تنها با یک پشتیبان انجام می‌شود و تغییر پشتیبان غیر ممکن است.
     *
     * در صورتی که امکان انجام کار وجود نداشت باید خطا صادر شود.
     *
     * بعد از این روال ورودی ذخیره خواهد شد.
     *
     * @param unknown $receipt            
     */
    public function create ($receipt)
    {
        // XXX: maso, 1395: ایجاد یک پرداخت
    }

    /**
     * حالت پرداخت را بررسی و پرداخت را به روز می‌کند.
     *
     * اطلاعات مورد نیاز باید از متا برداشته شود.
     *
     * در صورتی که ‌شماره ارجا تعیین شود به معنی انجام شدن پرداخت است.
     *
     * در صورتی که بررسی مشکل داشته باشد خطا صادر می‌شود.
     *
     * در صورتی که پرداخت تکمیل شده باشد درستی برگردانده می‌شود در غیر این صورت
     * نا درستی.
     *
     * بعد از این فراخوانی داده‌ها باز ذخیره سازی می‌شود اگر و تنها اگر پرداخت
     * انجام شده باشد.
     *
     * @param unknown $receipt            
     */
    public function update ($receipt)
    {
        // XXX: maso, 1395: ایجاد یک پرداخت
        return false;
    }

    /**
     * (non-PHPdoc)
     *
     * @see JsonSerializable::jsonSerialize()
     */
    public function jsonSerialize ()
    {
        $coded = array(
                'type' => $this->getType(),
                'title' => $this->getTitle(),
                'description' => $this->getDescription(),
                'symbol' => $this->getSymbol()
        );
        return $coded;
    }

    /**
     * فهرستی از پارامترهای موتور پرداخت را تعیین می‌کند
     *
     * هر موتور پرداخت به دسته‌ای از پارامترها نیازمند است که باید توسط کاربر
     * تعیین شود. این فراخوانی پارامترهایی را تعیین می‌کند که برای استفاده از
     * این متور پرداخت باید تعیین کرد.
     *
     * خروجی این فراخوانی یک فهرست است توصیف خصوصیت‌ها است.
     */
    public function getParameters ()
    {
        $param = array(
                'name' => $this->getType(),
                'type' => 'struct',
                'title' => $this->getTitle(),
                'description' => $this->getDescription(),
                'editable' => true,
                'visible' => true,
                'priority' => 5,
                'symbol' => $this->getSymbol(),
                'children' => []
        );
        $general = $this->getGeneralParam();
        foreach ($general as $gp) {
            $param['children'][] = $gp;
        }
        
        $extra = $this->getExtraParam();
        foreach ($extra as $ep) {
            $param['children'][] = $ep;
        }
        return $param;
    }

    /**
     * فهرست خصوصیت‌های عمومی را تعیین می‌کند.
     *
     * @return
     *
     */
    public function getGeneralParam ()
    {
        $params = array();
        $params[] = array(
                'name' => 'title',
                'type' => 'String',
                'unit' => 'none',
                'title' => 'title',
                'description' => 'beackend title',
                'editable' => true,
                'visible' => true,
                'priority' => 5,
                'symbol' => 'title',
                'defaultValue' => 'no title',
                'validators' => [
                        'NotNull',
                        'NotEmpty'
                ]
        );
        $params[] = array(
                'name' => 'description',
                'type' => 'String',
                'unit' => 'none',
                'title' => 'description',
                'description' => 'beackend description',
                'editable' => true,
                'visible' => true,
                'priority' => 5,
                'symbol' => 'title',
                'defaultValue' => 'description',
                'validators' => []
        );
        $params[] = array(
                'name' => 'symbol',
                'type' => 'String',
                'unit' => 'none',
                'title' => 'Symbol',
                'description' => 'beackend symbol',
                'editable' => true,
                'visible' => true,
                'priority' => 5,
                'symbol' => 'icon',
                'defaultValue' => '',
                'validators' => []
        );
        return $params;
    }

    /**
     * خصوصیت‌های اضافه را تعیین می‌کند.
     */
    public function getExtraParam ()
    {
        // TODO: maso, 1395: فرض شده که این فراخوانی توسط پیاده‌سازی‌ها بازنویسی
        // شود
        return array();
    }
}