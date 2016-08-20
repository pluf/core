<?php

class SaaSBank_Backend extends Pluf_Model
{

    /**
     * @brief مدل داده‌ای را بارگذاری می‌کند.
     *
     * تمام فیلدهای مورد نیاز برای این مدل داده‌ای در این متد تعیین شده و به
     * صورت کامل ساختار دهی می‌شود.
     *
     * @see Pluf_Model::init()
     */
    function init ()
    {
        $this->_a['table'] = 'saasbank_backend';
        $this->_a['model'] = 'SaaSBank_Backend';
        $this->_model = 'SaaSBank_Backend';
        $this->_a['cols'] = array(
                'id' => array(
                        'type' => 'Pluf_DB_Field_Sequence',
                        'blank' => true,
                        'verbose' => 'unique and no repreducable id fro reception'
                ),
                'title' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 50
                ),
                'description' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'size' => 200
                ),
                'symbol' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 50
                ),
                'home' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'size' => 50
                ),
                'redirect' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 50,
                        'secure' => true
                ),
                'meta' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'secure' => true
                ),
                'engine' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 50
                ),
                
                'creation_dtime' => array(
                        'type' => 'Pluf_DB_Field_Datetime',
                        'blank' => true,
                        'verbose' => 'creation date'
                ),
                'modif_dtime' => array(
                        'type' => 'Pluf_DB_Field_Datetime',
                        'blank' => true,
                        'verbose' => 'modification date'
                )
        );
        $this->_a['views'] = array(
                'global' => array(
                        'select' => $this->getGlobalSelect()
                )
        );
    }

    /*
     * @see Pluf_Model::preSave()
     */
    function preSave ($create = false)
    {
        if ($this->id == '') {
            $this->creation_dtime = gmdate('Y-m-d H:i:s');
        }
        $this->modif_dtime = gmdate('Y-m-d H:i:s');
    }

    /**
     * یک مقدار جدید در داده‌ها ایجاد می‌کند
     *
     * مقدار جدید با کلید $key و مقدار $value ایجاد می‌شود.
     * کلید یک مقدار رشته است که باید به صورت کیتا تعیین شود.
     *
     * @param String $key            
     * @param Object $value            
     */
    public function putMeta ($key, $value)
    {
        // TODO: maso, 1395: قراردادن یک مقدار در داده‌ها
        return $this;
    }

    /**
     *
     * @param unknown $key            
     */
    public function getMeta ($key)
    {
        // TODO: maso, 1395: گرفتن یک مقدار از داده‌ها
    }

    /**
     * کلید تعیین شده را از متا حذف می‌کند
     *
     * این تابع در صورتی که متا داده وجود داشته باشد آن را از مدل داده‌ای حذف
     * می‌کند.
     *
     * @param unknown $key            
     */
    public function removeMeta ($key)
    {
        // TODO: maso, 1395: کلید تعیین شده از داده‌های متا حذف می‌شود.
    }

    private function getGlobalSelect ()
    {
        if (isset($this->_cache['getGlobalSelect']))
            return $this->_cache['getGlobalSelect'];
        $select = array();
        $table = $this->getSqlTable();
        foreach ($this->_a['cols'] as $col => $val) {
            if (($val['type'] == 'Pluf_DB_Field_Manytomany') ||
                     (array_key_exists('secure', $val) && $val['secure'])) {
                continue;
            }
            $select[] = $table . '.' . $this->_con->qn($col) . ' AS ' .
                     $this->_con->qn($col);
        }
        $this->_cache['getSelect'] = implode(', ', $select);
        return $this->_cache['getSelect'];
    }
}