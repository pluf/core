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
     */
    public function create ()
    {
        // XXX: maso, 1395: ایجاد یک پرداخت
    }

    /**
     */
    public function update ()
    {
        // XXX: maso, 1395: ایجاد یک پرداخت
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
                'validators' => ['NotNull', 'NotEmpty']
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