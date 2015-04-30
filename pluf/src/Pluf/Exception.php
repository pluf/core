<?php
/**
 * ساختار استاندارد مدیریت خطا را ایجاد می‌کند.
 * 
 * 
 * @author maso
 *
 */
class Pluf_Exception extends Exception implements JsonSerializable {
	protected $status;
	protected $link;
	protected $developerMessage;
	
	/**
	 * یک نمونه از این کلاس ایجاد می‌کند.
	 *
	 * @param string $message        	
	 * @param string $code        	
	 * @param string $previous        	
	 */
	public function __construct($message = "Unknown exception", $code = 4000, $previous = null, $status = 400, $link = null, $developerMessage = null) {
		parent::__construct ( $message, $code, $previous );
		$this->status = $status;
		$this->link = $link;
		$this->developerMessage = $developerMessage;
	}
	
	public function getDeveloperMessage(){
		return $this->developerMessage;
	}
	
	public function setDeveloperMessage($message){
		$this->developerMessage = $message;
	}
	
	public function getStatus() {
		return $this->status;
	}
	
	public function setStatus($status) {
		$this->status = $status;
	}
	
	public function jsonSerialize() {
		return [ 
				'code' => $this->code,
				'status' => $this->status,
				'link' => $this->link, 
				'message' => $this->message, 
				'developerMessage' => $this->developerMessage, 
		];
	}
}