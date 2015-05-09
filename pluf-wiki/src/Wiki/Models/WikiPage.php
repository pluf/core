<?php

/**
 * ساختار داده‌ای یک خانه را تعیین می‌کند.
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class Wiki_Models_WikiPage extends Pluf_Model implements JsonSerializable {
	
	/**
	 * @brief مدل داده‌ای را بارگذاری می‌کند.
	 *
	 * @see Pluf_Model::init()
	 */
	function init() {
		$this->_a ['table'] = 'wiki_page';
		$this->_a ['model'] = 'Wiki_Models_WikiPage';
		$this->_model = 'Wiki_Models_WikiPage';
		
		$modelid = array ();
		$modelid ['type'] = 'Pluf_DB_Field_Sequence';
		$modelid ['blank'] = true;
		
		$title = array (
				'type' => 'Pluf_DB_Field_Varchar',
				'blank' => false,
				'size' => 250,
				'verbose' => __ ( 'title' ),
				'help_text' => __ ( 'The title of the page must only contain letters, digits or the dash character. For example: My-new-Wiki-Page.' ) 
		);
		$language = array (
				'type' => 'Pluf_DB_Field_Varchar',
				'blank' => false,
				'size' => 50,
				'verbose' => __ ( 'language' ),
				'help_text' => __ ( 'The language of the page must only contain letters. For example: en.' ) 
		);
		$summary = array (
				'type' => 'Pluf_DB_Field_Varchar',
				'blank' => false,
				'size' => 250,
				'verbose' => __ ( 'summary' ),
				'help_text' => __ ( 'A one line description of the page content.' ) 
		);
		$content = array (
				'type' => 'Pluf_DB_Field_Compressed',
				'blank' => false,
				'verbose' => __ ( 'content' ) 
		);
		$creation_dtime = array (
				'type' => 'Pluf_DB_Field_Datetime',
				'blank' => true,
				'verbose' => __ ( 'creation date' ) 
		);
		$modif_dtime = array (
				'type' => 'Pluf_DB_Field_Datetime',
				'blank' => true,
				'verbose' => __ ( 'modification date' ) 
		);
		
		$cols ['id'] = $modelid;
		$cols ['language'] = $language;
		$cols ['title'] = $title;
		$cols ['summary'] = $summary;
		$cols ['content'] = $content;
		$cols ['creation_dtime'] = $creation_dtime;
		$cols ['modif_dtime'] = $modif_dtime;
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
	
	/**
	 * ساختار داده‌ای این کلاس را به ساختار JSON تبدیل می‌کند.
	 *
	 * @see JsonSerializable::jsonSerialize()
	 */
	public function jsonSerialize() {
		return [ 
				'id' => $this->id,
				'language' => $this->language,
				'title' => $this->title,
				'summary' => $this->summary,
				'body' => $this->content,
				'creation' => $this->creation_dtime,
				'modifed' => $this->modif_dtime 
		];
	}
	static function getWikiPageFile($title, $language) {
		$page = new HM_Models_WikiPage ();
		$page->title = $title;
		$page->language = $language;
		$page->content = "This page is created for a test and will ber replaced with the actual materials as soon as possible.";
		return $page;
	}
}