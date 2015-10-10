<?php

/**
 * ساختار داده‌ای پروفایل کاربر را تعیین می‌کند.
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class User_Profile extends Pluf_Model {
	
	/**
	 * @brief مدل داده‌ای را بارگذاری می‌کند.
	 *
	 * تمام فیلدهای مورد نیاز برای این مدل داده‌ای در این متد تعیین شده و به
	 * صورت کامل ساختار دهی می‌شود.
	 *
	 * @see Pluf_Model::init()
	 */
	function init() {
		$this->_a ['table'] = 'user_profile';
		$this->_a ['model'] = 'User_Profile';
		$this->_model = 'User_Profile';
		
		$this->_a ['cols'] = array (
				'id' => array (
						'type' => 'Pluf_DB_Field_Sequence',
						'blank' => true 
				),
				'user' => array (
						'type' => 'Pluf_DB_Field_Foreignkey',
						'model' => 'Pluf_User',
						'blank' => false,
						'unique' => true 
				),
				'level' => array (
						'type' => 'Pluf_DB_Field_Integer',
						'blank' => false,
						'unique' => false, 
				),
				'access_count' => array (
						'type' => 'Pluf_DB_Field_Integer',
						'blank' => false,
						'unique' => false, 
				),
				'validate' => array (
						'type' => 'Pluf_DB_Field_Boolean',
						'default' => false,
						'blank' => true,
						'verbose' => __ ( 'validate' ) 
				),
				'title' => array (
						'type' => 'Pluf_DB_Field_Varchar',
						'blank' => false,
						'size' => 50 
				),
				'state' => array (
						'type' => 'Pluf_DB_Field_Varchar',
						'blank' => true,
						'size' => 50 
				),
				'city' => array (
						'type' => 'Pluf_DB_Field_Varchar',
						'blank' => true,
						'size' => 50 
				),
				'country' => array (
						'type' => 'Pluf_DB_Field_Varchar',
						'blank' => true,
						'size' => 50 
				),
				'address' => array (
						'type' => 'Pluf_DB_Field_Varchar',
						'blank' => true,
						'size' => 200 
				),
				'national_id' => array (
						'type' => 'Pluf_DB_Field_Varchar',
						'blank' => true,
						'size' => 100 
				),
				'postal_code' => array (
						'type' => 'Pluf_DB_Field_Varchar',
						'blank' => true,
						'size' => 100 
				),
				'phone_number' => array (
						'type' => 'Pluf_DB_Field_Varchar',
						'blank' => true,
						'size' => 50,
						'unique' => false, 
				),
				'mobile_number' => array (
						'type' => 'Pluf_DB_Field_Varchar',
						'blank' => true,
						'size' => 50,
						'unique' => false, 
				),
				'shaba' => array (
						'type' => 'Pluf_DB_Field_Varchar',
						'blank' => true,
						'size' => 70,
						'unique' => false 
				),
				'creation_dtime' => array (
						'type' => 'Pluf_DB_Field_Datetime',
						'blank' => true,
						'verbose' => __ ( 'creation date' ) 
				),
				'modif_dtime' => array (
						'type' => 'Pluf_DB_Field_Datetime',
						'blank' => true,
						'verbose' => __ ( 'modification date' ) 
				) 
		);
	}
	
	/**
	 * پیش ذخیره را انجام می‌دهد
	 *
	 * در این فرآیند نیازهای ابتدایی سیستم به آن اضافه می‌شود. این نیازها مقادیری هستند که
	 * در زمان ایجاد باید تعیین شوند. از این جمله می‌توان به کاربر و تاریخ اشاره کرد.
	 *
	 * @param $create حالت
	 *        	ساخت یا به روز رسانی را تعیین می‌کند
	 */
	function preSave($create = false) {
		if ($this->id == '') {
			$this->creation_dtime = gmdate ( 'Y-m-d H:i:s' );
			$this->access_count = 0;
		}
		$this->modif_dtime = gmdate ( 'Y-m-d H:i:s' );
	}
	


	/**
	 * سطح کاربر را ارتقا می‌دهد.
	 *
	 * نوع عمل عمل انجام شده می‌توان سطح کاربر را افزایش و یا کاهش داد. این
	 * فراخوانی
	 * امکان افزایش سطح کاربر را تعیین می‌کند.
	 *
	 * برای این کار باید یک عمل به عنوان عمل انجام شده تعیین شود. هر عمل به صورت
	 * یک
	 * متغیر در سیستم در نظر گرفته می‌شود، و در صورتی که یک درجه برای عمل تعیین
	 * شده
	 * باشد به اندازه همان درجه به کاربر اضافه یا شاید کم می‌شود.
	 *
	 * پارامتر بعد تعیین کاهش و یا افزایش است که با یک مقدار درستی و یا نا درستی
	 * تعیین
	 * می‌شود.
	 */
	public function updateLevel ($action, $decrease = true)
	{
	    $values = Pluf::f ( 'user_profile_level_values', array() );
	    if(! array_key_exists($action, $values)){
	        return;
	    }
	    $value = $values[$action];
	    if($value == 0){
	        return;
	    }
	    if($decrease){
	        $this->level += $value;
	    } else {
	        $this->level -= $value;
	    }
	    $this->update();
	}
}