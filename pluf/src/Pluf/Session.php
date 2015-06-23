<?php
/**
 * ساختار داده‌ای یک نشست را تعیین می‌کند
 * 
 * @author maso
 *
 */
class Pluf_Session extends Pluf_Model
{

    public $_model = 'Pluf_Session';

    public $data = array();

    public $cookie_name = 'sessionid';

    public $touched = false;

    public $test_cookie_name = 'testcookie';

    public $test_cookie_value = 'worked';

    public $set_test_cookie = false;

    public $test_cookie = null;

    /**
     * یک نمونه جدید از این کلاس ایجاد می‌کند.
     * 
     * @see Pluf_Model::_init()
     */
    function _init ()
    {
        $this->cookie_name = Pluf::f('session_cookie_id', 'sessionid');
        parent::_init();
    }

    /**
     * ساختارهای داده‌ای مورد نیاز برای نشت را تعیین می‌کند.
     * 
     * @see Pluf_Model::init()
     */
    function init ()
    {
        $this->_a['table'] = 'sessions';
        $this->_a['model'] = 'Pluf_Session';
        $this->_a['cols'] = array(
                // It is mandatory to have an "id" column.
                'id' => array(
                        'type' => 'Pluf_DB_Field_Sequence',
                        // It is automatically added.
                        'blank' => true
                ),
                'version' => array(
                        'type' => 'Pluf_DB_Field_Integer',
                        'blank' => true
                ),
                'session_key' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 100
                ),
                'session_data' => array(
                        'type' => 'Pluf_DB_Field_Text',
                        'blank' => false
                ),
                'expire' => array(
                        'type' => 'Pluf_DB_Field_Datetime',
                        'blank' => false
                )
        );
        $this->_a['idx'] = array(
                'session_key_idx' => array(
                        'type' => 'unique',
                        'col' => 'session_key'
                )
        );
        $this->_admin = array();
        $this->_a['views'] = array();
    }

    /**
     * تعیین یک داده در نشست
     *
     * با استفاده از این فراخوانی می‌توان یک داده با کلید جدید در نشست ایجاد
     * کرد. این کلید برای دستیابی‌های
     * بعد مورد استفاده قرار خواهد گرفت.
     *
     * @param
     *            کلید داده
     * @param
     *            داده مورد نظر. در صورتی که مقدار آن تهی باشد به معنی
     *            حذف است.
     */
    function setData ($key, $value = null)
    {
        if (is_null($value)) {
            unset($this->data[$key]);
        } else {
            $this->data[$key] = $value;
        }
        $this->touched = true;
    }

    /**
     * داده معادل با کلید تعیین شده را برمی‌گرداند
     *
     * در صورتی که داده تعیین نشده بود مقدار پیش فرض تعیین شده به عنوان نتیجه
     * این فراخوانی
     * برگردانده خواهد شد.
     */
    function getData ($key = null, $default = '')
    {
        if (is_null($key)) {
            return parent::getData();
        }
        if (isset($this->data[$key])) {
            return $this->data[$key];
        } else {
            return $default;
        }
    }

    /**
     * تمام داده‌های موجود در نشت را پاک می‌کند.
     * 
     * تمام داده‌های ذخیره شده در نشست را پاک می‌کند.
     */
    function clear ()
    {
        $this->data = array();
        $this->touched = true;
    }

    /**
     * یک کلید نشت جدید را ایجاد می‌کند.
     * 
     * از این کلید برای دستیابی به داده‌ها استفاده می‌شود.
     */
    function getNewSessionKey ()
    {
        while (1) {
            $key = md5(
                    microtime() . rand(0, 123456789) . rand(0, 123456789) .
                             Pluf::f('secret_key'));
            $sess = $this->getList(
                    array(
                            'filter' => 'session_key=\'' . $key . '\''
                    ));
            if (count($sess) == 0) {
                break;
            }
        }
        return $key;
    }

    /**
     * Presave/create function to encode data into session_data.
     */
    function preSave ($create = false)
    {
        $this->session_data = serialize($this->data);
        if ($this->session_key == '') {
            $this->session_key = $this->getNewSessionKey();
        }
        $this->expire = gmdate('Y-m-d H:i:s', time() + 31536000);
    }

    /**
     * Restore function to decode the session_data into $this->data.
     */
    function restore ()
    {
        $this->data = unserialize($this->session_data);
    }

    /**
     * Create a test cookie.
     */
    public function createTestCookie ()
    {
        $this->set_test_cookie = true;
    }

    public function getTestCookie ()
    {
        return ($this->test_cookie == $this->test_cookie_value);
    }

    public function deleteTestCookie ()
    {
        $this->set_test_cookie = true;
        $this->test_cookie_value = null;
    }
}
