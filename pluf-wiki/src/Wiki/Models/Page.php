<?php

/**
 * ساختار داده‌ای یک خانه را تعیین می‌کند.
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class Wiki_Models_Page extends Pluf_Model {
	
	/**
	 * @brief مدل داده‌ای را بارگذاری می‌کند.
	 *
	 * @see Pluf_Model::init()
	 */
	function init() {
		$this->_a ['table'] = 'wiki_page';
		$this->_a ['model'] = 'Wiki_Models_Page';
		$this->_model = 'Wiki_Models_Page';
		
		$cols ['id'] = array (
				'type' => 'Pluf_DB_Field_Sequence',
				'blank' => true 
		);
		
		$cols ['title'] = array (
				'type' => 'Pluf_DB_Field_Varchar',
				'blank' => false,
				'size' => 250,
				'verbose' => __ ( 'title' ),
				'help_text' => __ ( 'The title of the page must only contain letters, digits or the dash character. For example: My-new-Wiki-Page.' ) 
		);
		$cols ['language'] = array (
				'type' => 'Pluf_DB_Field_Varchar',
				'blank' => false,
				'size' => 50,
				'verbose' => __ ( 'language' ),
				'help_text' => __ ( 'The language of the page must only contain letters. For example: en.' ) 
		);
		$cols ['summary'] = array (
				'type' => 'Pluf_DB_Field_Varchar',
				'blank' => false,
				'size' => 250,
				'verbose' => __ ( 'summary' ),
				'help_text' => __ ( 'A one line description of the page content.' ) 
		);
		$cols ['content'] = array (
				'type' => 'Pluf_DB_Field_Compressed',
				'blank' => false,
				'verbose' => __ ( 'content' ) 
		);
		$cols ['creation_dtime'] = array (
				'type' => 'Pluf_DB_Field_Datetime',
				'blank' => true,
				'verbose' => __ ( 'creation date' ) 
		);
		$cols ['modif_dtime'] = array (
				'type' => 'Pluf_DB_Field_Datetime',
				'blank' => true,
				'verbose' => __ ( 'modification date' ) 
		);
		
		$this->_a ['cols'] = $cols;
	}
	
	/**
	 * \brief پیش ذخیره را انجام می‌دهد
	 *
	 * @param $create حالت
	 *        	ساخت یا به روز رسانی را تعیین می‌کند
	 */
	function preSave($create = false) {
		if ($this->id == '') {
			$this->creation_dtime = gmdate ( 'Y-m-d H:i:s' );
		}
		$this->modif_dtime = gmdate ( 'Y-m-d H:i:s' );
	}
	
	/**
	 * حالت کار ایجاد شده را به روز می‌کند
	 *
	 * @see Pluf_Model::postSave()
	 */
	function postSave($create = false) {
		//
	}
	static function getWikiPageFile($title, $language) {
		$page = new HM_Models_WikiPage ();
		$page->title = $title;
		$page->language = $language;
		$page->content = "This page is created for a test and will ber replaced with the actual materials as soon as possible.";
		return $page;
	}
}