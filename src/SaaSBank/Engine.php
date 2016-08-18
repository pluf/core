<?php

/**
 * سرویس پرداخت‌ها را برای ماژولهای داخلی سیستم ایجاد می کند.
 * 
 * @author maso<mostafa.barmshory@dpq.co.ir>
 *
 */
class SaaSBank_Engine implements JsonSerializable {
	
	/**
	 *
	 * @return string
	 */
	public function getType() {
		return 'type';
	}
	
	/**
	 *
	 * @return string
	 */
	public function getTitle() {
		return 'title';
	}
	
	/**
	 *
	 * @return string
	 */
	public function getDescription() {
		return 'description';
	}
	
	/**
	 *
	 * @return string
	 */
	public function getSymbol() {
		return 'symbol';
	}
	
	/**
	 */
	public function create() {
		// XXX: maso, 1395: ایجاد یک پرداخت
	}
	
	/**
	 */
	public function update() {
		// XXX: maso, 1395: ایجاد یک پرداخت
	}
	
	/**
	 * (non-PHPdoc)
	 *
	 * @see JsonSerializable::jsonSerialize()
	 */
	public function jsonSerialize() {
		$coded = array (
				'type' => $this->getType (),
				'title' => $this->getTitle (),
				'description' => $this->getDescription (),
				'symbol' => $this->getSymbol () 
		);
		return $coded;
	}
}