<?php

/**
 * سرویس پرداخت‌ها را برای ماژولهای داخلی سیستم ایجاد می کند.
 * 
 * @author maso<mostafa.barmshory@dpq.co.ir>
 *
 */
class SaaSBank_Engine implements JsonSerializable {
	const ENGINE_PREFIX = 'saasbank_engine_';
	/**
	 *
	 * @return string
	 */
	public function getType() {
		$name = strtolower ( get_class ( $this ) );
		// NOTE: maso, 1395: تمام متورهای پرداخت باید در پوشه تعیین شده قرار بگیرند
		if (strpos ( $name, SaaSBank_Engine::ENGINE_PREFIX ) !== 0) {
			throw new SaaSBank_Exception_EngineLoad ( 'Engine class must be placed in engine package.' );
		}
		return substr ( $name, strlen ( SaaSBank_Engine::ENGINE_PREFIX ) );
	}
	
	/**
	 *
	 * @return string
	 */
	public function getSymbol() {
		return $this->getType();
	}
	
	/**
	 *
	 * @return string
	 */
	public function getTitle() {
		return '';
	}
	
	/**
	 *
	 * @return string
	 */
	public function getDescription() {
		return '';
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