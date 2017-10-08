<?php

/**
 * ساختارهای کلی تعریف یک برچسب را تعیین می‌کند
 * 
 * با استفاده از برچسب می‌توان داده‌های مورد نیاز و یا الگوهای مورد نیاز در لایه
 * الگو را ایجاد کرد.
 * 
 * @author maso
 *
 */
class Pluf_Template_Tag {
	
	/**
	 *
	 * @var array
	 */
	protected $context;
	
	/**
	 * Constructor.
	 *
	 * @param
	 *        	Context Context object (null)
	 */
	function __construct($context = null) {
		$this->context = $context;
	}
}
